<?php 
include "include/header.php"; 
include "include/topnavbar.php"; 
?>
<div id="layoutSidenav">
    <div id="layoutSidenav_nav">
        <?php include "include/menubar.php"; ?>
    </div>
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid p-0 p-2">
                <div class="card" style="margin-top: 2rem">
                    <div class="card-body">
                        <h5 class="card-title">POS System</h5>
                        <div class="row">
                            <div class="col-md-8">
                                <!-- Barcode Search -->
                                <div class="input-group mb-3">
                                    <input type="text" id="barcodeSearch" class="form-control" placeholder="Scan or enter barcode..." onkeyup="handleBarcodeSearch(event)">
                                    <button class="btn btn-outline-secondary" type="button" onclick="manualBarcodeSearch()">Search</button>
                                </div>
                                <!-- Product Search -->
                                <input type="text" id="productSearch" class="form-control" placeholder="Search for products..." onkeyup="searchProducts()">
                                <div id="productList" class="list-group mt-2">
                                    <!-- Product items will be dynamically inserted here -->
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">Cart</h5>
                                    </div>
                                    <div class="card-body">
                                        <ul id="cartItems" class="list-group">
                                            <!-- Cart items will be dynamically inserted here -->
                                        </ul>
                                        <div class="mt-3">
                                            <h5>Total: <span id="cartTotal">$0.00</span></h5>
                                        </div>
                                        <div class="mt-3">
                                            <button class="btn btn-success btn-block" onclick="previewBill()">Preview Bill (F1)</button>
                                            <button class="btn btn-danger btn-block mt-2" onclick="clearCart()">Clear Cart (F2)</button>
                                        </div>
                                        <div class="mt-3">
                                            <button class="btn btn-primary btn-block" onclick="processPayment('Cash')">Pay with Cash (F3)</button>
                                            <button class="btn btn-primary btn-block mt-2" onclick="processPayment('Card')">Pay with Card (F4)</button>
                                            <button class="btn btn-primary btn-block mt-2" onclick="processPayment('Mobile')">Pay with Mobile (F5)</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <?php include "include/footerbar.php"; ?>
    </div>
</div>

<!-- Bill Preview Modal -->
<div class="modal fade" id="billPreviewModal" tabindex="-1" aria-labelledby="billPreviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="billPreviewModalLabel">Bill Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody id="billPreviewItems">
                        <!-- Bill items will be dynamically inserted here -->
                    </tbody>
                </table>
                <h5 class="text-end">Total: <span id="billPreviewTotal">$0.00</span></h5>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="finalizeSale()">Finalize Sale</button>
            </div>
        </div>
    </div>
</div>

<?php include "include/footerscripts.php"; ?>

<script type="text/javascript">
    let cart = [];
    let total = 0;

    // Sample product data (replace with database query in real implementation)
    const products = [
        { id: 1, name: 'Product 1', price: 10.00, barcode: '123456' },
        { id: 2, name: 'Product 2', price: 20.00, barcode: '654321' },
        { id: 3, name: 'Product 3', price: 30.00, barcode: '987654' }
    ];

    // Barcode Search
    function handleBarcodeSearch(event) {
        if (event.key === 'Enter') {
            manualBarcodeSearch();
        }
    }

    function manualBarcodeSearch() {
        const barcode = document.getElementById('barcodeSearch').value.trim();
        if (!barcode) return;

        const product = products.find(p => p.barcode === barcode);
        if (product) {
            addToCart(product);
            document.getElementById('barcodeSearch').value = ''; // Clear barcode input
        } else {
            alert('Product not found!');
        }
    }

    // Product Search
    function searchProducts() {
        const searchTerm = document.getElementById('productSearch').value;
        const filteredProducts = products.filter(product => product.name.toLowerCase().includes(searchTerm.toLowerCase()));
        const productList = document.getElementById('productList');
        productList.innerHTML = '';

        filteredProducts.forEach(product => {
            const productItem = document.createElement('button');
            productItem.className = 'list-group-item list-group-item-action';
            productItem.innerText = `${product.name} - $${product.price.toFixed(2)}`;
            productItem.onclick = () => addToCart(product);
            productList.appendChild(productItem);
        });
    }

    // Add to Cart
    function addToCart(product) {
        const existingItem = cart.find(item => item.id === product.id);
        if (existingItem) {
            existingItem.quantity += 1;
        } else {
            cart.push({ ...product, quantity: 1 });
        }
        updateCartDisplay();
    }

    // Update Cart Display
    function updateCartDisplay() {
        const cartItems = document.getElementById('cartItems');
        cartItems.innerHTML = '';
        total = 0;

        cart.forEach(item => {
            const cartItem = document.createElement('li');
            cartItem.className = 'list-group-item d-flex justify-content-between align-items-center';
            cartItem.innerText = `${item.name} x ${item.quantity} - $${(item.price * item.quantity).toFixed(2)}`;
            const removeButton = document.createElement('button');
            removeButton.className = 'btn btn-danger btn-sm';
            removeButton.innerText = 'Remove';
            removeButton.onclick = () => removeFromCart(item.id);
            cartItem.appendChild(removeButton);
            cartItems.appendChild(cartItem);
            total += item.price * item.quantity;
        });

        document.getElementById('cartTotal').innerText = `$${total.toFixed(2)}`;
    }

    // Remove from Cart
    function removeFromCart(productId) {
        cart = cart.filter(item => item.id !== productId);
        updateCartDisplay();
    }

    // Clear Cart
    function clearCart() {
        cart = [];
        updateCartDisplay();
    }

    // Preview Bill
    function previewBill() {
        if (cart.length === 0) {
            alert('Cart is empty!');
            return;
        }

        const billPreviewItems = document.getElementById('billPreviewItems');
        billPreviewItems.innerHTML = '';
        let billTotal = 0;

        cart.forEach(item => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${item.name}</td>
                <td>${item.quantity}</td>
                <td>$${item.price.toFixed(2)}</td>
                <td>$${(item.price * item.quantity).toFixed(2)}</td>
            `;
            billPreviewItems.appendChild(row);
            billTotal += item.price * item.quantity;
        });

        document.getElementById('billPreviewTotal').innerText = `$${billTotal.toFixed(2)}`;
        new bootstrap.Modal(document.getElementById('billPreviewModal')).show();
    }

    // Finalize Sale
    function finalizeSale() {
        if (cart.length === 0) {
            alert('Cart is empty!');
            return;
        }
        alert('Sale finalized!');
        clearCart();
        new bootstrap.Modal(document.getElementById('billPreviewModal')).hide();
    }

    // Process Payment
    function processPayment(method) {
        if (cart.length === 0) {
            alert('Cart is empty!');
            return;
        }
        alert(`Payment processed with ${method}`);
        clearCart();
    }

    // Shortcut Keys
    document.addEventListener('keydown', (event) => {
        if (event.key === 'F1') previewBill();
        if (event.key === 'F2') clearCart();
        if (event.key === 'F3') processPayment('Cash');
        if (event.key === 'F4') processPayment('Card');
        if (event.key === 'F5') processPayment('Mobile');
    });
</script>

<?php include "include/footer.php"; ?>