<?php
class Usuario {
    private PDO $db;
    public function __construct() { $this->db = getDB(); }

    public function findByEmail(string $email): ?array {
        $s = $this->db->prepare("SELECT * FROM usuario WHERE email=? LIMIT 1");
        $s->execute([$email]); return $s->fetch() ?: null;
    }
    public function findById(int $id): ?array {
        $s = $this->db->prepare("SELECT id,nombre,apellido,email,telefono,rol,avatar_seed FROM usuario WHERE id=? LIMIT 1");
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
        $fields = []; $values = [];
        foreach (['nombre','apellido','telefono','avatar_seed'] as $f) {
            if (array_key_exists($f, $d)) { $fields[] = "$f=?"; $values[] = $d[$f]; }
        }
        if (empty($fields)) return;
        $values[] = $id;
        $this->db->prepare("UPDATE usuario SET ".implode(',',$fields)." WHERE id=?")->execute($values);
    }
    public function verify(string $pass, string $hash): bool { return password_verify($pass,$hash); }
}
