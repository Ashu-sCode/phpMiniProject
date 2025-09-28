<?php
require '../includes/session.php';
require '../includes/database.php';
checkLogin();

$action = $_GET['action'] ?? '';

if($action == 'list') {
    $stmt = $pdo->prepare("SELECT * FROM products ORDER BY created_at DESC");
    $stmt->execute();
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    exit();
}

if($action == 'get') {
    $id = $_GET['id'] ?? 0;
    $stmt = $pdo->prepare("SELECT * FROM products WHERE product_id=?");
    $stmt->execute([$id]);
    echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
    exit();
}

if($action == 'save') {
    $id = $_POST['product_id'] ?? 0;
    $name = $_POST['name'] ?? '';
    $description = $_POST['description'] ?? '';
    $price = $_POST['price'] ?? 0;
    $stock = $_POST['stock'] ?? 0;

    if(!$name || !$price || !$stock) {
        echo json_encode(['success'=>false, 'error'=>'Name, Price and Stock are required']);
        exit();
    }

    if($id){
        $stmt = $pdo->prepare("UPDATE products SET name=?, description=?, price=?, stock=? WHERE product_id=?");
        $stmt->execute([$name,$description,$price,$stock,$id]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO products (name, description, price, stock) VALUES (?,?,?,?)");
        $stmt->execute([$name,$description,$price,$stock]);
    }

    echo json_encode(['success'=>true]);
    exit();
}

if($action == 'delete') {
    $id = $_GET['id'] ?? 0;
    $stmt = $pdo->prepare("DELETE FROM products WHERE product_id=?");
    $stmt->execute([$id]);
    echo json_encode(['success'=>true]);
    exit();
}
