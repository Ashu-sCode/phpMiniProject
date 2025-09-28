<?php
require '../includes/session.php';
require '../includes/database.php';
checkLogin();

$action = $_GET['action'] ?? '';
$user_id = $_SESSION['user_id'];
$isAdmin = ($_SESSION['role'] ?? '') === 'admin';

if($action=='list'){
    if($isAdmin){
        $stmt=$pdo->query("SELECT c.*, u.username FROM complaints c JOIN users u ON c.user_id=u.user_id ORDER BY created_at DESC");
    } else {
        $stmt=$pdo->prepare("SELECT c.*, u.username FROM complaints c JOIN users u ON c.user_id=u.user_id WHERE c.user_id=? ORDER BY created_at DESC");
        $stmt->execute([$user_id]);
    }
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    exit();
}

if($action=='get'){
    $id=$_GET['id'] ?? 0;
    $stmt=$pdo->prepare("SELECT * FROM complaints WHERE complaint_id=? AND (user_id=? OR ?)");
    $stmt->execute([$id,$user_id,$isAdmin]);
    echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
    exit();
}

if($action=='save'){
    $id=$_POST['complaint_id'] ?? 0;
    $category=$_POST['category'] ?? '';
    $description=$_POST['description'] ?? '';

    if(!$category || !$description){
        echo json_encode(['success'=>false,'error'=>'Category & Description required']);
        exit();
    }

    if($id){
        if($isAdmin){
            $stmt=$pdo->prepare("UPDATE complaints SET category=?, description=?, status=? WHERE complaint_id=?");
            $stmt->execute([$category,$description,$_POST['status']??'pending',$id]);
        } else {
            $stmt=$pdo->prepare("UPDATE complaints SET category=?, description=? WHERE complaint_id=? AND user_id=?");
            $stmt->execute([$category,$description,$id,$user_id]);
        }
    } else {
        $stmt=$pdo->prepare("INSERT INTO complaints (user_id, category, description) VALUES (?,?,?)");
        $stmt->execute([$user_id,$category,$description]);
    }

    echo json_encode(['success'=>true]);
    exit();
}

if($action=='delete'){
    $id=$_GET['id'] ?? 0;
    if($isAdmin){
        $stmt=$pdo->prepare("DELETE FROM complaints WHERE complaint_id=?");
        $stmt->execute([$id]);
    } else {
        $stmt=$pdo->prepare("DELETE FROM complaints WHERE complaint_id=? AND user_id=?");
        $stmt->execute([$id,$user_id]);
    }
    echo json_encode(['success'=>true]);
    exit();
}
