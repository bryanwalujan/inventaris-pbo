<?php
session_start();
include_once '../../models/Database.php'; // Sertakan file koneksi database
include_once '../../controllers/AuthController.php'; // Sertakan controller

// Buat instance Database untuk mendapatkan koneksi
$database = new Database();
$conn = $database->getConnection();

// Buat instance AuthController dengan koneksi database
$auth = new AuthController($conn);
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lakukan login jika form disubmit
    $error_message = $auth->login();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <!-- my style -->
    <link rel="stylesheet" href="../css/style.css">
    <link rel="icon" href="../images/box.png" type="image/x-icon">

    <!-- font google : poppins -->
    <link rel="stylesheet" href="../css/google-poppins-style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

    <!-- bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<style>
    body {
        font-family: 'Poppins', sans-serif;
    }

    .main {
        height: 100vh;
    }

    .login-box {
        width: 400px;
        padding: 2rem;
        border-radius: 15px;
        background-image: linear-gradient(to right, #434343 0%, black 100%);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
    }

    .bg {
        background-image: linear-gradient(to right, #868f96 0%, #596164 100%);
        height: 100%;
        background-position: center;
        background-repeat: no-repeat;
        background-size: cover;
    }

    .white {
        color: white;
    }

    .no-decoration {
        text-decoration: none;
    }

    .login-button {
        background-color: rgba(43, 43, 43, 0.66);
        border: none;
        color: white;
        padding: 12px;
        width: 100%;
        height: 45px;
        font-size: 16px;
        border-radius: 12px;
        transition: background-color 0.3s, transform 0.2s;
    }

    .login-button:hover {
        background-color: rgba(43, 43, 43, 0.8);
        transform: scale(1.05);
    }

    input.form-control {
        border-radius: 10px;
        border: none;
        padding: 10px;
        transition: box-shadow 0.3s;
    }

    input.form-control:focus {
        box-shadow: 0 0 5px rgba(255, 255, 255, 0.8);
        outline: none;
    }

    .signup-link {
        text-align: center;
        margin-top: 1rem;
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

<body>
    <script src="../bootstrap/js/bootstrap.min.js"></script>
    <script src="../fontawesome/js/all.min.js"></script>
    <div class="website-name">Katalog Pakaian Instansi</div>


    <div class="bg">
        <div class="main d-flex justify-content-center align-items-center">
            <div class="login-box">
                <div class="text-center mb-4 white">
                    <h2>LOGIN TO INVENTORY</h2>
                </div>
                <form action="" method="post">
                    <div class="mb-4">
                        <?php if (!empty($error_message)): ?>
                            <p style="color: red;"><?= htmlspecialchars($error_message); ?></p>
                        <?php endif; ?>
                        <input type="text" class="form-control" id="username" name="username" placeholder="username" required>
                    </div>
                    <div class="mb-4">
                        <input type="password" class="form-control" id="password" name="password" placeholder="password" required>
                    </div>
                    <div class="text-center">
                        <button class="login-button" type="submit" name="loginbtn">Login</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>
