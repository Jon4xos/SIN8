<?php
require 'vendor/autoload.php';
use OTPHP\TOTP;

session_start();
$pdo = new PDO('mysql:host=localhost;dbname=SIN8', 'root', ''); // Passen Sie die Datenbankverbindung an

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
  <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<div class="container">
  <h1>Registrierung</h1>
  <form method="POST">
    <label>Benutzername: <input type="text" name="username" required></label><br>
    <label>Passwort: <input type="password" name="password" required></label><br>
    <button type="submit">Registrieren</button>
  </form>
</div>
</body>
</html>
