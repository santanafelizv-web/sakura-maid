<?php
class MaidController {
    public function index(): void {
        requireLogin(); requireRole('cliente');
        $db=getDB(); $q=trim($_GET['q']??'');
        $sql="SELECT pm.*,u.nombre,u.apellido FROM perfil_maid pm JOIN usuario u ON pm.usuario_id=u.id WHERE pm.activo=1 AND pm.disponibilidad='disponible'";
        $params=[];
        if ($q) { 
            $sql.=" AND (u.nombre LIKE ? OR u.apellido LIKE ?)"; 
            $like="$q%"; 
            $params=[$like,$like]; 
        }
        $sql.=" ORDER BY pm.calificacion_promedio DESC";
        $s=$db->prepare($sql); $s->execute($params); $maids=$s->fetchAll();
        require __DIR__.'/../views/client/maids.php';
    }

    public function adminMailds(): void {
        requireLogin(); requireRole('admin');
        $db=getDB();
        $s=$db->query("SELECT pm.*,u.nombre,u.apellido,u.email FROM perfil_maid pm JOIN usuario u ON pm.usuario_id=u.id ORDER BY u.nombre");
        $maids=$s->fetchAll();
        $ok=$_SESSION['ok']??null; $err=$_SESSION['err']??null; unset($_SESSION['ok'],$_SESSION['err']);
        require __DIR__.'/../views/admin/maids.php';
    }

    public function editarTarifa(): void {
        requireLogin(); requireRole('admin');
        $db=getDB();
        $id=(int)($_POST['maid_id']??0);
        $tarifa=(float)($_POST['tarifa_hora']??0);
        if ($id && $tarifa >= 0) {
            $db->prepare("UPDATE perfil_maid SET tarifa_hora=? WHERE id=?")->execute([$tarifa,$id]);
            $_SESSION['ok']='Tarifa actualizada correctamente.';
        } else {
            $_SESSION['err']='Datos inválidos.';
        }
        redirect('/admin/maids');
    }

    public function perfil(): void {
        requireLogin(); requireRole('maid'); $user=authUser(); $db=getDB();
        $s=$db->prepare("SELECT * FROM perfil_maid WHERE usuario_id=?"); $s->execute([$user['id']]); $perfil=$s->fetch();
        $ok=$_SESSION['ok']??null; $err=$_SESSION['err']??null; unset($_SESSION['ok'],$_SESSION['err']);
        require __DIR__.'/../views/maid/perfil.php';
    }

    public function guardarPerfil(): void {
        requireLogin(); requireRole('maid'); $user=authUser(); $db=getDB();
        $desc=trim($_POST['descripcion']??'');
        $disp=in_array($_POST['disponibilidad']??'',['disponible','ocupado','inactivo'])?$_POST['disponibilidad']:'disponible';
        $db->prepare("UPDATE perfil_maid SET descripcion=?,disponibilidad=? WHERE usuario_id=?")->execute([$desc,$disp,$user['id']]);
        $_SESSION['ok']='Perfil actualizado.'; redirect('/maids/perfil');
    }
}