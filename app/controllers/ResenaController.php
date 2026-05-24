<?php
class ResenaController {
    public function index(): void {
        requireLogin(); requireRole('cliente');
        $user = authUser(); $db = getDB();
        $r = $db->prepare("SELECT r.*,s.fecha,u.nombre mn,u.apellido ma FROM resena r JOIN servicio s ON r.servicio_id=s.id JOIN perfil_maid pm ON s.maid_id=pm.id JOIN usuario u ON pm.usuario_id=u.id WHERE r.autor_id=? ORDER BY r.created_at DESC");
        $r->execute([$user['id']]); $resenas = $r->fetchAll();
        require __DIR__.'/../views/client/resenas.php';
    }

    public function crear(): void {
        requireLogin(); requireRole('cliente');
        $user = authUser(); $db = getDB();
        $sid = (int)($_GET['servicio_id'] ?? 0);
        $s = $db->prepare("SELECT s.*,u.nombre mn,u.apellido ma FROM servicio s JOIN perfil_maid pm ON s.maid_id=pm.id JOIN usuario u ON pm.usuario_id=u.id WHERE s.id=? AND s.cliente_id=? AND s.estado='completado'");
        $s->execute([$sid, $user['id']]); $servicio = $s->fetch();
        if (!$servicio) { $_SESSION['err']='Servicio no válido.'; redirect('/servicios'); }
        $ya = $db->prepare("SELECT id FROM resena WHERE servicio_id=?");
        $ya->execute([$sid]);
        if ($ya->fetch()) { $_SESSION['err']='Ya dejaste una reseña para este servicio.'; redirect('/resenas'); }
        $err = $_SESSION['err'] ?? null; unset($_SESSION['err']);
        require __DIR__.'/../views/client/resena.php';
    }

    public function guardar(): void {
        requireLogin(); requireRole('cliente');
        $user = authUser(); $db = getDB();
        $sid = (int)($_POST['servicio_id'] ?? 0);
        $cal = (int)($_POST['calificacion'] ?? 0);
        $com = trim($_POST['comentario'] ?? '');
        if (!$sid || $cal < 1 || $cal > 5) { $_SESSION['err']='Datos inválidos.'; redirect('/servicios'); }
        $s = $db->prepare("SELECT * FROM servicio WHERE id=? AND cliente_id=? AND estado='completado'");
        $s->execute([$sid, $user['id']]);
        if (!$s->fetch()) { redirect('/servicios'); }
        $ya = $db->prepare("SELECT id FROM resena WHERE servicio_id=?");
        $ya->execute([$sid]);
        if ($ya->fetch()) { $_SESSION['err']='Ya dejaste una reseña.'; redirect('/resenas'); }
        $db->prepare("INSERT INTO resena (servicio_id,autor_id,calificacion,comentario) VALUES (?,?,?,?)")
           ->execute([$sid, $user['id'], $cal, $com]);
        $mid = $db->prepare("SELECT maid_id FROM servicio WHERE id=?");
        $mid->execute([$sid]); $maid_id = $mid->fetchColumn();
        if ($maid_id) {
            $db->prepare("CALL sp_actualizar_calificacion(?)")->execute([$maid_id]);
        }
        $_SESSION['ok'] = '¡Gracias por tu reseña!'; redirect('/resenas');
    }
}