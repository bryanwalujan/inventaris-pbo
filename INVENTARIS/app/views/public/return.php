<?php
include_once __DIR__ . '/../../models/Database.php';
include_once __DIR__ . '/../../models/Disposition.php';
include_once __DIR__ . '/../../models/Product.php';
include_once __DIR__ . '/../../controllers/DisposalController.php';

// Inisialisasi database dan koneksi
$database = new Database();
$connection = $database->getConnection();

// Inisialisasi DisposalController setelah koneksi berhasil
$disposalController = new DisposalController($connection);

// Mendapatkan semua disposisi untuk pengguna
$dispositions = $disposalController->getAllDispositions();
$message = '';

// Proses pengembalian produk jika ada request POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['return_quantity'])) {
    $message = $disposalController->processReturnProduct($_POST);

    // Redirect untuk mencegah pengiriman ulang data saat halaman di-refresh
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengembalian Produk</title>
    <link rel="stylesheet" href="style.css"> <!-- Gaya CSS -->
    <style>
        body {
            background-color: #1a1a1a;
            color: white;
            font-family: Arial, sans-serif;
        }
        h1 {
            color: #ffffff;
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border: 1px solid #444;
        }
        th {
            background-color: #555;
            color: white;
        }
        .message {
            background-color: #d9534f;
            color: white;
            padding: 10px;
            margin-bottom: 20px;
            text-align: center;
        }
        input[type="number"] {
            width: 100px;
        }
        button {
            padding: 5px 10px;
            background-color: #444;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #555;
        }
        .button-back {
            background-color: #007bff;
            padding: 10px 15px;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        .button-back:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Pengembalian Produk</h1>

        <?php if ($message): ?>
            <div class="message"><?php echo $message; ?></div>
        <?php endif; ?>

        <table>
            <thead>
                <tr>
                    <th>ID Disposisi</th>
                    <th>Produk</th>
                    <th>Kuantitas Didisposisikan</th>
                    <th>Tanggal Didisposisikan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($dispositions as $disposition): ?>
                    <tr>
                        <td><?php echo $disposition['id']; ?></td>
                        <td><?php echo $disposition['product_id']; // Ambil nama produk jika ada ?></td>
                        <td><?php echo $disposition['quantity_disposed']; ?></td>
                        <td><?php echo $disposition['date_disposed']; ?></td>
                        <td>
                            <form method="POST" action="">
                                <input type="hidden" name="disposition_id" value="<?php echo $disposition['id']; ?>">
                                <input type="number" name="return_quantity" min="1" max="<?php echo $disposition['quantity_disposed']; ?>" required>
                                <button type="submit">Kembalikan</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div>
            <a href="user.php" class="button-back">Kembali ke Halaman User</a>
        </div>
    </div>
</body>
</html>
