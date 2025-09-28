<?php
require '../includes/session.php';
require '../includes/database.php';
checkLogin();

$id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("SELECT * FROM contacts WHERE contact_id = ? AND user_id = ?");
$stmt->execute([$id, $_SESSION['user_id']]);
$contact = $stmt->fetch();
if (!$contact) die("Contact not found or you don't have permission.");

$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $email = $_POST['email'] ?? '';
    $relation = $_POST['relation'] ?? '';

    if ($name && $email) {
        $stmt = $pdo->prepare("UPDATE contacts SET name=?, phone=?, email=?, relation=? WHERE contact_id=? AND user_id=?");
        $stmt->execute([$name, $phone, $email, $relation, $id, $_SESSION['user_id']]);
        header("Location: view_contacts.php");
        exit();
    } else {
        $error = "Name and Email are required!";
    }
}

$pageTitle = "Edit Contact";
require '../includes/header.php';
?>

<div class="container">
    <h2>Edit Contact</h2>
    <?php if($error) echo "<p class='error'>$error</p>"; ?>
    <form method="POST">
        <input type="text" name="name" value="<?php echo htmlspecialchars($contact['name']); ?>" required>
        <input type="text" name="phone" value="<?php echo htmlspecialchars($contact['phone']); ?>">
        <input type="email" name="email" value="<?php echo htmlspecialchars($contact['email']); ?>" required>
        <input type="text" name="relation" value="<?php echo htmlspecialchars($contact['relation']); ?>">
        <button type="submit">Update Contact</button>
    </form>
</div>

<?php require '../includes/footer.php'; ?>
