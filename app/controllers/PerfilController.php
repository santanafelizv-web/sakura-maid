<?php
class PerfilController {
    public function index(): void {
        requireLogin();
        $ok=$_SESSION['ok']??null; $err=$_SESSION['err']??null; unset($_SESSION['ok'],$_SESSION['err']);
        $user=authUser();
        $palabras = ['luna','sol','estrella','nube','flor','mar','bosque','rio','nieve','fuego',
            'gato','pajaro','mariposa','delfin','leon','tigre','oso','lobo','ciervo','zorro',
            'rubi','perla','esmeralda','zafiro','topacio','ambar','coral','jade','cuarzo','onix',
            'otono','primavera','verano','invierno','lluvia','arcoiris','trueno','viento','niebla','hielo',
            'rosa','lirio','jazmin','violeta','clavel','tulipan','orquidea','girasol','lavanda','magnolia',
            'miel','canela','vainilla','almendra','caramelo','chocolate','coco','limon','menta','jengibre'];
        $seeds = [];
        for ($i = 0; $i < count($palabras); $i += 2) {
            if (isset($palabras[$i+1])) $seeds[] = $palabras[$i].'_'.$palabras[$i+1];
        }
        $seeds = array_slice($seeds, 0, 30);
        require __DIR__.'/../views/shared/perfil.php';
    }
    public function actualizar(): void {
        requireLogin(); $user=authUser();
        $n=trim($_POST['nombre']??''); $a=trim($_POST['apellido']??''); $t=trim($_POST['telefono']??'');
        if (!$n||!$a) { $_SESSION['err']='Nombre y apellido requeridos.'; redirect('/perfil'); }
        (new Usuario())->update($user['id'],['nombre'=>$n,'apellido'=>$a,'telefono'=>$t]);
        $_SESSION['user']['nombre']=$n; $_SESSION['user']['apellido']=$a;
        $_SESSION['ok']='Perfil actualizado.'; redirect('/perfil');
    }
    public function actualizarAvatar(): void {
        requireLogin(); $user=authUser();
        header('Content-Type: application/json');
        $seed = trim($_POST['seed']??'');
        if (!preg_match('/^[a-zA-Z0-9_]{1,50}$/', $seed)) {
            echo json_encode(['ok'=>false,'error'=>'Seed inválido.']); exit;
        }
        (new Usuario())->update($user['id'],['avatar_seed'=>$seed]);
        $_SESSION['user']['avatar_seed']=$seed;
        echo json_encode(['ok'=>true,'seed'=>$seed]); exit;
    }
}
