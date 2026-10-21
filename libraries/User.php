<?php

class User
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function register($data)
    {
        $this->db->query('SELECT id FROM users WHERE username = :username OR email = :email LIMIT 1');
        $this->db->bind(':username', $data['username']);
        $this->db->bind(':email', $data['email']);
        if ($this->db->single()) return false;

        $this->db->query('INSERT INTO users(name, email, avatar, username, password, about)
                          VALUES (:name, :email, :avatar, :username, :password, :about)');
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':avatar', $data['avatar']);
        $this->db->bind(':username', $data['username']);
        $this->db->bind(':password', password_hash($data['password'], PASSWORD_DEFAULT));
        $this->db->bind(':about', $data['about']);
        return $this->db->execute();
    }

    public function uploadAvatar()
    {
        if (empty($_FILES['avatar']) || $_FILES['avatar']['error'] === UPLOAD_ERR_NO_FILE) return false;
        if ($_FILES['avatar']['error'] !== UPLOAD_ERR_OK || $_FILES['avatar']['size'] > 5 * 1024 * 1024) return false;

        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($_FILES['avatar']['tmp_name']);
        $allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/gif' => 'gif'];
        if (!isset($allowed[$mime])) return false;

        $filename = bin2hex(random_bytes(16)) . '.' . $allowed[$mime];
        $destination = __DIR__ . '/../images/avatars/' . $filename;
        if (!move_uploaded_file($_FILES['avatar']['tmp_name'], $destination)) return false;

        $_FILES['avatar']['safe_name'] = $filename;
        return true;
    }

    public function login($username, $password)
    {
        $this->db->query('SELECT * FROM users WHERE username = :username LIMIT 1');
        $this->db->bind(':username', trim($username));
        $row = $this->db->single();
        if (!$row) return false;

        $stored = $row['password'];
        $valid = password_verify($password, $stored);
        if (!$valid && hash_equals((string)$stored, md5($password))) {
            $this->db->query('UPDATE users SET password = :password WHERE id = :id');
            $this->db->bind(':password', password_hash($password, PASSWORD_DEFAULT));
            $this->db->bind(':id', $row['id']);
            $this->db->execute();
            $valid = true;
        }

        if (!$valid) return false;
        $this->setUserData($row);
        return true;
    }

    public function logout()
    {
        unset($_SESSION['is_logged_in'], $_SESSION['user_id'], $_SESSION['username'], $_SESSION['name']);
        return true;
    }

    private function setUserData($row)
    {
        $_SESSION['is_logged_in'] = true;
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['username'] = $row['username'];
        $_SESSION['name'] = $row['name'];
    }

    public function getTotalUsers()
    {
        $this->db->query('SELECT COUNT(*) AS total FROM users');
        $row = $this->db->single();
        return (int)$row['total'];
    }
}
