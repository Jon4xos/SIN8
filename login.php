<?php
require 'vendor/autoload.php';
use OTPHP\TOTP;

session_start();
$pdo = new PDO('mysql:host=localhost;dbname=SIN8', 'root', ''); // Passen Sie die Datenbankverbindung an

$message = '';
$message_type = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = $_POST['username'];
  $password = $_POST['password'];
  $otp = $_POST['otp'];

  $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
  $stmt->execute([$username]);
  $user = $stmt->fetch();

  if ($user && password_verify($password, $user['password_hash'])) {
    $totp = TOTP::create($user['secret']);

    if ($totp->verify($otp)) {
      $_SESSION['username'] = $username; // Setzen Sie den Benutzernamen in der Session
      header('Location: welcome.php'); // Weiterleitung zur Willkommensseite
      exit();
    } else {
      $message = 'Ungültiges Einmalpasswort.';
      $message_type = 'error';
    }
  } else {
    $message = 'Ungültiger Benutzername oder Passwort.';
    $message_type = 'error';
  }
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Anmeldung</title>
  <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<div class="container">
  <h1>Anmeldung</h1>
  <?php if ($message): ?>
    <p class="message <?php echo $message_type; ?>"><?php echo htmlspecialchars($message); ?></p>
  <?php endif; ?>
  <form method="POST">
    <label>Benutzername: <input type="text" name="username" required></label><br>
    <label>Passwort: <input type="password" name="password" required></label><br>
    <label>Einmalpasswort (OTP): <input type="text" name="otp" required></label><br>
    <button type="submit">Anmelden</button>
  </form>
</div>
</body>
</html>
