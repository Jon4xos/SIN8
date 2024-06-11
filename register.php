<?php
require 'vendor/autoload.php';
use OTPHP\TOTP;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

session_start();
$pdo = new PDO('mysql:host=localhost;dbname=test', 'root', 'password'); // Passen Sie die Datenbankverbindung an

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $totp = TOTP::create();
    $totp->setLabel($username);
    $secret = $totp->getSecret();

    $stmt = $pdo->prepare("INSERT INTO users (username, password_hash, secret) VALUES (?, ?, ?)");
    $stmt->execute([$username, $password, $secret]);

    $_SESSION['username'] = $username;
    $_SESSION['secret'] = $secret;

    header('Location: register_qr.php');
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Registrierung</title>
</head>
<body>
    <h1>Registrierung</h1>
    <form method="POST">
        <label>Benutzername: <input type="text" name="username" required></label><br>
        <label>Passwort: <input type="password" name="password" required></label><br>
        <button type="submit">Registrieren</button>
    </form>
</body>
</html>
