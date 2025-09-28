<?php
require '../includes/session.php';
require '../includes/database.php';
checkLogin();

$extraCSS = '<link rel="stylesheet" href="/php-projects/Projects/bcaMiniProject/assets/contacts.css">';
$pageTitle = "Contact Book";
require '../includes/header.php';
?>

<div class="container">
    <h2>Contact Book</h2>

    <!-- Add Contact Form -->
    <div id="form-container">
        <h3>Add / Edit Contact</h3>
        <form id="contact-form">
            <input type="hidden" name="contact_id" id="contact_id">
            <input type="text" name="name" id="name" placeholder="Full Name" required>
            <input type="text" name="phone" id="phone" placeholder="Phone">
            <input type="email" name="email" id="email" placeholder="Email" required>
            <input type="text" name="relation" id="relation" placeholder="Relation">
            <button type="submit">Save Contact</button>
            <button type="button" id="cancel-edit" style="display:none;">Cancel</button>
        </form>
        <p id="form-error" class="error"></p>
    </div>

    <!-- Contact Table -->
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
        <tbody id="contacts-tbody">
            <!-- Rows will be injected by JS -->
        </tbody>
    </table>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tbody = document.getElementById('contacts-tbody');
    const form = document.getElementById('contact-form');
    const errorP = document.getElementById('form-error');
    const cancelBtn = document.getElementById('cancel-edit');

    // Fetch all contacts
    function loadContacts() {
        fetch('contacts_ajax.php?action=list')
            .then(res => res.json())
            .then(data => {
                tbody.innerHTML = '';
                if(data.length) {
                    data.forEach(c => {
                        tbody.innerHTML += `
                            <tr>
                                <td>${c.name}</td>
                                <td>${c.phone}</td>
                                <td>${c.email}</td>
                                <td>${c.relation}</td>
                                <td>
                                    <button class="edit-btn" data-id="${c.contact_id}">Edit</button>
                                    <button class="delete-btn" data-id="${c.contact_id}">Delete</button>
                                </td>
                            </tr>
                        `;
                    });
                } else {
                    tbody.innerHTML = '<tr><td colspan="5" style="text-align:center;">No contacts found.</td></tr>';
                }
            });
    }

    loadContacts();

    // Submit Add/Edit Form
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(form);
        fetch('contacts_ajax.php?action=save', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if(data.success) {
                form.reset();
                document.getElementById('contact_id').value = '';
                cancelBtn.style.display = 'none';
                loadContacts();
            } else {
                errorP.textContent = data.error;
            }
        });
    });

    // Edit button
    tbody.addEventListener('click', function(e) {
        if(e.target.classList.contains('edit-btn')) {
            const id = e.target.dataset.id;
            fetch('contacts_ajax.php?action=get&id=' + id)
                .then(res => res.json())
                .then(c => {
                    document.getElementById('contact_id').value = c.contact_id;
                    document.getElementById('name').value = c.name;
                    document.getElementById('phone').value = c.phone;
                    document.getElementById('email').value = c.email;
                    document.getElementById('relation').value = c.relation;
                    cancelBtn.style.display = 'inline-block';
                });
        }
    });

    // Delete button
    tbody.addEventListener('click', function(e) {
        if(e.target.classList.contains('delete-btn')) {
            if(confirm('Are you sure?')) {
                const id = e.target.dataset.id;
                fetch('contacts_ajax.php?action=delete&id=' + id)
                    .then(res => res.json())
                    .then(data => {
                        if(data.success) loadContacts();
                        else alert(data.error);
                    });
            }
        }
    });

    // Cancel Edit
    cancelBtn.addEventListener('click', function() {
        form.reset();
        document.getElementById('contact_id').value = '';
        cancelBtn.style.display = 'none';
        errorP.textContent = '';
    });
});
</script>

<?php require '../includes/footer.php'; ?>
