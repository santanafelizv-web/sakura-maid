<?php
class PerfilController {
    public function index(): void {
        requireLogin();
        $ok=$_SESSION['ok']??null; $err=$_SESSION['err']??null; unset($_SESSION['ok'],$_SESSION['err']);
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
}
