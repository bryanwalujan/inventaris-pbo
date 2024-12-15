<?php
include_once __DIR__ . '/../../models/Database.php';
include_once __DIR__ . '/../../models/Disposition.php';
include_once __DIR__ . '/../../models/Product.php';
include_once __DIR__ . '/../../controllers/DisposalController.php';
include_once __DIR__ . '/../../controllers/ProductController.php'; // Tambahkan ini

// Inisialisasi database dan controller
$db = (new Database())->getConnection();
$disposalController = new DisposalController($db);
$productController = new ProductController($db); // Inisialisasi ProductController

// Mendapatkan semua produk untuk ditampilkan
$products = $productController->getProducts();
$message = '';

// Proses pencarian jika ada data POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Proses disposisi
    if (isset($_POST['disposition'])) {
        $message = $disposalController->processAddDisposition($_POST);
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }

    // Proses pencarian produk
    $keyword = $_POST['keyword'] ?? '';
    $category = $_POST['category'] ?? '';

    // Mencari produk berdasarkan keyword dan kategori
    $products = $productController->searchProducts($keyword, $category);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Disposisi Produk - User</title>
    <style>
        body {
            background-color: #1a1a1a;
            color: white;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: auto;
            padding: 20px;
            background-color: #333;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
        }
        h1, h2 {
            color: #f0f0f0;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input[type="text"],
        input[type="number"],
        select,
        textarea {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 5px;
            background-color: #444;
            color: white;
        }
        input[type="text"]:focus,
        input[type="number"]:focus,
        select:focus,
        textarea:focus {
            background-color: #555;
            outline: none;
        }
        button {
            background-color: #007BFF;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #0056b3;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border: 1px solid #555;
        }
        th {
            background-color: #444;
        }
        tr:nth-child(even) {
            background-color: #444;
        }
        .alert {
            padding: 10px;
            background-color: #ff4d4d;
            color: white;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .button-back {
            display: inline-block;
            background-color: #6c757d;
            color: white;
            padding: 10px 15px;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s;
        }
        .button-back:hover {
            background-color: #5a6268;
        }

        .website-name {
    position: fixed; /* Agar tetap di posisi yang sama saat scroll */
    top: 10px;      /* Jarak dari bagian atas */
    left: 10px;     /* Jarak dari bagian kiri */
    background-color: rgba(0, 0, 0, 0.7); /* Latar belakang transparan */
    color: white;   /* Warna teks */
    padding: 5px 10px; /* Jarak dalam kotak */
    border-radius: 5px; /* Sudut membulat */
    font-size: 16px; /* Ukuran font */
    z-index: 1000; /* Agar tetap di atas elemen lainnya */
}

    </style>
</head>
<body>
    <div class="container">
    <div class="website-name">Katalog Pakaian Instansi</div>

        <h1>Disposisi Produk</h1>
        
        <?php if ($message): ?>
            <div class="alert">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <!-- Form pencarian produk -->
        <form method="POST" action="">
            <div class="form-group">
                <label for="keyword">Cari berdasarkan Nama:</label>
                <input type="text" name="keyword" id="keyword" placeholder="Masukkan nama produk...">
            </div>

            <div class="form-group">
                <label for="category">Pilih Kategori:</label>
                <select name="category" id="category">
                    <option value="">-- Pilih Kategori --</option>
                    <option value="baju">Baju</option>
                    <option value="celana">Celana</option>
                    <option value="sepatu">Sepatu</option>
                    <option value="topi">Topi</option>
                    <option value="kaos kaki">Kaos Kaki</option>
                    <option value="tas">Tas</option>
                    <option value="kalung">Kalung</option>
                </select>
            </div>

            <button type="submit" name="search">Cari</button>
        </form>

        <!-- Tabel untuk menampilkan semua produk -->
        <h2>Daftar Produk Tersedia</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Produk</th>
                    <th>Kategori</th>
                    <th>Kuantitas Tersedia</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($product['id']); ?></td>
                        <td><?php echo htmlspecialchars($product['name']); ?></td>
                        <td><?php echo htmlspecialchars($product['category']); ?></td>
                        <td><?php echo htmlspecialchars($product['quantity']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Form disposisi produk -->
        <form method="POST" action="">
            <div class="form-group">
                <label for="product_id">Pilih Produk untuk Disposisi:</label>
                <select name="product_id" id="product_id" required>
                    <option value="">-- Pilih Produk --</option>
                    <?php foreach ($products as $product): ?>
                        <option value="<?php echo $product['id']; ?>">
                            <?php echo htmlspecialchars($product['name']); ?> (Tersedia: <?php echo $product['quantity']; ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="quantity_disposed">Kuantitas Disposisi:</label>
                <input type="number" name="quantity_disposed" id="quantity_disposed" required min="1">
            </div>

            <div class="form-group">
                <label for="disposition_reason">Alasan Disposisi:</label>
                <textarea name="disposition_reason" id="disposition_reason" required></textarea>
            </div>

            <button type="submit" name="disposition">Tambah Disposisi</button>
        </form>
        
        <div>
            <a href="return.php" class="button-back">Ke Halaman Riwayat Peminjaman</a>
        </div>
        <div>
            <a href="logout.php" class="button-back">Log out</a>
        </div>
    </div>
</body>
</html>
