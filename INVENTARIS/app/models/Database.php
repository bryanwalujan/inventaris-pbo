<?php
class Database {
    private $host = 'localhost';
    private $db_name = 'inventory_db';
    private $username = 'root';
    private $password = '';
    private $conn;

    // Deklarasi tipe pengembalian : PDO
    public function getConnection(): ?PDO {
        $this->conn = null;

        try {
            // Membangun koneksi PDO dengan database
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            // Mengatur mode error agar Exception dilemparkan jika terjadi kesalahan
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $exception) {
            // Menampilkan pesan error jika koneksi gagal
            echo "Connection error: " . $exception->getMessage();
        }

        // Mengembalikan objek PDO atau null jika gagal
        return $this->conn;
    }
}
?>
