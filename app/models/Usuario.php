<?php
class Usuario {
    private PDO $db;
    public function __construct() { $this->db = getDB(); }

    public function findByEmail(string $email): ?array {
        $s = $this->db->prepare("SELECT * FROM usuario WHERE email=? LIMIT 1");
        $s->execute([$email]); return $s->fetch() ?: null;
    }
    public function findById(int $id): ?array {
        $s = $this->db->prepare("SELECT id,nombre,apellido,email,telefono,rol FROM usuario WHERE id=? LIMIT 1");
        $s->execute([$id]); return $s->fetch() ?: null;
    }
    public function create(array $d): int {
        $s = $this->db->prepare("INSERT INTO usuario (nombre,apellido,email,password_hash,telefono,rol) VALUES (?,?,?,?,?,?)");
        $s->execute([$d['nombre'],$d['apellido'],$d['email'],password_hash($d['password'],PASSWORD_BCRYPT),$d['telefono']??null,$d['rol']??'cliente']);
        return (int)$this->db->lastInsertId();
    }
    public function emailExists(string $email): bool {
        $s = $this->db->prepare("SELECT COUNT(*) FROM usuario WHERE email=?");
        $s->execute([$email]); return (int)$s->fetchColumn() > 0;
    }
    public function update(int $id, array $d): void {
        $this->db->prepare("UPDATE usuario SET nombre=?,apellido=?,telefono=? WHERE id=?")->execute([$d['nombre'],$d['apellido'],$d['telefono']??null,$id]);
    }
    public function verify(string $pass, string $hash): bool { return password_verify($pass,$hash); }
}
