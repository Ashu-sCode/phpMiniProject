<?php
require '../includes/session.php';
require '../includes/database.php';
checkLogin();

$extraCSS = '<link rel="stylesheet" href="/php-projects/Projects/bcaMiniProject/assets/complaints.css">';
$pageTitle = "Complaint Management";
require '../includes/header.php';
?>

<div class="container">
    <h2>Complaint Management</h2>

    <!-- Complaint Form -->
    <div id="form-container">
        <h3>Submit Complaint</h3>
        <form id="complaint-form">
            <input type="hidden" name="complaint_id" id="complaint_id">
            <input type="text" name="category" id="category" placeholder="Category" required>
            <textarea name="description" id="description" placeholder="Describe your complaint..." required></textarea>
            <button type="submit">Submit</button>
            <button type="button" id="cancel-edit" style="display:none;">Cancel</button>
        </form>
        <p id="form-error" class="error"></p>
    </div>

    <!-- Complaints Table -->
    <table class="complaint-table">
        <thead>
            <tr>
                <th>User</th>
                <th>Category</th>
                <th>Description</th>
                <th>Status</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="complaints-tbody">
            <!-- AJAX injected -->
        </tbody>
    </table>
</div>

<script>
document.addEventListener('DOMContentLoaded', function(){
    const tbody = document.getElementById('complaints-tbody');
    const form = document.getElementById('complaint-form');
    const errorP = document.getElementById('form-error');
    const cancelBtn = document.getElementById('cancel-edit');

    function loadComplaints(){
        fetch('complaint_ajax.php?action=list')
        .then(res => res.json())
        .then(data => {
            tbody.innerHTML = '';
            if(data.length){
                data.forEach(c=>{
                    tbody.innerHTML += `
                        <tr>
                            <td>${c.username}</td>
                            <td>${c.category}</td>
                            <td>${c.description}</td>
                            <td>${c.status}</td>
                            <td>${c.created_at.split(' ')[0]}</td>
                            <td>
                                <button class="edit-btn" data-id="${c.complaint_id}">Edit</button>
                                <button class="delete-btn" data-id="${c.complaint_id}">Delete</button>
                            </td>
                        </tr>
                    `;
                });
            } else {
                tbody.innerHTML = '<tr><td colspan="6" style="text-align:center;">No complaints found.</td></tr>';
            }
        });
    }

    loadComplaints();

    form.addEventListener('submit', function(e){
        e.preventDefault();
        const formData = new FormData(form);
        fetch('complaint_ajax.php?action=save',{method:'POST',body:formData})
        .then(res=>res.json())
        .then(data=>{
            if(data.success){
                form.reset();
                document.getElementById('complaint_id').value='';
                cancelBtn.style.display='none';
                loadComplaints();
            } else {
                errorP.textContent=data.error;
            }
        });
    });

    tbody.addEventListener('click', function(e){
        if(e.target.classList.contains('edit-btn')){
            const id = e.target.dataset.id;
            fetch('complaint_ajax.php?action=get&id='+id)
            .then(res=>res.json())
            .then(c=>{
                document.getElementById('complaint_id').value=c.complaint_id;
                document.getElementById('category').value=c.category;
                document.getElementById('description').value=c.description;
                cancelBtn.style.display='inline-block';
            });
        }
        if(e.target.classList.contains('delete-btn')){
            if(confirm('Are you sure?')){
                const id = e.target.dataset.id;
                fetch('complaint_ajax.php?action=delete&id='+id)
                .then(res=>res.json())
                .then(data=>{ if(data.success) loadComplaints(); else alert(data.error); });
            }
        }
    });

    cancelBtn.addEventListener('click', function(){
        form.reset();
        document.getElementById('complaint_id').value='';
        cancelBtn.style.display='none';
        errorP.textContent='';
    });
});
</script>

<?php require '../includes/footer.php'; ?>
