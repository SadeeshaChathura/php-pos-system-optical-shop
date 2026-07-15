<?php 
include "include/header.php"; 
include "include/topnavbar.php"; 
?>
<!-- Custom Ocean Blue Theme CSS -->
<style>
    .bg-ocean-blue {
        background-color: #0077be; /* Ocean Blue */
        color: white;
    }
    .btn-ocean-blue {
        background-color: #005f8c; /* Darker Ocean Blue */
        color: white;
        border: none;
    }
    .btn-ocean-blue:hover {
        background-color: #004466; /* Even Darker Ocean Blue */
    }
    .card-header-ocean-blue {
        background-color: #0077be; /* Ocean Blue */
        color: white;
    }
    .table-ocean-blue th {
        background-color: #0077be; /* Ocean Blue */
        color: white;
    }
    .product-card {
        border: 1px solid #ddd;
        border-radius: 10px;
        transition: transform 0.2s;
        cursor: pointer;
    }
    .product-card:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    .dialpad {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 10px;
    }
    .dialpad button {
        font-size: 1.5rem;
        padding: 15px;
        border-radius: 10px;
    }
    .cart-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px;
        border-bottom: 1px solid #ddd;
    }
    .cart-item:last-child {
        border-bottom: none;
    }
</style>

<div id="layoutSidenav">
    <div id="layoutSidenav_nav">
        <?php include "include/menubar.php"; ?>
    </div>
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid p-0 p-2">
                <div class="card" style="margin-top: 2rem">
                    <div class="card-header bg-ocean-blue">
                        <h5 class="card-title text-white">POS System</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- Product List -->
                            <div class="col-md-8">
                                <div class="card mb-3">
                                    <div class="card-header card-header-ocean-blue">
                                        <h5 class="card-title text-white">Products</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="input-group mb-3">
                                            <input type="text" id="barcodeSearch" class="form-control" placeholder="Scan or enter barcode..." onkeyup="handleBarcodeSearch(event)">
                                            <button class="btn btn-ocean-blue" type="button" onclick="manualBarcodeSearch()">Search</button>
                                        </div>
                                        <input type="text" id="productSearch" class="form-control" placeholder="Search for products..." onkeyup="searchProducts()">
                                        <div class="row mt-3" id="productList">
                                            <!-- Product cards will be dynamically inserted here -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Cart and Dialpad -->
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-header card-header-ocean-blue">
                                        <h5 class="card-title text-white">Cart</h5>
                                    </div>
                                    <div class="card-body">
                                        <div id="cartItems">
                                            <!-- Cart items will be dynamically inserted here -->
                                        </div>
                                        <div class="mt-3">
                                            <h5>Total: <span id="cartTotal">$0.00</span></h5>
                                        </div>
                                        <div class="mt-3">
                                            <button class="btn btn-ocean-blue btn-block" onclick="previewBill()">Preview Bill (F1)</button>
                                            <button class="btn btn-danger btn-block mt-2" onclick="clearCart()">Clear Cart (F2)</button>
                                        </div>
                                        <div class="mt-3">
                                            <button class="btn btn-ocean-blue btn-block" onclick="processPayment('Cash')">Pay with Cash (F3)</button>
                                            <button class="btn btn-ocean-blue btn-block mt-2" onclick="processPayment('Card')">Pay with Card (F4)</button>
                                            <button class="btn btn-ocean-blue btn-block mt-2" onclick="processPayment('Mobile')">Pay with Mobile (F5)</button>
                                        </div>
                                        <!-- Dialpad -->
                                        <div class="mt-3">
                                            <div class="dialpad">
                                                <button class="btn btn-outline-secondary" onclick="addToDialpad('1')">1</button>
                                                <button class="btn btn-outline-secondary" onclick="addToDialpad('2')">2</button>
                                                <button class="btn btn-outline-secondary" onclick="addToDialpad('3')">3</button>
                                                <button class="btn btn-outline-secondary" onclick="addToDialpad('4')">4</button>
                                                <button class="btn btn-outline-secondary" onclick="addToDialpad('5')">5</button>
                                                <button class="btn btn-outline-secondary" onclick="addToDialpad('6')">6</button>
                                                <button class="btn btn-outline-secondary" onclick="addToDialpad('7')">7</button>
                                                <button class="btn btn-outline-secondary" onclick="addToDialpad('8')">8</button>
                                                <button class="btn btn-outline-secondary" onclick="addToDialpad('9')">9</button>
                                                <button class="btn btn-outline-secondary" onclick="addToDialpad('0')">0</button>
                                                <button class="btn btn-outline-secondary" onclick="clearDialpad()">C</button>
                                                <button class="btn btn-outline-secondary" onclick="submitDialpad()">Enter</button>
                                            </div>
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
            <div class="modal-header bg-ocean-blue">
                <h5 class="modal-title text-white" id="billPreviewModalLabel">Bill Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="card">
                    <div class="card-body">
                        <table class="table table-bordered table-ocean-blue">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Qty</th>
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
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-ocean-blue" onclick="finalizeSale()">Finalize Sale</button>
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
        { id: 1, name: 'Product 1', price: 10.00, barcode: '123456', image: 'https://via.placeholder.com/100' },
        { id: 2, name: 'Product 2', price: 20.00, barcode: '654321', image: 'https://via.placeholder.com/100' },
        { id: 3, name: 'Product 3', price: 30.00, barcode: '987654', image: 'https://via.placeholder.com/100' }
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
            const productCard = `
                <div class="col-md-4 mb-3">
                    <div class="card product-card" onclick="addToCart(${JSON.stringify(product).replace(/"/g, '&quot;')})">
                        <img src="${product.image}" class="card-img-top" alt="${product.name}">
                        <div class="card-body">
                            <h5 class="card-title">${product.name}</h5>
                            <p class="card-text">$${product.price.toFixed(2)}</p>
                        </div>
                    </div>
                </div>
            `;
            productList.innerHTML += productCard;
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
            const cartItem = document.createElement('div');
            cartItem.className = 'cart-item';
            cartItem.innerHTML = `
                <span>${item.name} x ${item.quantity}</span>
                <span>$${(item.price * item.quantity).toFixed(2)}</span>
                <button class="btn btn-danger btn-sm" onclick="removeFromCart(${item.id})">Remove</button>
            `;
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

    // Dialpad Functions
    function addToDialpad(value) {
        const barcodeSearch = document.getElementById('barcodeSearch');
        barcodeSearch.value += value;
    }

    function clearDialpad() {
        document.getElementById('barcodeSearch').value = '';
    }

    function submitDialpad() {
        manualBarcodeSearch();
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