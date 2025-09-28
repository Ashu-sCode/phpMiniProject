<?php
require '../includes/session.php';
require '../includes/database.php';
checkLogin();

$id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("DELETE FROM contacts WHERE contact_id = ? AND user_id = ?");
$stmt->execute([$id, $_SESSION['user_id']]);

header("Location: view_contacts.php");
exit();
