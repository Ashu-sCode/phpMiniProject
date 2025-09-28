<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $pageTitle ?? "Master PHP Project"; ?></title>
    <link rel="stylesheet" href="/php-projects/Projects/bcaMiniProject/assets/style.css">

    <?php if (isset($extraCSS)) { echo $extraCSS; } ?>
</head>
<body>
<div class="page-wrapper">
<header class="site-header">
    <h1>Master PHP Project</h1>
    <?php if (isset($_SESSION['username'])): ?>
        <p>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?> (<?php echo $_SESSION['role']; ?>)</p>
        <a class="logout-btn" href="/php-projects/Projects/bcaMiniProject/logout.php">Logout</a>
    <?php endif; ?>
</header>
<main class="page-content">
