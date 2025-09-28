<?php
require '../includes/session.php';
require '../includes/database.php';
checkLogin();

$extraCSS = '<link rel="stylesheet" href="/php-projects/Projects/bcaMiniProject/assets/employees.css">';
$pageTitle = "Employee Management";
require '../includes/header.php';
?>

<div class="container">
    <h2>Employee Management</h2>

    <!-- Employee Form -->
    <div id="form-container">
        <h3>Add / Edit Employee</h3>
        <form id="employee-form">
            <input type="hidden" name="emp_id" id="emp_id">
            <input type="text" name="name" id="name" placeholder="Full Name" required>
            <input type="text" name="department" id="department" placeholder="Department">
            <input type="text" name="designation" id="designation" placeholder="Designation">
            <input type="number" step="0.01" name="salary" id="salary" placeholder="Salary">
            <input type="email" name="email" id="email" placeholder="Email">
            <input type="text" name="phone" id="phone" placeholder="Phone">
            <input type="date" name="join_date" id="join_date" placeholder="Joining Date">
            <button type="submit">Save Employee</button>
            <button type="button" id="cancel-edit" style="display:none;">Cancel</button>
        </form>
        <p id="form-error" class="error"></p>
    </div>

    <!-- Employee Table -->
    <table class="employee-table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Dept</th>
                <th>Designation</th>
                <th>Salary</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Joining Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="employees-tbody">
            <!-- AJAX injected -->
        </tbody>
    </table>
</div>

<script>
document.addEventListener('DOMContentLoaded', function(){
    const tbody = document.getElementById('employees-tbody');
    const form = document.getElementById('employee-form');
    const errorP = document.getElementById('form-error');
    const cancelBtn = document.getElementById('cancel-edit');

    function loadEmployees(){
        fetch('employee_ajax.php?action=list')
        .then(res => res.json())
        .then(data => {
            tbody.innerHTML = '';
            if(data.length){
                data.forEach(emp => {
                    tbody.innerHTML += `
                        <tr>
                            <td>${emp.name}</td>
                            <td>${emp.department}</td>
                            <td>${emp.designation}</td>
                            <td>${emp.salary}</td>
                            <td>${emp.email}</td>
                            <td>${emp.phone}</td>
                            <td>${emp.join_date ? emp.join_date.split(' ')[0] : ''}</td>
                            <td>
                                <button class="edit-btn" data-id="${emp.emp_id}">Edit</button>
                                <button class="delete-btn" data-id="${emp.emp_id}">Delete</button>
                            </td>
                        </tr>
                    `;
                });
            } else {
                tbody.innerHTML = '<tr><td colspan="8" style="text-align:center;">No employees found.</td></tr>';
            }
        });
    }

    loadEmployees();

    form.addEventListener('submit', function(e){
        e.preventDefault();
        const formData = new FormData(form);
        fetch('employee_ajax.php?action=save', { method:'POST', body:formData })
        .then(res => res.json())
        .then(data => {
            if(data.success){
                form.reset();
                document.getElementById('emp_id').value='';
                cancelBtn.style.display='none';
                loadEmployees();
            } else {
                errorP.textContent = data.error;
            }
        });
    });

    tbody.addEventListener('click', function(e){
        if(e.target.classList.contains('edit-btn')){
            const id = e.target.dataset.id;
            fetch('employee_ajax.php?action=get&id=' + id)
            .then(res=>res.json())
            .then(emp=>{
                document.getElementById('emp_id').value = emp.emp_id;
                document.getElementById('name').value = emp.name;
                document.getElementById('department').value = emp.department;
                document.getElementById('designation').value = emp.designation;
                document.getElementById('salary').value = emp.salary;
                document.getElementById('email').value = emp.email;
                document.getElementById('phone').value = emp.phone;
                document.getElementById('join_date').value = emp.join_date ? emp.join_date.split(' ')[0] : '';
                cancelBtn.style.display='inline-block';
            });
        }
        if(e.target.classList.contains('delete-btn')){
            if(confirm('Are you sure?')){
                const id = e.target.dataset.id;
                fetch('employee_ajax.php?action=delete&id=' + id)
                .then(res=>res.json())
                .then(data=>{
                    if(data.success) loadEmployees();
                    else alert(data.error);
                });
            }
        }
    });

    cancelBtn.addEventListener('click', function(){
        form.reset();
        document.getElementById('emp_id').value='';
        cancelBtn.style.display='none';
        errorP.textContent='';
    });
});
</script>

<?php require '../includes/footer.php'; ?>
