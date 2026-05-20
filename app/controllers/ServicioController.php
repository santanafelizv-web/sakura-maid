<?php
class ServicioController {
    public function index(): void {
        requireLogin(); $user=authUser(); $db=getDB();
        if ($user['rol']==='cliente') {
            $s=$db->prepare("SELECT s.*,u.nombre mn,u.apellido ma FROM servicio s JOIN perfil_maid pm ON s.maid_id=pm.id JOIN usuario u ON pm.usuario_id=u.id WHERE s.cliente_id=? ORDER BY s.fecha DESC");
            $s->execute([$user['id']]); $servicios=$s->fetchAll(); $vista='client';
        } elseif ($user['rol']==='maid') {
            $pm=$db->prepare("SELECT id FROM perfil_maid WHERE usuario_id=?"); $pm->execute([$user['id']]); $mid=$pm->fetchColumn()?:0;
            $s=$db->prepare("SELECT s.*,u.nombre cn,u.apellido ca FROM servicio s JOIN usuario u ON s.cliente_id=u.id WHERE s.maid_id=? ORDER BY s.fecha DESC");
            $s->execute([$mid]); $servicios=$s->fetchAll(); $vista='maid';
        } else {
            $servicios=$db->query("SELECT s.*,uc.nombre cn,uc.apellido ca,um.nombre mn,um.apellido ma FROM servicio s JOIN usuario uc ON s.cliente_id=uc.id JOIN perfil_maid pm ON s.maid_id=pm.id JOIN usuario um ON pm.usuario_id=um.id ORDER BY s.fecha DESC")->fetchAll(); $vista='admin';
        }
        $ok=$_SESSION['ok']??null; $err=$_SESSION['err']??null; unset($_SESSION['ok'],$_SESSION['err']);
        require __DIR__."/../views/{$vista}/servicios.php";
    }

    public function nuevo(): void {
        requireLogin(); requireRole('cliente');
        $maid_id=(int)($_GET['maid_id']??0);
        $db=getDB();
        $s=$db->prepare("SELECT pm.*,u.nombre,u.apellido FROM perfil_maid pm JOIN usuario u ON pm.usuario_id=u.id WHERE pm.id=? AND pm.activo=1");
        $s->execute([$maid_id]); $maid=$s->fetch();
        if (!$maid) { $_SESSION['err']='Maid no encontrada.'; redirect('/maids'); }
        $err=$_SESSION['err']??null; unset($_SESSION['err']);
        require __DIR__.'/../views/client/nuevo_servicio.php';
    }

    public function crear(): void {
        requireLogin(); requireRole('cliente');
        $user=authUser(); $db=getDB();
        $mid=(int)($_POST['maid_id']??0);
        $desc=trim($_POST['descripcion']??'');
        $fecha=$_POST['fecha']??''; $hi=$_POST['hora_inicio']??''; $hf=$_POST['hora_fin']??'';
        $dir=trim($_POST['direccion']??'');
        if (!$mid||!$fecha||!$hi||!$hf||!$dir) { $_SESSION['err']='Completa todos los campos.'; redirect('/servicios/nuevo?maid_id='.$mid); }
        $t=$db->prepare("SELECT tarifa_hora FROM perfil_maid WHERE id=?"); $t->execute([$mid]); $tarifa=(float)$t->fetchColumn();
        $mins=(strtotime($fecha.' '.$hf)-strtotime($fecha.' '.$hi))/60;
        $precio=max(0,round($tarifa*($mins/60),2));
        $db->prepare("INSERT INTO servicio (cliente_id,maid_id,descripcion,fecha,hora_inicio,hora_fin,direccion,estado,precio_total) VALUES (?,?,?,?,?,?,?,'pendiente',?)")
           ->execute([$user['id'],$mid,$desc,$fecha,$hi,$hf,$dir,$precio]);
        $sid=(int)$db->lastInsertId();
        // Notificación interna a la maid
        $pm=$db->prepare("SELECT pm.usuario_id,u.nombre,u.apellido FROM perfil_maid pm JOIN usuario u ON pm.usuario_id=u.id WHERE pm.id=?");
        $pm->execute([$mid]); $maidUser=$pm->fetch();
        if ($maidUser) {
            $db->prepare("INSERT INTO notificacion (usuario_id,titulo,mensaje,tipo) VALUES (?,?,?,'servicio')")
               ->execute([$maidUser['usuario_id'],'Nuevo trabajo disponible',"{$user['nombre']} {$user['apellido']} solicitó tus servicios para el $fecha."]);
            // Disparar n8n
            triggerN8n('nuevo_servicio',['maid'=>$maidUser['nombre'].' '.$maidUser['apellido'],'cliente'=>$user['nombre'].' '.$user['apellido'],'fecha'=>$fecha,'precio'=>$precio]);
        }
        $_SESSION['ok']='¡Servicio contratado! La Maid confirmará pronto.'; redirect('/servicios');
    }

    public function cambiarEstado(): void {
        requireLogin(); $user=authUser(); $db=getDB();
        $id=(int)($_POST['id']??0); $estado=$_POST['estado']??'';
        if (!in_array($estado,['pendiente','confirmado','en_progreso','completado','cancelado'])) redirect('/servicios');
        $db->prepare("UPDATE servicio SET estado=? WHERE id=?")->execute([$estado,$id]);
        if ($estado==='completado') {
            $db->prepare("CALL sp_generar_factura(?)")->execute([$id]);
            // Notificar al cliente
            $sv=$db->prepare("SELECT s.*,u.email,u.nombre FROM servicio s JOIN usuario u ON s.cliente_id=u.id WHERE s.id=?");
            $sv->execute([$id]); $sv=$sv->fetch();
            if ($sv) {
                $db->prepare("INSERT INTO notificacion (usuario_id,titulo,mensaje,tipo) VALUES (?,?,?,'pago')")
                   ->execute([$sv['cliente_id'],'Servicio completado','Tu servicio del '.$sv['fecha'].' fue completado. Revisa tu factura.']);
                triggerN8n('servicio_completado',['cliente_email'=>$sv['email'],'cliente'=>$sv['nombre'],'fecha'=>$sv['fecha'],'total'=>$sv['precio_total']]);
            }
        }
        $_SESSION['ok']='Estado actualizado.'; redirect('/servicios');
    }
}
