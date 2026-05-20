<?php
class ApiController {
    // GET /api/dashboard-data
    public function dashboardData(): void {
        requireLogin();
        header('Content-Type: application/json');
        $db   = getDB();
        $user = authUser();
        $anio = date('Y');

        // Servicios por mes (últimos 6 meses)
        if ($user['rol'] === 'cliente') {
            $sql = "SELECT MONTH(fecha) m, COUNT(*) n FROM servicio WHERE cliente_id={$user['id']} AND YEAR(fecha)=? GROUP BY MONTH(fecha) ORDER BY m";
        } elseif ($user['rol'] === 'maid') {
            $pm = $db->prepare("SELECT id FROM perfil_maid WHERE usuario_id=?");
            $pm->execute([$user['id']]); $mid = $pm->fetchColumn() ?: 0;
            $sql = "SELECT MONTH(fecha) m, COUNT(*) n FROM servicio WHERE maid_id={$mid} AND YEAR(fecha)=? GROUP BY MONTH(fecha) ORDER BY m";
        } else {
            $sql = "SELECT MONTH(fecha) m, COUNT(*) n FROM servicio WHERE YEAR(fecha)=? GROUP BY MONTH(fecha) ORDER BY m";
        }

        $s = $db->prepare($sql); $s->execute([$anio]);
        $rows = $s->fetchAll();
        $meses = ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'];
        $labels = []; $valores = [];
        foreach ($rows as $r) { $labels[] = $meses[$r['m']-1]; $valores[] = (int)$r['n']; }

        // Ingresos por mes (admin/maid)
        $ingresos_labels = []; $ingresos_vals = [];
        if ($user['rol'] !== 'cliente') {
            $si = $db->prepare("SELECT MONTH(f.fecha_emision) m, SUM(f.total) t FROM factura f WHERE YEAR(f.fecha_emision)=? AND f.estado_pago='pagado' GROUP BY MONTH(f.fecha_emision) ORDER BY m");
            $si->execute([$anio]);
            foreach ($si->fetchAll() as $r) { $ingresos_labels[] = $meses[$r['m']-1]; $ingresos_vals[] = (float)$r['t']; }
        }

        // Estado de servicios (donut)
        if ($user['rol'] === 'cliente') {
            $sd = $db->prepare("SELECT estado, COUNT(*) n FROM servicio WHERE cliente_id=? GROUP BY estado");
            $sd->execute([$user['id']]);
        } else {
            $sd = $db->query("SELECT estado, COUNT(*) n FROM servicio GROUP BY estado");
        }
        $donut_labels = []; $donut_vals = [];
        foreach ($sd->fetchAll() as $r) { $donut_labels[] = $r['estado']; $donut_vals[] = (int)$r['n']; }

        echo json_encode(compact('labels','valores','ingresos_labels','ingresos_vals','donut_labels','donut_vals'));
    }

    // GET /api/reporte-data
    public function reporteData(): void {
        requireLogin();
        header('Content-Type: application/json');
        $db = getDB();

        // Top maids
        $top = $db->query("SELECT u.nombre,u.apellido,pm.calificacion_promedio,COUNT(s.id) servicios,COALESCE(SUM(s.precio_total),0) ingresos FROM servicio s JOIN perfil_maid pm ON s.maid_id=pm.id JOIN usuario u ON pm.usuario_id=u.id WHERE s.estado='completado' GROUP BY pm.id ORDER BY servicios DESC LIMIT 5")->fetchAll();

        // Ingresos anuales via SP
        $db->prepare("CALL sp_reporte_ingresos(?)")->execute([date('Y')]);
        $ingresos_mes = $db->query("CALL sp_reporte_ingresos(".date('Y').")")->fetchAll();

        // Resumen general
        $resumen = $db->query("SELECT (SELECT COUNT(*) FROM usuario WHERE rol='cliente') clientes,(SELECT COUNT(*) FROM usuario WHERE rol='maid') maids,(SELECT COUNT(*) FROM servicio WHERE estado='completado') completados,(SELECT COALESCE(SUM(total),0) FROM factura WHERE estado_pago='pagado') ingresos_total")->fetch();

        echo json_encode(compact('top','ingresos_mes','resumen'));
    }
}
