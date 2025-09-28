<?php
require '../includes/session.php';
require '../includes/database.php';
checkLogin();

$action = $_GET['action'] ?? '';

if($action == 'list') {
    $stmt = $pdo->prepare("SELECT * FROM contacts WHERE user_id = ? ORDER BY created_at DESC");
    $stmt->execute([$_SESSION['user_id']]);
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    exit();
}

if($action == 'get') {
    $id = $_GET['id'] ?? 0;
    $stmt = $pdo->prepare("SELECT * FROM contacts WHERE contact_id=? AND user_id=?");
    $stmt->execute([$id, $_SESSION['user_id']]);
    echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
    exit();
}

if($action == 'save') {
    $id = $_POST['contact_id'] ?? 0;
    $name = $_POST['name'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $email = $_POST['email'] ?? '';
    $relation = $_POST['relation'] ?? '';

    if(!$name || !$email) {
        echo json_encode(['success'=>false, 'error'=>'Name and Email are required']);
        exit();
    }

    if($id) {
        // Update
        $stmt = $pdo->prepare("UPDATE contacts SET name=?, phone=?, email=?, relation=? WHERE contact_id=? AND user_id=?");
        $stmt->execute([$name,$phone,$email,$relation,$id,$_SESSION['user_id']]);
    } else {
        // Insert
        $stmt = $pdo->prepare("INSERT INTO contacts (user_id,name,phone,email,relation) VALUES (?,?,?,?,?)");
        $stmt->execute([$_SESSION['user_id'],$name,$phone,$email,$relation]);
    }

    echo json_encode(['success'=>true]);
    exit();
}

if($action == 'delete') {
    $id = $_GET['id'] ?? 0;
    $stmt = $pdo->prepare("DELETE FROM contacts WHERE contact_id=? AND user_id=?");
    $stmt->execute([$id,$_SESSION['user_id']]);
    echo json_encode(['success'=>true]);
    exit();
}
