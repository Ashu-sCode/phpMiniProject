<?php
require '../includes/session.php';
require '../includes/database.php';
checkLogin();

$pageTitle = "View Contacts";
require '../includes/header.php';
?>

<div class="container">
    <h2>Contact List</h2>
    <a href="add_contact.php" class="btn-add">+ Add Contact</a>
    <table class="contact-table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Phone</th>
                <th>Email</th>
                <th>Relation</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Fetch only contacts of the logged-in user
            $stmt = $pdo->prepare("SELECT * FROM contacts WHERE user_id = ? ORDER BY created_at DESC");
            $stmt->execute([$_SESSION['user_id']]);
            while($contact = $stmt->fetch()) {
                echo "<tr>
                    <td>".htmlspecialchars($contact['name'])."</td>
                    <td>".htmlspecialchars($contact['phone'])."</td>
                    <td>".htmlspecialchars($contact['email'])."</td>
                    <td>".htmlspecialchars($contact['relation'])."</td>
                    <td>
                        <a href='edit_contact.php?id=".$contact['contact_id']."'>Edit</a> |
                        <a href='delete_contact.php?id=".$contact['contact_id']."' onclick=\"return confirm('Are you sure?')\">Delete</a>
                    </td>
                </tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<?php require '../includes/footer.php'; ?>
