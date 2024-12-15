<?php
include_once __DIR__ . '/../models/Database.php';
include_once __DIR__ . '/../models/Product.php'; // Menggunakan model Product

class ProductController {
    private $conn;
    private $productModel;

    public function __construct($db) {
        $this->conn = $db;
        $this->productModel = new Product($db); // Inisialisasi model Product
    }

    // Fungsi untuk menambah produk baru
    public function processAddProduct($postData, $fileData) {
        $this->productModel->name = $postData['name'];
        $this->productModel->quantity = $postData['quantity'];
        $this->productModel->description = $postData['description'];
        $this->productModel->price = $postData['price'];
        $this->productModel->category = $postData['category'];

        // Handle upload gambar produk
        $target_file = $this->productModel->uploadImage($fileData['image']);
        $this->productModel->image = $target_file; // Menetapkan path gambar

        // Menambahkan produk ke database
        return $this->productModel->addProduct();
    }

    // Fungsi untuk mengedit produk
    public function processEditProduct($postData, $fileData) {
        $this->productModel->id = $postData['id'];
        $this->productModel->name = $postData['name'];
        $this->productModel->quantity = $postData['quantity'];
        $this->productModel->description = $postData['description'];
        $this->productModel->price = $postData['price'];
        $this->productModel->category = $postData['category'];

        // Cek apakah ada gambar baru yang diunggah
        if (isset($fileData['image']) && $fileData['image']['error'] == UPLOAD_ERR_OK) {
            $target_file = $this->productModel->uploadImage($fileData['image']);
            $this->productModel->image = $target_file; // Tetapkan path gambar baru
        } else {
            // Jika tidak ada gambar baru, tetap gunakan gambar yang ada
            $this->productModel->image = $postData['current_image'];
        }

        // Update produk di database
        return $this->productModel->editProduct();
    }

    // Fungsi untuk menghapus produk
    public function processDeleteProduct($postData) {
        $id = $postData['id'];
        $this->productModel->deleteProduct($id);
    }

    // Fungsi untuk mengambil semua produk
    public function getProducts() {
        return $this->productModel->getProducts();
    }

    // Fungsi untuk mengambil produk berdasarkan ID
    public function getProductById($id) {
        return $this->productModel->getProductById($id);
    }

    // Fungsi untuk mencari produk berdasarkan nama dan kategori
    public function searchProducts($keyword, $category) {
        return $this->productModel->searchProducts($keyword, $category);
    }

    // Format harga menjadi format Rupiah
    public function formatRupiah($amount) {
        return "Rp " . number_format($amount, 2, ',', '.');
    }
}
?>
