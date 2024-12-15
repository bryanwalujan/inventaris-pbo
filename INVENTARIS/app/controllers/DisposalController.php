<?php
include_once __DIR__ . '/../models/Database.php';
include_once __DIR__ . '/../models/Disposition.php';
include_once __DIR__ . '/../models/Product.php'; // Pastikan ada model untuk Produk

class DisposalController {
    private $conn;
    private $dispositionModel;
    private $productModel; // Tambahkan atribut untuk produk

    public function __construct($db) {
        $this->conn = $db;
        $this->dispositionModel = new Disposition($db);
        $this->productModel = new Product($db); // Inisialisasi model produk
    }

    // Fungsi untuk menambah disposisi
    public function processAddDisposition($postData) {
        $this->dispositionModel->product_id = $postData['product_id'];
        $this->dispositionModel->quantity_disposed = $postData['quantity_disposed'];
        $this->dispositionModel->date_disposed = date('Y-m-d H:i:s'); // Menetapkan tanggal disposisi
        $this->dispositionModel->disposition_reason = $postData['disposition_reason'];

        // Ambil produk untuk mendapatkan kuantitas saat ini
        $currentProduct = $this->productModel->getProductById($this->dispositionModel->product_id);

        // Pastikan kuantitas disposisi tidak lebih dari kuantitas yang tersedia
        if ($this->dispositionModel->quantity_disposed > $currentProduct['quantity']) {
            return "Gagal menambahkan disposisi: Kuantitas disposisi melebihi kuantitas yang tersedia.";
        }

        // Buat disposisi
        if ($this->dispositionModel->createDisposition()) {
            // Kurangi kuantitas produk
            $newQuantity = $currentProduct['quantity'] - $this->dispositionModel->quantity_disposed;
            $this->productModel->updateProductQuantity($this->dispositionModel->product_id, $newQuantity);
            return "Disposisi berhasil ditambahkan.";
        } else {
            return "Gagal menambahkan disposisi.";
        }
    }

    // Fungsi untuk mendapatkan semua disposisi
    public function getAllDispositions() {
        $query = "SELECT * FROM dispositions"; // Sesuaikan dengan tabel Anda
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Mengembalikan semua disposisi
    }

    // Fungsi untuk menghapus disposisi
    public function processDeleteDisposition($postData) {
        $id = $postData['id'];
        if ($this->dispositionModel->deleteDisposition($id)) {
            return "Disposisi berhasil dihapus.";
        } else {
            return "Gagal menghapus disposisi.";
        }
    }

    public function processReturnProduct($postData) {
        $dispositionId = $postData['disposition_id'];
        $returnQuantity = $postData['return_quantity'];
    
        // Ambil disposisi berdasarkan ID
        $disposition = $this->dispositionModel->getDispositionById($dispositionId);
    
        // Validasi kuantitas pengembalian
        if ($returnQuantity > $disposition['quantity_disposed']) {
            return "Gagal mengembalikan produk: Kuantitas pengembalian melebihi kuantitas disposisi.";
        }
    
        // Mengupdate kuantitas produk
        $currentProduct = $this->productModel->getProductById($disposition['product_id']);
        $newQuantity = $currentProduct['quantity'] + $returnQuantity;
        $this->productModel->updateProductQuantity($disposition['product_id'], $newQuantity);
    
        // Update disposisi jika perlu
        $newDispositionQuantity = $disposition['quantity_disposed'] - $returnQuantity; // Hitung jumlah baru
        if ($newDispositionQuantity <= 0) {
            $this->dispositionModel->deleteDisposition($dispositionId); // Hapus disposisi jika semua produk telah dikembalikan
        } else {
            // Update disposisi dengan jumlah baru
            $this->dispositionModel->updateDisposition($dispositionId, $newDispositionQuantity);
        }
    
        return "Produk berhasil dikembalikan.";
    }
}
