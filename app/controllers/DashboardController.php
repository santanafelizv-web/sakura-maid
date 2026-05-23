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
            $proximo = $db->prepare("SELECT s.*,u.nombre mn,u.apellido ma FROM servicio s JOIN perfil_maid pm ON s.maid_id=pm.id JOIN usuario u ON pm.usuario_id=u.id WHERE s.cliente_id=? AND s.fecha >= CURDATE() AND s.estado NOT IN ('cancelado','completado') ORDER BY s.fecha ASC LIMIT 1");
            $proximo->execute([$user['id']]); $proximo_servicio = $proximo->fetch();
            $maid_fav = $db->prepare("SELECT u.nombre mn, u.apellido ma, pm.calificacion_promedio, COUNT(s.id) veces FROM servicio s JOIN perfil_maid pm ON s.maid_id=pm.id JOIN usuario u ON pm.usuario_id=u.id WHERE s.cliente_id=? GROUP BY pm.id ORDER BY veces DESC LIMIT 1");
            $maid_fav->execute([$user['id']]); $maid_favorita = $maid_fav->fetch();
            $gasto_mes = $db->prepare("SELECT COALESCE(SUM(f.total),0) FROM factura f JOIN servicio sv ON f.servicio_id=sv.id WHERE sv.cliente_id=? AND f.estado_pago='pagado' AND MONTH(f.fecha_emision)=MONTH(NOW()) AND YEAR(f.fecha_emision)=YEAR(NOW())");
            $gasto_mes->execute([$user['id']]); $gasto_este_mes = $gasto_mes->fetchColumn();
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
                $proximo = $db->prepare("SELECT s.*,u.nombre cn,u.apellido ca FROM servicio s JOIN usuario u ON s.cliente_id=u.id WHERE s.maid_id=? AND s.fecha >= CURDATE() AND s.estado NOT IN ('cancelado','completado') ORDER BY s.fecha ASC LIMIT 1");
                $proximo->execute([$mid]); $proximo_trabajo = $proximo->fetch();
                $ingresos_mes = $db->prepare("SELECT COALESCE(SUM(f.total),0) FROM factura f JOIN servicio sv ON f.servicio_id=sv.id WHERE sv.maid_id=? AND f.estado_pago='pagado' AND MONTH(f.fecha_emision)=MONTH(NOW())");
                $ingresos_mes->execute([$mid]); $ingresos_este_mes = $ingresos_mes->fetchColumn();
                $resenas_recientes = $db->prepare("SELECT r.*,u.nombre,u.apellido FROM resena r JOIN servicio s ON r.servicio_id=s.id JOIN usuario u ON r.autor_id=u.id WHERE s.maid_id=? ORDER BY r.created_at DESC LIMIT 3");
                $resenas_recientes->execute([$mid]); $resenas = $resenas_recientes->fetchAll();
            }
            $notifs = $db->prepare("SELECT * FROM notificacion WHERE usuario_id=? AND leida=0 ORDER BY created_at DESC LIMIT 5");
            $notifs->execute([$user['id']]); $notificaciones = $notifs->fetchAll();
            require __DIR__.'/../views/maid/dashboard.php';

        } else {
            $stats = $db->query("SELECT 
                (SELECT COUNT(*) FROM usuario WHERE rol='cliente') u,
                (SELECT COUNT(*) FROM servicio) s,
                (SELECT COUNT(*) FROM perfil_maid WHERE activo=1) m,
                (SELECT COALESCE(SUM(total),0) FROM factura WHERE estado_pago='pagado') i,
                (SELECT COUNT(*) FROM servicio WHERE estado='pendiente') pendientes,
                (SELECT COUNT(*) FROM servicio WHERE estado='completado') completados,
                (SELECT COUNT(*) FROM usuario WHERE DATE(created_at) >= DATE_SUB(NOW(), INTERVAL 7 DAY)) nuevos_semana
            ")->fetch();

            $top_maids = $db->query("SELECT u.nombre, u.apellido, pm.calificacion_promedio, pm.tarifa_hora,
                COUNT(s.id) servicios, COALESCE(SUM(s.precio_total),0) ingresos
                FROM servicio s
                JOIN perfil_maid pm ON s.maid_id=pm.id
                JOIN usuario u ON pm.usuario_id=u.id
                WHERE s.estado='completado'
                GROUP BY pm.id, u.nombre, u.apellido, pm.calificacion_promedio, pm.tarifa_hora
                ORDER BY servicios DESC LIMIT 5")->fetchAll();

            $servicios_recientes = $db->query("SELECT s.*, uc.nombre cn, uc.apellido ca, um.nombre mn, um.apellido ma
                FROM servicio s
                JOIN usuario uc ON s.cliente_id=uc.id
                JOIN perfil_maid pm ON s.maid_id=pm.id
                JOIN usuario um ON pm.usuario_id=um.id
                ORDER BY s.created_at DESC LIMIT 6")->fetchAll();

            require __DIR__.'/../views/admin/dashboard.php';
        }
    }
}