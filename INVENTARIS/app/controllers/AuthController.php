<?php
include_once __DIR__ . '/../models/Database.php'; // Menggunakan jalur absolut

class AuthController {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function login() {
        // Cek metode permintaan
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = trim($_POST['username']); // Menghapus spasi
            $password = $_POST['password'];

            // Cek kredensial di database
            $query = "SELECT * FROM users WHERE username = :username";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':username', $username);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                // Ambil data pengguna
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                // Debugging: tampilkan informasi pengguna
                error_log(print_r($user, true)); // Menampilkan informasi pengguna
                
                // Verifikasi password dengan hash dari database
                if (password_verify($password, $user['password'])) {
                    // Simpan informasi pengguna di session
                    $_SESSION['user_id'] = $user['id']; // Menyimpan ID pengguna
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['role'] = $user['role'];

                    // Redirect berdasarkan role
                    if ($user['role'] === 'admin') {
                        header("Location: admin.php");
                    } else {
                        header("Location: user.php");
                    }
                    exit();
                } else {
                    error_log("Password tidak cocok untuk user: $username"); // Tambahkan log
                    return "Login gagal. Silakan coba lagi."; // Mengembalikan pesan error
                }
            } else {
                error_log("Pengguna tidak ditemukan: $username"); // Tambahkan log
                return "Login gagal. Silakan coba lagi."; // Mengembalikan pesan error
            }
        }
        
        return null; // Kembali null jika tidak ada error
    }
}
?>
