<?php

class UserModel extends Model
{
    public function getAdmin()
    {
        $stmt = $this->db->query("SELECT * FROM users WHERE role = 'admin' LIMIT 1");
        return $stmt->fetch();
    }

    public function findByUsername($username)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = ? LIMIT 1");
        $stmt->execute([$username]);
        return $stmt->fetch();
    }

    public function getAll()
    {
        $stmt = $this->db->query("SELECT id, username, display_name, role, created_at FROM users ORDER BY created_at DESC");
        return $stmt->fetchAll();
    }

    public function find($id)
    {
        $stmt = $this->db->prepare("SELECT id, username, display_name, role, created_at FROM users WHERE id = ? LIMIT 1");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create(array $data)
    {
        $stmt = $this->db->prepare(
            "INSERT INTO users (username, password_hash, display_name, role) VALUES (?, ?, ?, ?)"
        );
        $stmt->execute([
            $data['username'],
            password_hash($data['password'], PASSWORD_BCRYPT),
            $data['display_name'],
            $data['role'] ?? 'admin',
        ]);
        return $this->db->lastInsertId();
    }

    public function update($id, array $data)
    {
        if (!empty($data['password'])) {
            $stmt = $this->db->prepare(
                "UPDATE users SET username = ?, display_name = ?, role = ?, password_hash = ? WHERE id = ?"
            );
            $stmt->execute([
                $data['username'],
                $data['display_name'],
                $data['role'] ?? 'admin',
                password_hash($data['password'], PASSWORD_BCRYPT),
                $id,
            ]);
        } else {
            $stmt = $this->db->prepare(
                "UPDATE users SET username = ?, display_name = ?, role = ? WHERE id = ?"
            );
            $stmt->execute([
                $data['username'],
                $data['display_name'],
                $data['role'] ?? 'admin',
                $id,
            ]);
        }
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$id]);
    }

    public function countAll()
    {
        return $this->db->query("SELECT COUNT(*) FROM users")->fetchColumn();
    }
}
