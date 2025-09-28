<?php
require 'includes/session.php';
checkLogin();

$pageTitle = "Dashboard";
require 'includes/header.php';
?>

<div class="container">
    <a href="contacts/" class="card">📇 Contact Book</a>
    <a href="inventory/" class="card">📦 Inventory Management</a>
    <a href="employees/" class="card">👥 Employee Management</a>
    <a href="complaints/" class="card">📝 Complaint Management</a>
    <a href="quiz/" class="card">❓ Quiz & Evaluation</a>
</div>

<?php require 'includes/footer.php'; ?>
