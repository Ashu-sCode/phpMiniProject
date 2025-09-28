<?php
require '../includes/session.php';
require '../includes/database.php';
checkLogin();

$action = $_GET['action'] ?? '';

if($action=='list'){
    $stmt=$pdo->query("SELECT * FROM employees ORDER BY created_at DESC");
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    exit();
}

if($action=='get'){
    $id=$_GET['id'] ?? 0;
    $stmt=$pdo->prepare("SELECT * FROM employees WHERE emp_id=?");
    $stmt->execute([$id]);
    echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
    exit();
}

if($action=='save'){
    $id=$_POST['emp_id'] ?? 0;
    $name=$_POST['name'] ?? '';
    $department=$_POST['department'] ?? '';
    $designation=$_POST['designation'] ?? '';
    $salary=$_POST['salary'] ?? 0;
    $email=$_POST['email'] ?? '';
    $phone=$_POST['phone'] ?? '';
    $join_date=$_POST['join_date'] ?? null;

    if(!$name){
        echo json_encode(['success'=>false,'error'=>'Name is required']);
        exit();
    }

    if($id){
        $stmt=$pdo->prepare("UPDATE employees SET name=?, department=?, designation=?, salary=?, email=?, phone=?, join_date=? WHERE emp_id=?");
        $stmt->execute([$name,$department,$designation,$salary,$email,$phone,$join_date,$id]);
    } else {
        $stmt=$pdo->prepare("INSERT INTO employees (name, department, designation, salary, email, phone, join_date) VALUES (?,?,?,?,?,?,?)");
        $stmt->execute([$name,$department,$designation,$salary,$email,$phone,$join_date]);
    }

    echo json_encode(['success'=>true]);
    exit();
}

if($action=='delete'){
    $id=$_GET['id'] ?? 0;
    $stmt=$pdo->prepare("DELETE FROM employees WHERE emp_id=?");
    $stmt->execute([$id]);
    echo json_encode(['success'=>true]);
    exit();
}
