<?php
class FacturaController {
    public function index(): void {
        requireLogin(); $user=authUser(); $db=getDB();
        if ($user['rol']==='cliente') {
            $s=$db->prepare("SELECT f.*,s.fecha,um.nombre mn,um.apellido ma FROM factura f JOIN servicio s ON f.servicio_id=s.id JOIN perfil_maid pm ON s.maid_id=pm.id JOIN usuario um ON pm.usuario_id=um.id WHERE s.cliente_id=? ORDER BY f.fecha_emision DESC");
            $s->execute([$user['id']]);
        } elseif ($user['rol']==='maid') {
            $s=$db->prepare("SELECT f.*,s.fecha,uc.nombre cn,uc.apellido ca FROM factura f JOIN servicio s ON f.servicio_id=s.id JOIN perfil_maid pm ON s.maid_id=pm.id JOIN usuario uc ON s.cliente_id=uc.id WHERE pm.usuario_id=? ORDER BY f.fecha_emision DESC");
            $s->execute([$user['id']]);
        } else {
            $s=$db->query("SELECT f.*,s.fecha,uc.nombre cn,uc.apellido ca,um.nombre mn,um.apellido ma FROM factura f JOIN servicio s ON f.servicio_id=s.id JOIN usuario uc ON s.cliente_id=uc.id JOIN perfil_maid pm ON s.maid_id=pm.id JOIN usuario um ON pm.usuario_id=um.id ORDER BY f.fecha_emision DESC");
        }
        $facturas=$s->fetchAll();
        require __DIR__.'/../views/shared/facturas.php';
    }
}
