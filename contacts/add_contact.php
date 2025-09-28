<?php
require '../includes/session.php';
require '../includes/database.php';
checkLogin();

$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $email = $_POST['email'] ?? '';
    $relation = $_POST['relation'] ?? '';

    if ($name && $email) {
        $stmt = $pdo->prepare("INSERT INTO contacts (user_id, name, phone, email, relation) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$_SESSION['user_id'], $name, $phone, $email, $relation]);
        header("Location: view_contacts.php");
        exit();
    } else {
        $error = "Name and Email are required!";
    }
}

$pageTitle = "Add Contact";
require '../includes/header.php';
?>

<div class="container">
    <h2>Add Contact</h2>
    <?php if($error) echo "<p class='error'>$error</p>"; ?>
    <form method="POST">
        <input type="text" name="name" placeholder="Full Name" required>
        <input type="text" name="phone" placeholder="Phone">
        <input type="email" name="email" placeholder="Email" required>
        <input type="text" name="relation" placeholder="Relation">
        <button type="submit">Add Contact</button>
    </form>
</div>

<?php require '../includes/footer.php'; ?>
