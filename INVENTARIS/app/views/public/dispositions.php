<?php
session_start();

// Cek apakah pengguna sudah login dan memiliki hak akses
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php"); // Redirect jika tidak memiliki akses
    exit();
}

include_once __DIR__ . '../../../models/Database.php'; // Jalur yang benar untuk Database.php
include_once __DIR__ . '../../../controllers/DisposalController.php'; // Jalur yang benar untuk DisposalController.php

// Inisialisasi Database dan DisposalController
$database = new Database();
$db = $database->getConnection();
$disposalController = new DisposalController($db); // Menggunakan nama $disposalController untuk konsistensi

// Mendapatkan semua disposisi
$dispositions = $disposalController->getAllDispositions();

// Proses pengembalian produk jika ada data yang dikirimkan
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['return'])) {
    $dispositionId = $_POST['disposition_id'];
    $quantityReturned = $_POST['quantity_returned'];

    // Mengemas data dalam array
    $postData = [
        'disposition_id' => $dispositionId,
        'return_quantity' => $quantityReturned
    ];

    // Panggil fungsi untuk mengembalikan produk
    $returnMessage = $disposalController->processReturnProduct($postData);
    
    // Tampilkan pesan jika perlu
    // Mengalihkan pengguna ke halaman ini untuk refresh
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Disposition History</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="icon" href="../images/box.png" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../fontawesome/css/all.min.css">

    <style>
        body {
            background-color: #2a2a2a; /* Warna latar belakang gelap */
            color: white; /* Semua teks berwarna putih */
            font-family: 'Poppins', sans-serif; /* Font Poppins */
        }
        .header-title {
            text-align: center;
            margin-bottom: 30px;
        }
        .card-custom {
            background-color: #3c3c3c; /* Latar belakang kartu disposisi */
            border: none; /* Hapus border default */
            border-radius: 10px; /* Bulatkan sudut */
            transition: transform 0.2s, box-shadow 0.2s; /* Efek saat hover */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Tambahkan bayangan */
        }
        .card-custom:hover {
            transform: scale(1.05); /* Efek hover */
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3); /* Bayangan lebih besar saat hover */
        }
        .inputformprimary {
            background-color: #4a4a4a; /* Warna hitam tidak terlalu gelap */
            color: white;
            border: none;
            border-radius: 15px;
            padding: 10px;
            width: 70%; /* Lebar input */
        }
        .inputformprimary::placeholder {
            color: #ccc; /* Warna placeholder lebih terang */
        }
        .card-body h5, .card-body p {
            color: white; /* Warna teks di dalam kartu disposisi */
        }
        .return-form {
            display: flex;
            justify-content: center; /* Pusatkan form */
            margin-top: 10px; /* Tambahkan jarak di atas */
        }
        .back-button {
            margin-top: 30px; /* Jarak atas untuk tombol kembali */
        }
        .btn-outline-primary {
            border-color: white; /* Warna border tombol */
            color: white; /* Warna teks tombol */
        }
        .btn-outline-primary:hover {
            background-color: white; /* Latar belakang saat hover */
            color: #2a2a2a; /* Warna teks saat hover */
        }
        .btn-outline-light {
            border-color: white; /* Warna border tombol */
            color: white; /* Warna teks tombol */
        }
        .btn-outline-light:hover {
            background-color: white; /* Latar belakang saat hover */
            color: #2a2a2a; /* Warna teks saat hover */
        }
    </style>
</head>

<body>
    <div class="container mt-4 mb-4 p-4">
        <div class="header-title">
            <h1>DISPOSITION HISTORY</h1>
        </div>
        <div class="row">
            <?php foreach ($dispositions as $disposition): ?>
                <div class="col-md-6 col-lg-4 mb-4"> <!-- Responsif untuk kolom -->
                    <div class="card card-custom shadow mb-3">
                        <div class="card-body">
                            <h5>Product ID: <?= htmlspecialchars($disposition['product_id']); ?></h5>
                            <p>Quantity Disposed: <?= htmlspecialchars($disposition['quantity_disposed']); ?></p>
                            <p>Date Disposed: <?= htmlspecialchars($disposition['date_disposed']); ?></p>
                            <p>Reason: <?= htmlspecialchars($disposition['disposition_reason']); ?></p>
                            <div class="return-form">
                                <form method="post" class="d-flex align-items-center">
                                    <input type="hidden" name="disposition_id" value="<?= htmlspecialchars($disposition['id']); ?>">
                                    <input type="number" name="quantity_returned" placeholder="Return Quantity" required class="inputformprimary p-1">
                                    <button type="submit" name="return" class="btn btn-outline-primary btn-sm">Return</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="text-center back-button">
            <a href="admin.php" class="btn btn-outline-light">Back to Admin</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../fontawesome/js/all.min.js"></script>
</body>
</html>
