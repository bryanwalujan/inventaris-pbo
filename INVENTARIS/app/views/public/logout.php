<?php
session_start();

// Hapus semua sesi
$_SESSION = array();

// Jika ingin menghancurkan sesi
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

session_destroy(); // Hancurkan sesi

header("Location: index.php"); // Redirect ke halaman login
exit();
?>
