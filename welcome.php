<?php
session_start();
if (!isset($_SESSION['username'])) {
  header('Location: login.php');
  exit();
}
$username = $_SESSION['username'];
?>
<!DOCTYPE html>
<html>
<head>
  <title>Willkommen</title>
  <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<div class="container">
  <h1>Willkommen</h1>
  <p>Hallo, <?php echo htmlspecialchars($username); ?>!</p>
</div>
</body>
</html>