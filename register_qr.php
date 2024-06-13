<?php
require 'vendor/autoload.php';
use OTPHP\TOTP;

session_start();
if (!isset($_SESSION['username']) || !isset($_SESSION['secret'])) {
  header('Location: register.php');
  exit();
}

$username = $_SESSION['username'];
$secret = $_SESSION['secret'];

$totp = TOTP::create($secret);
$totp->setLabel($username);
$qrCode = $totp->getProvisioningUri();
?>
<!DOCTYPE html>
<html>
<head>
  <title>QR-Code Registrierung</title>
  <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<div class="container">
  <h1>QR-Code für <?php echo htmlspecialchars($username); ?></h1>
  <img src="https://api.qrserver.com/v1/create-qr-code/?data=<?php echo urlencode($qrCode); ?>&size=200x200" alt="QR Code">
</div>
</body>
</html>
