<?php
class Disposition {
    private $conn;
    private $table = "dispositions";

    public $id;
    public $product_id;
    public $quantity_disposed;
    public $date_disposed;
    public $disposition_reason;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Fungsi untuk menambah disposisi
    public function createDisposition() {
        $query = "INSERT INTO " . $this->table . " (product_id, quantity_disposed, date_disposed, disposition_reason) 
                  VALUES (:product_id, :quantity_disposed, :date_disposed, :disposition_reason)";
        $stmt = $this->conn->prepare($query);

        // Bind parameter
        $stmt->bindParam(':product_id', $this->product_id);
        $stmt->bindParam(':quantity_disposed', $this->quantity_disposed);
        $stmt->bindParam(':date_disposed', $this->date_disposed);
        $stmt->bindParam(':disposition_reason', $this->disposition_reason);

        return $stmt->execute();
    }

    // Fungsi untuk mendapatkan semua disposisi
    public function getAllDispositions() {
        $query = "SELECT * FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Fungsi untuk mendapatkan disposisi berdasarkan ID
    public function getDispositionById($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Fungsi untuk menghapus disposisi
    public function deleteDisposition($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // Fungsi untuk memperbarui disposisi
    public function updateDisposition($id, $quantity_disposed) {
        $query = "UPDATE " . $this->table . " SET quantity_disposed = :quantity_disposed WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':quantity_disposed', $quantity_disposed);
        $stmt->bindParam(':id', $id);
        return $stmt->execute(); // Mengembalikan status eksekusi
    }
}
?>
