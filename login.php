<?php
require 'vendor/autoload.php';
use OTPHP\TOTP;

session_start();
$pdo = new PDO('mysql:host=localhost;dbname=test', 'root', 'password'); // Passen Sie die Datenbankverbindung an

$error = '';

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
      echo '<p>Anmeldung erfolgreich!</p>';
      exit();
    } else {
      $error = 'Ungültiges Einmalpasswort.';
    }
  } else {
    $error = 'Ungültiger Benutzername oder Passwort.';
  }
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Anmeldung</title>
</head>
<body>
<h1>Anmeldung</h1>
<?php if ($error): ?>
  <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
<?php endif; ?>
<form method="POST">
  <label>Benutzername: <input type="text" name="username" required></label><br>
  <label>Passwort: <input type="password" name="password" required></label><br>
  <label>Einmalpasswort (OTP): <input type="text" name="otp" required></label><br>
  <button type="submit">Anmelden</button>
</form>
</body>
</html>

