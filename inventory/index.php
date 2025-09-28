<?php
require '../includes/session.php';
require '../includes/database.php';
checkLogin();

$extraCSS = '<link rel="stylesheet" href="/php-projects/Projects/bcaMiniProject/assets/inventory.css">';
$pageTitle = "Inventory Management";
require '../includes/header.php';
?>

<div class="container">
    <h2>Inventory Management</h2>

    <!-- Product Form -->
    <div id="form-container">
        <h3>Add / Edit Product</h3>
        <form id="product-form">
            <input type="hidden" name="product_id" id="product_id">
            <input type="text" name="name" id="name" placeholder="Product Name" required>
            <textarea name="description" id="description" placeholder="Description"></textarea>
            <input type="number" name="price" id="price" placeholder="Price" step="0.01" required>
            <input type="number" name="stock" id="stock" placeholder="Stock Quantity" required>
            <button type="submit">Save Product</button>
            <button type="button" id="cancel-edit" style="display:none;">Cancel</button>
        </form>
        <p id="form-error" class="error"></p>
    </div>

    <!-- Product Table -->
    <table class="inventory-table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Description</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="products-tbody">
            <!-- Rows injected via JS -->
        </tbody>
    </table>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tbody = document.getElementById('products-tbody');
    const form = document.getElementById('product-form');
    const errorP = document.getElementById('form-error');
    const cancelBtn = document.getElementById('cancel-edit');

    function loadProducts() {
        fetch('inventory_ajax.php?action=list')
            .then(res => res.json())
            .then(data => {
                tbody.innerHTML = '';
                if(data.length) {
                    data.forEach(p => {
                        tbody.innerHTML += `
                            <tr>
                                <td>${p.name}</td>
                                <td>${p.description}</td>
                                <td>${p.price}</td>
                                <td>${p.stock}</td>
                                <td>
                                    <button class="edit-btn" data-id="${p.product_id}">Edit</button>
                                    <button class="delete-btn" data-id="${p.product_id}">Delete</button>
                                </td>
                            </tr>
                        `;
                    });
                } else {
                    tbody.innerHTML = '<tr><td colspan="5" style="text-align:center;">No products found.</td></tr>';
                }
            });
    }

    loadProducts();

    form.addEventListener('submit', function(e){
        e.preventDefault();
        const formData = new FormData(form);
        fetch('inventory_ajax.php?action=save', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if(data.success){
                form.reset();
                document.getElementById('product_id').value = '';
                cancelBtn.style.display = 'none';
                loadProducts();
            } else {
                errorP.textContent = data.error;
            }
        });
    });

    tbody.addEventListener('click', function(e){
        if(e.target.classList.contains('edit-btn')){
            const id = e.target.dataset.id;
            fetch('inventory_ajax.php?action=get&id=' + id)
                .then(res => res.json())
                .then(p => {
                    document.getElementById('product_id').value = p.product_id;
                    document.getElementById('name').value = p.name;
                    document.getElementById('description').value = p.description;
                    document.getElementById('price').value = p.price;
                    document.getElementById('stock').value = p.stock;
                    cancelBtn.style.display = 'inline-block';
                });
        }
        if(e.target.classList.contains('delete-btn')){
            if(confirm('Are you sure?')){
                const id = e.target.dataset.id;
                fetch('inventory_ajax.php?action=delete&id=' + id)
                    .then(res => res.json())
                    .then(data => {
                        if(data.success) loadProducts();
                        else alert(data.error);
                    });
            }
        }
    });

    cancelBtn.addEventListener('click', function(){
        form.reset();
        document.getElementById('product_id').value = '';
        cancelBtn.style.display = 'none';
        errorP.textContent = '';
    });
});
</script>

<?php require '../includes/footer.php'; ?>
