<?php
include_once __DIR__ . '/../models/Database.php';
include_once __DIR__ . '/../models/User.php'; // Masukkan model User

class UserController {
    private $conn;
    private $userModel;

    public function __construct($db) {
        $this->conn = $db;
        $this->userModel = new User($db); // Inisialisasi model User
    }

    // Mendapatkan semua pengguna
    public function getUsers() {
        return $this->userModel->getAllUsers(); // Memanggil metode dari model User
    }

    // Menghapus pengguna
    public function deleteUser($id): bool {
        return $this->userModel->deleteUserById($id); // Memanggil metode dari model User
    }
}
?>
