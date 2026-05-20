<?php
class DashboardController {
    public function index(): void {
        requireLogin();
        $user = authUser(); $db = getDB();

        if ($user['rol'] === 'cliente') {
            $s = $db->prepare("SELECT COUNT(*) t, SUM(estado='completado') comp, SUM(estado='pendiente') pend FROM servicio WHERE cliente_id=?");
            $s->execute([$user['id']]); $stats = $s->fetch();
            $g = $db->prepare("SELECT COALESCE(SUM(f.total),0) g FROM factura f JOIN servicio sv ON f.servicio_id=sv.id WHERE sv.cliente_id=? AND f.estado_pago='pagado'");
            $g->execute([$user['id']]); $gasto = $g->fetchColumn();
            $r = $db->prepare("SELECT s.*,u.nombre mn,u.apellido ma FROM servicio s JOIN perfil_maid pm ON s.maid_id=pm.id JOIN usuario u ON pm.usuario_id=u.id WHERE s.cliente_id=? ORDER BY s.created_at DESC LIMIT 5");
            $r->execute([$user['id']]); $recientes = $r->fetchAll();
            $notifs = $db->prepare("SELECT * FROM notificacion WHERE usuario_id=? AND leida=0 ORDER BY created_at DESC LIMIT 5");
            $notifs->execute([$user['id']]); $notificaciones = $notifs->fetchAll();
            require __DIR__.'/../views/client/dashboard.php';

        } elseif ($user['rol'] === 'maid') {
            $pm = $db->prepare("SELECT * FROM perfil_maid WHERE usuario_id=?");
            $pm->execute([$user['id']]); $perfil = $pm->fetch();
            $mid = $perfil['id'] ?? 0;
            $stats = ['t'=>0,'comp'=>0,'pend'=>0]; $ingresos=0; $recientes=[];
            if ($mid) {
                $s = $db->prepare("SELECT COUNT(*) t,SUM(estado='completado') comp,SUM(estado='pendiente') pend FROM servicio WHERE maid_id=?");
                $s->execute([$mid]); $stats = $s->fetch();
                $g = $db->prepare("SELECT COALESCE(SUM(f.total),0) FROM factura f JOIN servicio sv ON f.servicio_id=sv.id WHERE sv.maid_id=? AND f.estado_pago='pagado'");
                $g->execute([$mid]); $ingresos = $g->fetchColumn();
                $r = $db->prepare("SELECT s.*,u.nombre cn,u.apellido ca FROM servicio s JOIN usuario u ON s.cliente_id=u.id WHERE s.maid_id=? ORDER BY s.created_at DESC LIMIT 5");
                $r->execute([$mid]); $recientes = $r->fetchAll();
            }
            $notifs = $db->prepare("SELECT * FROM notificacion WHERE usuario_id=? AND leida=0 ORDER BY created_at DESC LIMIT 5");
            $notifs->execute([$user['id']]); $notificaciones = $notifs->fetchAll();
            require __DIR__.'/../views/maid/dashboard.php';

        } else {
            $stats = $db->query("SELECT (SELECT COUNT(*) FROM usuario) u,(SELECT COUNT(*) FROM servicio) s,(SELECT COUNT(*) FROM perfil_maid WHERE activo=1) m,(SELECT COALESCE(SUM(total),0) FROM factura WHERE estado_pago='pagado') i")->fetch();
            require __DIR__.'/../views/admin/dashboard.php';
        }
    }
}
