<?php
class Product
{
    private $conn;
    private $table = "products";

    public $id;
    public $name;
    public $quantity;
    public $description;
    public $image;
    public $price;
    public $category;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Fungsi untuk menambahkan produk
    public function addProduct()
    {
        $query = "INSERT INTO " . $this->table . " (name, quantity, description, image, price, category) 
                  VALUES (:name, :quantity, :description, :image, :price, :category)";
        $stmt = $this->conn->prepare($query);

        // Bind parameter
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':quantity', $this->quantity);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':image', $this->image);
        $stmt->bindParam(':price', $this->price);
        $stmt->bindParam(':category', $this->category);

        return $stmt->execute();
    }

    // Fungsi untuk mengedit produk
    public function editProduct()
    {
        $query = "UPDATE " . $this->table . " SET name = :name, quantity = :quantity, 
                  description = :description, image = :image, price = :price, category = :category 
                  WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        // Bind parameter
        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':quantity', $this->quantity);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':image', $this->image);
        $stmt->bindParam(':price', $this->price);
        $stmt->bindParam(':category', $this->category);

        return $stmt->execute();
    }

    // Fungsi untuk menghapus produk
    public function deleteProduct($id)
    {
        // Ambil informasi gambar sebelum dihapus
        $query = "SELECT image FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $product = $stmt->fetch(PDO::FETCH_ASSOC);
            $imagePath = $product['image'];

            // Hapus produk dari database
            $query = "DELETE FROM " . $this->table . " WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            // Hapus file gambar jika ada
            if (file_exists($imagePath)) {
                unlink($imagePath); // Menghapus file dari server
            }
        }
    }

    // Fungsi untuk mendapatkan semua produk
    public function getProducts()
    {
        $query = "SELECT * FROM " . $this->table; // Mengambil semua data produk
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Mengembalikan semua produk sebagai array
    }

    // Fungsi untuk mendapatkan produk berdasarkan ID
    public function getProductById($id)
    {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id"; // Mengambil produk berdasarkan ID
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC); // Mengembalikan produk sebagai array
    }

    // Fungsi untuk mengupload gambar
    public function uploadImage($imageFile)
    {
        $target_dir = "uploads/"; // Folder untuk menyimpan gambar
        $target_file = $target_dir . basename($imageFile['name']);

        if (move_uploaded_file($imageFile['tmp_name'], $target_file)) {
            return $target_file; // Mengembalikan path gambar
        } else {
            return null; // Mengembalikan null jika upload gagal
        }
    }

    // Fungsi untuk memperbarui kuantitas produk
public function updateProductQuantity($productId, $newQuantity)
{
    $query = "UPDATE " . $this->table . " SET quantity = :quantity WHERE id = :id";
    $stmt = $this->conn->prepare($query);
    
    // Bind parameter
    $stmt->bindParam(':quantity', $newQuantity);
    $stmt->bindParam(':id', $productId);

    return $stmt->execute();
}


    // Fungsi untuk mencari produk berdasarkan nama atau kategori
    public function searchProducts($keyword, $category)
    {
        $query = "SELECT * FROM " . $this->table . " WHERE 1=1"; // Awal query
        
        // Jika ada keyword nama produk
        if (!empty($keyword)) {
            $query .= " AND name LIKE :keyword";
        }

        // Jika ada kategori
        if (!empty($category)) {
            $query .= " AND category = :category";
        }

        $stmt = $this->conn->prepare($query);

        // Bind parameter jika diperlukan
        if (!empty($keyword)) {
            $stmt->bindValue(':keyword', '%' . $keyword . '%');
        }
        if (!empty($category)) {
            $stmt->bindValue(':category', $category);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Mengembalikan hasil pencarian
    }

    
}
