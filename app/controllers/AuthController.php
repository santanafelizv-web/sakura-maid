<?php
class AuthController {
    private Usuario $m;
    public function __construct() { $this->m = new Usuario(); }

    public function loginForm(): void {
        if (authUser()) redirect('/dashboard');
        $error    = $_SESSION['err'] ?? null; unset($_SESSION['err']);
        $success  = $_SESSION['ok']  ?? null; unset($_SESSION['ok']);
        $old      = $_SESSION['old'] ?? [];   unset($_SESSION['old']);
        $mode     = 'login';
        require __DIR__.'/../views/auth/auth.php';
    }

    public function login(): void {
        $email = trim($_POST['email'] ?? '');
        $pass  = $_POST['password'] ?? '';
        if (!$email || !$pass) { $_SESSION['err']='Completa todos los campos.'; redirect('/login'); }
        $user = $this->m->findByEmail($email);
        if (!$user || !$this->m->verify($pass, $user['password_hash'])) {
            $_SESSION['err']='Correo o contraseña incorrectos.'; redirect('/login');
        }
        $_SESSION['user'] = ['id'=>$user['id'],'nombre'=>$user['nombre'],'apellido'=>$user['apellido'],'email'=>$user['email'],'rol'=>$user['rol']];
        redirect('/dashboard');
    }

    public function registerForm(): void {
        if (authUser()) redirect('/dashboard');
        $error   = $_SESSION['err'] ?? null; unset($_SESSION['err']);
        $success = $_SESSION['ok']  ?? null; unset($_SESSION['ok']);
        $old     = $_SESSION['old'] ?? [];   unset($_SESSION['old']);
        $mode    = 'register';
        require __DIR__.'/../views/auth/auth.php';
    }

    public function register(): void {
        $d = [
            'nombre'   => trim($_POST['nombre']   ?? ''),
            'apellido' => trim($_POST['apellido'] ?? ''),
            'email'    => trim($_POST['email']    ?? ''),
            'telefono' => trim($_POST['telefono'] ?? ''),
            'password' => $_POST['password'] ?? '',
            'confirm'  => $_POST['confirm']  ?? '',
            'rol'      => in_array($_POST['rol']??'', ['cliente','maid']) ? $_POST['rol'] : 'cliente',
        ];
        $_SESSION['old'] = $d;
        if (!$d['nombre']||!$d['apellido']||!$d['email']||!$d['password']) { $_SESSION['err']='Todos los campos son requeridos.'; redirect('/registro'); }
        if (!filter_var($d['email'],FILTER_VALIDATE_EMAIL))                  { $_SESSION['err']='Correo inválido.'; redirect('/registro'); }
        if (strlen($d['password']) < 8)                                      { $_SESSION['err']='La contraseña debe tener al menos 8 caracteres.'; redirect('/registro'); }
        if ($d['password'] !== $d['confirm'])                                { $_SESSION['err']='Las contraseñas no coinciden.'; redirect('/registro'); }
        if ($this->m->emailExists($d['email']))                              { $_SESSION['err']='Este correo ya está registrado.'; redirect('/registro'); }

        $id = $this->m->create($d);
        if ($d['rol'] === 'maid') {
            getDB()->prepare("INSERT INTO perfil_maid (usuario_id,tarifa_hora) VALUES (?,0)")->execute([$id]);
        }
        unset($_SESSION['old']);
        $_SESSION['ok'] = '¡Cuenta creada! Inicia sesión.';
        redirect('/login');
    }

    public function logout(): void { session_destroy(); redirect('/login'); }
}
