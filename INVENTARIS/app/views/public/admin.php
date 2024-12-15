<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start(); // Mulai sesi jika belum dimulai
}

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

include_once '../../models/Database.php';
include '../../controllers/ProductController.php';
include '../../controllers/UserController.php';
include '../../controllers/DisposalController.php';

$db = new Database();
$conn = $db->getConnection(); // Pastikan koneksi berhasil
$productController = new ProductController($conn);
$userController = new UserController($conn);
$disposalController = new DisposalController($conn);

// Proses tambah, edit, dan hapus produk
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_product'])) {
        $productController->processAddProduct($_POST, $_FILES);
    } elseif (isset($_POST['edit_product'])) {
        $productController->processEditProduct($_POST, $_FILES);
    } elseif (isset($_POST['delete_product'])) {
        $productController->processDeleteProduct($_POST);
    } elseif (isset($_POST['delete_user'])) { // Proses hapus pengguna
        $userController->deleteUser($_POST['id']);
    } elseif (isset($_POST['add_disposition'])) { // Proses tambah disposisi
        $disposalController->processAddDisposition($_POST);
    } elseif (isset($_POST['delete_disposition'])) { // Proses hapus disposisi jika ada
        $disposalController->processDeleteDisposition($_POST);
    }
}

// Mendapatkan daftar produk
$products = $productController->getProducts();
// Mengambil data produk untuk diedit jika ada
$edit_product = isset($_GET['edit_id']) ? $productController->getProductById($_GET['edit_id']) : null;

// Mendapatkan daftar pengguna
$users = $userController->getUsers(); // Ambil daftar pengguna
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="css/styles.css">
    <script src="js/scripts.js" defer></script>

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #1a1a1a; /* Warna latar belakang gelap */
            color: #f0f0f0; /* Warna teks lebih lembut */
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
            padding: 20px;
            background-color: #2c2c2c; /* Latar belakang kontainer */
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        h2, h3 {
            margin: 0;
            color: #f0f0f0; /* Warna teks judul lebih lembut */
        }

        .button-logout, .btn-main {
            background-color: #007bff; /* Warna biru lembut */
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .button-logout:hover, .btn-main:hover {
            background-color: #0056b3; /* Warna biru gelap saat hover */
        }

        h4 {
            color: #e0e0e0; /* Warna teks subjudul */
            border-bottom: 2px solid #444; /* Garis bawah lebih lembut */
            padding-bottom: 10px;
        }

        .product-form, .user-list, .disposition-form {
            margin-top: 20px;
        }

        label {
            display: block;
            font-weight: bold;
            margin-top: 10px;
            color: #d3d3d3; /* Warna label lebih lembut */
        }

        input[type="text"], input[type="number"], input[type="date"], textarea, select {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border-radius: 4px;
            border: 1px solid #555; /* Garis border lebih lembut */
            box-sizing: border-box;
            background-color: #333; /* Warna latar input */
            color: #f0f0f0; /* Warna teks input lebih lembut */
        }

        button {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .btn-delete {
            background-color: #dc3545; /* Warna merah lembut */
            color: white;
        }

        .btn-delete:hover {
            background-color: #c82333; /* Warna merah gelap saat hover */
        }

        .btn-edit {
            background-color: #ffc107; /* Warna kuning lembut */
            color: black;
            padding: 8px 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-align: center;
            transition: background-color 0.3s;
        }

        .btn-edit:hover {
            background-color: #e0a800; /* Warna kuning gelap saat hover */
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table, th, td {
            border: 1px solid #444; /* Garis border tabel lebih lembut */
        }

        th, td {
            padding: 10px;
            text-align: left;
            color: #f0f0f0; /* Warna teks tabel lebih lembut */
        }

        img {
            max-width: 50px;
            height: auto;
            border-radius: 5px;
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

    <header>
            <h2>Selamat datang, <?= htmlspecialchars($_SESSION['username']); ?>!</h2>
            <h3>Dashboard Admin</h3>
            <a class="button-logout" href="logout.php">Logout</a>
        </header>

        <button class="btn-main" onclick="window.location='dispositions.php';">Ke Halaman Disposisi Barang</button>

        <!-- Form Produk -->
        <section class="product-form">
    <h4><?= $edit_product ? 'Edit Produk' : 'Tambah Produk'; ?></h4>
    <form action="" method="POST" enctype="multipart/form-data">
        <?php if ($edit_product): ?>
            <input type="hidden" name="id" value="<?= $edit_product['id']; ?>">
            <input type="hidden" name="current_image" value="<?= $edit_product['image']; ?>">
        <?php endif; ?>

        <label for="name">Nama Produk:</label>
        <input type="text" id="name" name="name" value="<?= $edit_product['name'] ?? ''; ?>" required>

        <label for="quantity">Kuantitas:</label>
        <input type="number" id="quantity" name="quantity" value="<?= $edit_product['quantity'] ?? ''; ?>" required>

        <label for="description">Deskripsi:</label>
        <textarea id="description" name="description" required><?= $edit_product['description'] ?? ''; ?></textarea>

        <label for="price">Harga:</label>
        <input type="number" id="price" name="price" value="<?= $edit_product['price'] ?? ''; ?>" required>

        <label for="category">Kategori:</label>
        <input type="text" id="category" name="category" value="<?= $edit_product['category'] ?? ''; ?>" required>

        <label for="image">Gambar:</label>
        <input type="file" id="image" name="image" <?= $edit_product ? '' : 'required'; ?>>

        <button class="btn-main" type="submit" name="<?= $edit_product ? 'edit_product' : 'add_product'; ?>">
            <?= $edit_product ? 'Simpan Perubahan' : 'Tambah Produk'; ?>
        </button>

        <?php if ($edit_product): ?>
            <button class="btn-back" type="button" onclick="window.location='admin.php';">Kembali ke Tambah Produk</button>
        <?php endif; ?>
    </form>
    </section>

    <!-- Daftar Produk -->
    <section class="product-list">
    <h4>Daftar Produk</h4>
    <table>
        <tr>
            <th>ID</th>
            <th>Nama</th>
            <th>Kuantitas</th>
            <th>Deskripsi</th>
            <th>Gambar</th>
            <th>Harga</th>
            <th>Kategori</th>
            <th>Aksi</th>
        </tr>
        <?php foreach ($products as $product): ?>
            <tr>
                <td><?= $product['id']; ?></td>
                <td><?= $product['name']; ?></td>
                <td><?= $product['quantity']; ?></td>
                <td><?= $product['description']; ?></td>
                <td><img src="<?= $product['image']; ?>" alt="<?= $product['name']; ?>" width="50"></td>
                <td><?= $productController->formatRupiah($product['price']); ?></td>
                <td><?= $product['category']; ?></td>
                <td>
                    <form action="" method="POST" style="display:inline;">
                        <input type="hidden" name="id" value="<?= $product['id']; ?>">
                        <button class="btn-edit" type="button" onclick="window.location='?edit_id=<?= $product['id']; ?>'">Edit</button>
                                <button class="btn-delete" type="submit" name="delete_product" onclick="return confirm('Apakah Anda yakin ingin menghapus produk ini?');">Hapus</button>
                            </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    </section>

    <!-- Daftar Pengguna -->
    <section class="user-list">
    <h4>Daftar Pengguna</h4>
    <table>
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Email</th>
            <th>Aksi</th>
        </tr>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?= $user['id']; ?></td>
                <td><?= $user['username']; ?></td>
                <td><?= $user['email']; ?></td>
                <td>
                <form action="" method="POST" style="display:inline;">
                                <input type="hidden" name="id" value="<?= $user['id']; ?>">
                                <button class="btn-delete" type="submit" name="delete_user" onclick="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?');">Hapus</button>
                            </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    </section>

<!-- Fungsi Disposisi -->
 <section class=".disposition-form">
    <h4>Tambah Disposisi</h4>
    <form action="" method="POST">
        <label for="product_id">ID Produk:</label>
        <input type="number" id="product_id" name="product_id" required>

        <label for="quantity_disposed">Jumlah Disposisi:</label>
        <input type="number" id="quantity_disposed" name="quantity_disposed" required>

        <label for="date_disposed">Tanggal Disposisi:</label>
        <input type="date" id="date_disposed" name="date_disposed" required>

        <label for="disposition_reason">Alasan Disposisi:</label>
        <textarea id="disposition_reason" name="disposition_reason" required></textarea>

        <button class="btn-main" type="submit" name="add_disposition">Tambah Disposisi</button>
    </form>
    </section>

    </div>
</body>

</html>