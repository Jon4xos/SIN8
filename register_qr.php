<?php
require 'vendor/autoload.php';
use OTPHP\TOTP;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

session_start();
if (!isset($_SESSION['username']) || !isset($_SESSION['secret'])) {
  header('Location: register.php');
  exit();
}

$username = $_SESSION['username'];
$secret = $_SESSION['secret'];

$totp = TOTP::create($secret);
$totp->setLabel($username);

$qrCode = new QrCode($totp->getProvisioningUri());
$writer = new PngWriter();
$qrCodeImage = $writer->write($qrCode)->getString();

echo '<h1>QR-Code für ' . htmlspecialchars($username) . '</h1>';
echo '<img src="data:image/png;base64,' . base64_encode($qrCodeImage) . '">';
?>
