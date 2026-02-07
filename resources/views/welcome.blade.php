<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>
<body>
<div class="container">
    <div class="row p-5">
        <h3>Product Management</h3>
        <div class="col-md-4 mt-2">
            <form id="addProductForm" method="POST" action="">
                <div class="mb-3">
                    <label for="product_name" class="form-label">Product Name</label>
                    <input type="text" class="form-control" id="product_name" name="product_name" value="">
                    <div class="invalid-feedback"></div>
                </div>
                <div class="mb-3">
                    <label for="quantity" class="form-label">Quantity In Stock</label>
                    <input type="number" step="1" class="form-control" id="quantity" name="quantity" value="0">
                    <div class="invalid-feedback"></div>
                </div>
                <div class="mb-3">
                    <label for="price" class="form-label">Price per item</label>
                    <div class="input-group">
                        <span class="input-group-text">$</span>
                        <input type="text" id="price" name="price" class="form-control" aria-label="Amount (to the nearest dollar)" value="0">
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
                <button type="reset" class="btn btn-outline-secondary">Cancel</button>
            </form>
        </div>
        <div class="col-md-8 mt-2">
            <table class="table table-striped table-bordered" id="productsTable">
                <thead class="table-dark">
                <tr>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Price per Item</th>
                    <th>Datetime Submitted</th>
                    <th>Total Value</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-4.0.0.min.js" integrity="sha256-OaVG6prZf4v69dPg6PhVattBXkcOWQB62pdZ3ORyrao=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/additional-methods.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/additional-methods.min.js"></script>
<script>
    let products = [];

    $("#addProductForm").validate({
        rules: {
            product_name: "required",
            quantity: {
                required: true,
                min: 1,
                number: true
            },
            price: {
                required: true,
                min: 1,
                number: true
            }
        },
        messages: {
            product_name: "Please specify product name.",
            quantity: {
                required: "Please enter the quantity in stock.",
                number: "Quantity must be a valid number.",
                min: "Quantity must be at least 1."
            },
            price: {
                required: "Please enter the price per item.",
                number: "Price must be a valid number.",
                min: "Price must be at least 1."
            }
        },
        errorElement: 'div',
        errorClass: 'invalid-feedback',
        highlight: function(element) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function(element) {
            $(element).removeClass('is-invalid');
        },
        submitHandler: function(form) {
            const formData = {
                product_name: $('#product_name').val(),
                quantity: $('#quantity').val(),
                price: $('#price').val()
            };

            fetch('{{ route('products.index') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(formData)
            })
                .then(response => response.json())
                .then(data => {
                    renderRows(data.data.products);
                    alert('Product added successfully!');
                    form.reset();
                    $('.form-control').removeClass('is-invalid');
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Failed to submit product.');
                });
        }
    });

    function renderRows(products) {
        const tbody = document.querySelector('#productsTable tbody');
        tbody.innerHTML = '';

        products.sort((a, b) => new Date(b.submitted_at) - new Date(a.submitted_at));

        products.forEach(p => {
            const totalValue = (p.quantity * p.price).toFixed(2);
            const row = `
            <tr>
                <td>${p.product_name}</td>
                <td>${p.quantity}</td>
                <td>$${parseFloat(p.price).toFixed(2)}</td>
                <td>${p.submitted_at}</td>
                <td>$${totalValue}</td>
            </tr>
        `;
            tbody.insertAdjacentHTML('beforeend', row);
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        fetchProducts();
    });

    function fetchProducts() {
        fetch('{{ route('products.index') }}')
            .then(res => res.json())
            .then(res => {
                if (res.success) {
                    renderRows(res.data.products);
                } else {
                    console.warn('No products found.');
                }
            })
            .catch(err => console.error('Error fetching products:', err));
    }
</script>
</body>
</html>
