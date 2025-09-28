<?php
require 'includes/session.php';
checkLogin();

$pageTitle = "Dashboard";
$extraCSS = '<link rel="stylesheet" href="assets/dashboard.css">';
require 'includes/header.php';
?>

<div class="dashboard-container">
    <a href="contacts/" class="dashboard-card">
        <div class="icon">📇</div>
        <div class="title">Contact Book</div>
    </a>
    <a href="inventory/" class="dashboard-card">
        <div class="icon">📦</div>
        <div class="title">Inventory Management</div>
    </a>
    <a href="employees/" class="dashboard-card">
        <div class="icon">👥</div>
        <div class="title">Employee Management</div>
    </a>
    <a href="complaints/" class="dashboard-card">
        <div class="icon">📝</div>
        <div class="title">Complaint Management</div>
    </a>
    <a href="quiz/" class="dashboard-card">
        <div class="icon">❓</div>
        <div class="title">Quiz & Evaluation</div>
    </a>
</div>

<?php require 'includes/footer.php'; ?>
