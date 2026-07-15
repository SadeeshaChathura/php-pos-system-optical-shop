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
            <div class="container-fluid p-0 pos-container pos-fullscreen">

                <div class="pos-exit-bar">
                    <div class="pos-brand"><i class="fas fa-cash-register"></i> Point of Sale</div>
                    <div class="pos-exit-bar-mid">
                        <button class="pos-topbar-btn" id="btnPrevBills"><i class="fas fa-receipt"></i> Previous Bills</button>
                        <button class="pos-topbar-btn" id="btnHeldBills"><i class="fas fa-pause"></i> Held Bills <span class="pos-topbar-badge">0</span></button>
                        <button class="pos-topbar-btn" id="btnReturns"><i class="fas fa-undo"></i> Returns</button>
                    </div>
                    <a class="pos-exit-btn" href="<?php echo base_url().'Welcome/Dashboard'; ?>">
                        <i class="fas fa-th"></i> Exit to Dashboard
                    </a>
                </div>

                <div class="pos-app pos-fullscreen-app">

                    <!-- LEFT: PRODUCTS -->
                    <div class="pos-left">
                        <div class="pos-search-row">
                            <div class="pos-search-box">
                                <i class="fas fa-search"></i>
                                <input type="text" id="productSearch" placeholder="Search by product name or code…" autofocus>
                            </div>
                            <div class="pos-barcode-box">
                                <i class="fas fa-barcode"></i>
                                <input type="text" id="barcodeInput" placeholder="Scan or type barcode, then Enter">
                            </div>
                            <button class="pos-btn-icon" id="btnBrowseProducts" title="Browse all products">
                                <i class="fas fa-th-large"></i>
                            </button>
                        </div>

                        <div class="pos-category-row" id="categoryRow">
                            <div class="pos-cat-chip active" data-cat=""><i class="fas fa-th"></i> All Items</div>
                            <!-- category chips injected by JS from Getcategorylist -->
                        </div>

                        <div class="pos-filter-row">
                            <div class="pos-stock-filters">
                                <button class="pos-sf-btn active" data-stock="">All Stock</button>
                                <button class="pos-sf-btn" data-stock="in">In Stock</button>
                                <button class="pos-sf-btn" data-stock="low">Low Stock</button>
                                <button class="pos-sf-btn" data-stock="out">Out of Stock</button>
                            </div>
                        </div>

                        <div class="pos-product-area">
                            <div class="pos-product-grid" id="productGrid">
                                <!-- AJAX rendered -->
                            </div>
                        </div>
                    </div>

                    <!-- RIGHT: CART -->
                    <div class="pos-right">
                        <div class="pos-customer-strip">
                            <div class="pos-customer-row">
                                <div class="pos-customer-select" id="customerSelectBtn">
                                    <div class="av" id="custAvatar">CC</div>
                                    <div class="info">
                                        <div class="cname" id="custName">Cash Customer</div>
                                        <div class="ctag" id="custTag">Walk-in · No account</div>
                                    </div>
                                    <i class="fas fa-chevron-down" style="color:var(--muted2);font-size:12px;"></i>
                                </div>
                                <button class="pos-btn-add-customer" id="btnNewCustomer" title="Add new customer">
                                    <i class="fas fa-user-plus"></i>
                                </button>
                            </div>
                        </div>

                        <div class="pos-cart-head">
                            <h3><i class="fas fa-shopping-cart"></i> Current Sale <span class="count" id="cartCount">0</span></h3>
                            <button class="pos-clear-btn" id="btnClearCart"><i class="fas fa-trash-alt"></i> Clear</button>
                        </div>

                        <div class="pos-cart-list" id="cartList">
                            <div class="pos-cart-empty" id="cartEmptyState">
                                <i class="fas fa-shopping-cart"></i>
                                <p><b>Cart is empty</b></p>
                                <span>Scan a barcode or tap a product to begin</span>
                            </div>
                        </div>

                        <div class="pos-cart-summary">
                            <div class="pos-sum-row"><span>Items (<span id="sumItemCount">0</span>)</span><b id="sumSubtotal">Rs. 0.00</b></div>
                            <div class="pos-sum-row discount-row"><span>Discounts</span><b id="sumLineDiscount">- Rs. 0.00</b></div>

                            <div class="pos-discount-input-row">
                                <select id="billDiscType" class="pos-form-control">
                                    <option value="pct">% Off</option>
                                    <option value="amt">Rs. Off</option>
                                </select>
                                <input type="number" id="billDiscValue" placeholder="Bill discount" value="0" min="0">
                            </div>

                            <div class="pos-sum-total">
                                <span class="lbl">Net Total</span>
                                <span class="val" id="grandTotal">Rs. 0.00</span>
                            </div>

                            <div class="pos-pay-buttons">
                                <button class="pos-pay-btn cash" id="btnPayCash" disabled><i class="fas fa-money-bill-wave"></i> Cash</button>
                                <button class="pos-pay-btn card" id="btnPayCard" disabled><i class="fas fa-credit-card"></i> Card</button>
                                <button class="pos-pay-btn credit" id="btnPayCredit" disabled><i class="fas fa-file-invoice"></i> Sell on Credit</button>
                            </div>
                            <div style="text-align:center;margin-top:10px;font-size:11px;color:var(--muted2);">
                                <span class="pos-kbd">F2</span> Browse &nbsp; <span class="pos-kbd">F4</span> Cash &nbsp; <span class="pos-kbd">Esc</span> Close
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ===================== Hidden form fields kept for compatibility ===================== -->
                <input type="hidden" id="hiddenmaterialID" name="hiddenmaterialID">
                <input type="hidden" id="locationID" name="locationID">
                <input type="hidden" id="priceeditstatus" value="0">
                <input type="hidden" id="hideapproveuser" value="0">
                <input type="hidden" id="hidewarrantystatus" value="0">

                <!-- Hidden cart table — data source for submitSale() -->
                <table class="table d-none" id="carttable">
                    <thead>
                        <tr>
                            <th>PRODUCT</th><th>QTY</th><th>SALE</th><th>DISCOUNT</th><th>TOTAL</th>
                            <th>productid</th><th>productcode</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>

                <!-- Hidden payment table — data source for the payment split list -->
                <table class="table d-none" id="paymenttable">
                    <thead>
                        <tr><th>method</th><th>bank_or_cardtype</th><th>branch</th><th>chequeno_or_last4</th><th>chequedate</th><th>amount</th></tr>
                    </thead>
                    <tbody></tbody>
                </table>

            </div>
        </main>
        <?php include "include/footerbar.php"; ?>
    </div>
</div>

<!-- ===================== MODAL: CUSTOMER SELECT ===================== -->
<div class="pos-modal-overlay" id="modalCustomerSelect">
    <div class="pos-modal-box wide">
        <div class="pos-modal-header">
            <h3><i class="fas fa-users"></i> Select Customer</h3>
            <button onclick="closePosModal('modalCustomerSelect')"><i class="fas fa-times"></i></button>
        </div>
        <div class="pos-modal-body">
            <div class="pos-cash-customer-row" id="pickCashCustomer">
                <i class="fas fa-money-bill-wave"></i>
                <div><b>Continue as Cash Customer</b><br><span style="font-size:11px;color:var(--muted2);">Default for walk-ins</span></div>
            </div>
            <div class="pos-cust-search-wrap">
                <i class="fas fa-search"></i>
                <input type="text" class="pos-form-control" id="custSearchInput" placeholder="Search by name, mobile, or customer code…">
            </div>
            <div class="pos-cust-list" id="custListBody"></div>
        </div>
        <div class="pos-modal-footer">
            <button class="pos-btn pos-btn-secondary" onclick="closePosModal('modalCustomerSelect')">Cancel</button>
            <button class="pos-btn pos-btn-primary" id="btnOpenAddFromSelect"><i class="fas fa-user-plus"></i> New Customer</button>
        </div>
    </div>
</div>

<!-- ===================== MODAL: NEW CUSTOMER (QUICK ADD) ===================== -->
<div class="pos-modal-overlay" id="modalNewCustomer">
    <div class="pos-modal-box">
        <div class="pos-modal-header">
            <h3><i class="fas fa-user-plus"></i> Add New Customer</h3>
            <button onclick="closePosModal('modalNewCustomer')"><i class="fas fa-times"></i></button>
        </div>
        <div class="pos-modal-body">
            <p style="font-size:12px;color:var(--muted2);margin-top:0;">Quick add for POS sales — name and contact only.</p>
            <div class="pos-form-group">
                <label>Full Name *</label>
                <input type="text" class="pos-form-control" id="newCustName" placeholder="e.g. Nimal Perera">
            </div>
            <div class="pos-form-group">
                <label>Mobile Number *</label>
                <input type="tel" class="pos-form-control" id="newCustMobile" placeholder="07XXXXXXXX">
            </div>
            <div id="newCustError" style="color:var(--danger);font-size:12px;display:none;margin-top:-6px;margin-bottom:10px;"></div>
        </div>
        <div class="pos-modal-footer">
            <button class="pos-btn pos-btn-secondary" onclick="closePosModal('modalNewCustomer')">Cancel</button>
            <button class="pos-btn pos-btn-primary" id="btnSaveNewCustomer"><i class="fas fa-check"></i> Save &amp; Select</button>
        </div>
    </div>
</div>

<!-- ===================== MODAL: BATCH PICKER ===================== -->
<div class="pos-modal-overlay" id="modalBatchPicker">
    <div class="pos-modal-box">
        <div class="pos-modal-header">
            <h3><i class="fas fa-layer-group"></i> Select Batch</h3>
            <button onclick="closePosModal('modalBatchPicker')"><i class="fas fa-times"></i></button>
        </div>
        <div class="pos-modal-body">
            <div class="pos-bill-summary-box" style="margin-bottom:14px;">
                <div class="lbl" id="batchProductName">Product Name</div>
                <div style="font-size:11px;color:var(--muted2);margin-top:2px;" id="batchProductCode"></div>
            </div>
            <div id="batchListBody" style="display:flex;flex-direction:column;gap:8px;max-height:400px;overflow-y:auto;padding-right:2px;"></div>
            <div id="batchNoneMsg" style="display:none;text-align:center;padding:20px;color:var(--muted2);font-size:13px;">
                <i class="fas fa-box-open" style="font-size:26px;margin-bottom:8px;display:block;"></i>
                No stock available for this product.
            </div>
        </div>
        <div class="pos-modal-footer">
            <button class="pos-btn pos-btn-secondary" onclick="closePosModal('modalBatchPicker')">Cancel</button>
        </div>
    </div>
</div>

<!-- ===================== MODAL: ADD TO CART (QTY/PRICE/DISCOUNT) ===================== -->
<div class="pos-modal-overlay" id="modalAddToCart">
    <div class="pos-modal-box">
        <div class="pos-modal-header">
            <h3><i class="fas fa-cart-plus"></i> Add to Sale</h3>
            <button onclick="closePosModal('modalAddToCart')"><i class="fas fa-times"></i></button>
        </div>
        <div class="pos-modal-body">
            <div class="pos-bill-summary-box" style="margin-bottom:16px;">
                <div class="lbl" id="addProductName">Product Name</div>
                <div style="font-size:11px;color:var(--muted2);margin-top:2px;" id="addProductMeta">CODE-001 · Stock: 0</div>
                <div style="font-size:11px;color:var(--muted2);margin-top:2px;" id="addBatchMeta"></div>
            </div>
            <div class="pos-form-row">
                <div class="pos-form-group">
                    <label>Unit Price (Rs.)</label>
                    <input type="number" class="pos-form-control" id="addUnitPrice" min="0">
                </div>
                <div class="pos-form-group">
                    <label>Quantity</label>
                    <input type="number" class="pos-form-control" id="addQty" value="1" min="1">
                </div>
            </div>
            <div class="pos-form-group">
                <label>Line Discount (%)</label>
                <input type="number" class="pos-form-control" id="addDiscount" value="0" min="0" max="100">
            </div>
            <div style="background:var(--accent-light);border-radius:9px;padding:10px 12px;display:flex;justify-content:space-between;font-weight:700;color:var(--primary-dark);">
                <span>Line Total</span><span id="addLineTotal">Rs. 0.00</span>
            </div>
            <input type="hidden" id="hideproductid">
            <input type="hidden" id="hideproduct">
            <input type="hidden" id="hideproductcode">
            <input type="hidden" id="hideproductstock">
        </div>
        <div class="pos-modal-footer">
            <button class="pos-btn pos-btn-secondary" onclick="closePosModal('modalAddToCart')">Cancel</button>
            <button class="pos-btn pos-btn-primary" id="btnConfirmAddToCart"><i class="fas fa-check"></i> Add to Cart</button>
        </div>
    </div>
</div>

<!-- ===================== MODAL: BROWSE PRODUCTS ===================== -->
<div class="pos-modal-overlay" id="modalBrowse">
    <div class="pos-modal-box xwide">
        <div class="pos-modal-header">
            <h3><i class="fas fa-list-alt"></i> Browse Products</h3>
            <button onclick="closePosModal('modalBrowse')"><i class="fas fa-times"></i></button>
        </div>
        <div class="pos-modal-body">
            <div class="pos-bills-toolbar">
                <input type="text" class="pos-form-control" id="browseSearch" placeholder="Search name, code or barcode…">
                <select class="pos-form-control" id="browseCategory" style="max-width:200px;">
                    <option value="">All Categories</option>
                </select>
                <select class="pos-form-control" id="browseStock" style="max-width:170px;">
                    <option value="">All Stock</option>
                    <option value="in">In Stock</option>
                    <option value="low">Low Stock</option>
                    <option value="out">Out of Stock</option>
                </select>
            </div>
            <table class="table table-bordered table-striped table-sm" id="tableBrowseProducts">
                <thead>
                    <tr><th>Item</th><th>Code</th><th>Category</th><th>Stock</th><th class="text-right">Price</th><th></th></tr>
                </thead>
                <tbody id="browseTableBody"></tbody>
            </table>
        </div>
    </div>
</div>

<!-- ===================== MODAL: CASH PAYMENT ===================== -->
<div class="pos-modal-overlay" id="modalPayCash">
    <div class="pos-modal-box">
        <div class="pos-modal-header">
            <h3><i class="fas fa-money-bill-wave"></i> Cash Payment</h3>
            <button onclick="closePosModal('modalPayCash')"><i class="fas fa-times"></i></button>
        </div>
        <div class="pos-modal-body">
            <div class="pos-bill-summary-box">
                <div class="lbl">Amount Due</div>
                <div class="amt" id="cashDueAmt">Rs. 0.00</div>
            </div>
            <div class="pos-form-group">
                <label>Cash Received</label>
                <input type="number" class="pos-form-control" id="cashReceived" placeholder="0.00" style="font-size:18px;font-weight:700;">
                <div class="pos-quick-cash-row">
                    <button class="pos-quick-cash-btn" data-amt="exact">Exact</button>
                    <button class="pos-quick-cash-btn" data-amt="500">+500</button>
                    <button class="pos-quick-cash-btn" data-amt="1000">+1000</button>
                    <button class="pos-quick-cash-btn" data-amt="5000">+5000</button>
                </div>
            </div>
            <div class="pos-balance-line" id="cashBalanceLine"><span>Change Due</span><span id="cashChangeAmt">Rs. 0.00</span></div>
            <div style="display:flex;align-items:center;gap:8px;margin-top:14px;padding:10px 12px;background:var(--accent-light);border-radius:9px;">
                <input type="checkbox" id="cashWarrantyCheck" style="width:16px;height:16px;cursor:pointer;">
                <label for="cashWarrantyCheck" style="margin:0;font-size:12.5px;font-weight:600;color:var(--primary-dark);cursor:pointer;">
                    Add Lifetime Warranty to this invoice
                </label>
            </div>
        </div>
        <div class="pos-modal-footer">
            <button class="pos-btn pos-btn-secondary" onclick="closePosModal('modalPayCash')">Cancel</button>
            <button class="pos-btn pos-btn-success" id="btnCompleteCash"><i class="fas fa-check"></i> Complete Sale</button>
        </div>
    </div>
</div>

<!-- ===================== MODAL: CARD PAYMENT ===================== -->
<div class="pos-modal-overlay" id="modalPayCard">
    <div class="pos-modal-box">
        <div class="pos-modal-header">
            <h3><i class="fas fa-credit-card"></i> Card Payment</h3>
            <button onclick="closePosModal('modalPayCard')"><i class="fas fa-times"></i></button>
        </div>
        <div class="pos-modal-body">
            <div class="pos-bill-summary-box">
                <div class="lbl">Amount to Charge</div>
                <div class="amt" id="cardDueAmt">Rs. 0.00</div>
            </div>
            <div class="pos-form-row">
                <div class="pos-form-group" style="flex:1.4;">
                    <label>Card Type</label>
                    <select class="pos-form-control" id="cardType">
                        <option>Visa</option>
                        <option>MasterCard</option>
                        <option>Amex</option>
                        <option>Other</option>
                    </select>
                </div>
                <div class="pos-form-group">
                    <label>Last 4 Digits</label>
                    <input type="text" class="pos-form-control" id="cardLast4" maxlength="4" placeholder="1234" style="font-weight:700;letter-spacing:2px;">
                </div>
            </div>
            <div style="display:flex;align-items:center;gap:8px;margin-top:6px;padding:10px 12px;background:var(--accent-light);border-radius:9px;">
                <input type="checkbox" id="cardWarrantyCheck" style="width:16px;height:16px;cursor:pointer;">
                <label for="cardWarrantyCheck" style="margin:0;font-size:12.5px;font-weight:600;color:var(--primary-dark);cursor:pointer;">
                    Add Lifetime Warranty to this invoice
                </label>
            </div>
        </div>
        <div class="pos-modal-footer">
            <button class="pos-btn pos-btn-secondary" onclick="closePosModal('modalPayCard')">Cancel</button>
            <button class="pos-btn pos-btn-success" id="btnCompleteCard"><i class="fas fa-check"></i> Complete Sale</button>
        </div>
    </div>
</div>

<!-- ===================== MODAL: CREDIT — BLOCKED (Cash Customer) ===================== -->
<div class="pos-modal-overlay" id="modalCreditBlocked">
    <div class="pos-modal-box">
        <div class="pos-modal-header">
            <h3><i class="fas fa-file-invoice"></i> Sell on Credit</h3>
            <button onclick="closePosModal('modalCreditBlocked')"><i class="fas fa-times"></i></button>
        </div>
        <div class="pos-modal-body">
            <div style="text-align:center;padding:20px 10px;">
                <i class="fas fa-exclamation-triangle" style="font-size:34px;color:var(--warning);"></i>
                <p style="font-weight:700;margin:14px 0 4px;">A saved customer is required for credit sales</p>
                <p style="font-size:12.5px;color:var(--muted2);margin:0;">Cash Customer can't be used for credit. Select an existing customer or add a new one to continue.</p>
            </div>
        </div>
        <div class="pos-modal-footer">
            <button class="pos-btn pos-btn-secondary" onclick="closePosModal('modalCreditBlocked')">Cancel</button>
            <button class="pos-btn pos-btn-primary" id="btnCreditSelectCustomer"><i class="fas fa-address-book"></i> Select Customer</button>
        </div>
    </div>
</div>

<!-- ===================== MODAL: CREDIT CONFIRM ===================== -->
<div class="pos-modal-overlay" id="modalCreditConfirm">
    <div class="pos-modal-box">
        <div class="pos-modal-header">
            <h3><i class="fas fa-file-invoice"></i> Confirm Credit Sale</h3>
            <button onclick="closePosModal('modalCreditConfirm')"><i class="fas fa-times"></i></button>
        </div>
        <div class="pos-modal-body">
            <div class="pos-cust-row" style="display:flex;align-items:center;gap:10px;border:1px solid var(--border);border-radius:10px;padding:10px;margin-bottom:14px;">
                <div class="av" style="width:34px;height:34px;border-radius:50%;background:var(--primary);color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:12px;">--</div>
                <div><b id="creditCustName">—</b><br><span style="font-size:11.5px;color:var(--muted2);" id="creditCustMeta">—</span></div>
            </div>
            <div class="pos-bill-summary-box">
                <div class="lbl">Total Credit Amount</div>
                <div class="amt" id="creditDueAmt">Rs. 0.00</div>
            </div>
            <div class="pos-form-group">
                <label>Advance Payment Now (optional)</label>
                <input type="number" class="pos-form-control" id="creditAdvance" placeholder="0.00">
            </div>
            <div style="display:flex;align-items:center;gap:8px;margin-top:6px;padding:10px 12px;background:var(--accent-light);border-radius:9px;">
                <input type="checkbox" id="creditWarrantyCheck" style="width:16px;height:16px;cursor:pointer;">
                <label for="creditWarrantyCheck" style="margin:0;font-size:12.5px;font-weight:600;color:var(--primary-dark);cursor:pointer;">
                    Add Lifetime Warranty to this invoice
                </label>
            </div>
        </div>
        <div class="pos-modal-footer">
            <button class="pos-btn pos-btn-secondary" onclick="closePosModal('modalCreditConfirm')">Cancel</button>
            <button class="pos-btn pos-btn-success" id="btnCompleteCredit"><i class="fas fa-check"></i> Confirm Credit Sale</button>
        </div>
    </div>
</div>

<!-- ===================== MODAL: PREVIOUS BILLS ===================== -->
<div class="pos-modal-overlay" id="modalPrevBills">
    <div class="pos-modal-box xwide">
        <div class="pos-modal-header">
            <h3><i class="fas fa-receipt"></i> Previous Bills</h3>
            <button onclick="closePosModal('modalPrevBills')"><i class="fas fa-times"></i></button>
        </div>
        <div class="pos-modal-body">
            <div class="pos-bills-toolbar">
                <input type="date" class="pos-form-control" id="prevBillsDateFrom" style="max-width:160px;">
                <input type="date" class="pos-form-control" id="prevBillsDateTo" style="max-width:160px;">
                <select class="pos-form-control" id="prevBillsType" style="max-width:160px;">
                    <option value="">All Types</option>
                    <option value="1">Cash</option>
                    <option value="2">Card</option>
                    <option value="3">Credit</option>
                </select>
                <button class="pos-btn pos-btn-primary" id="btnApplyBillsFilter"><i class="fas fa-filter"></i> Apply</button>
            </div>
            <table class="table table-bordered table-striped table-sm w-100" id="tablePrevBills">
                <thead>
                    <tr>
                        <th>Invoice #</th><th>Date / Time</th><th>Customer</th><th>Mobile</th>
                        <th class="text-right">Gross</th><th class="text-right">Discount</th><th class="text-right">Net Total</th>
                        <th>Status</th><th></th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<!-- ===================== MODAL: RECEIPT / SALE COMPLETE ===================== -->
<div class="pos-modal-overlay" id="modalReceipt">
    <div class="pos-modal-box">
        <div class="pos-modal-header">
            <h3><i class="fas fa-check-circle" style="color:var(--success);"></i> Sale Completed</h3>
            <button onclick="closePosModalAndReload()"><i class="fas fa-times"></i></button>
        </div>
        <div class="pos-modal-body" style="text-align:center;">
            <div style="background:var(--success-bg);border-radius:14px;padding:24px;margin-bottom:16px;">
                <i class="fas fa-check-circle" style="font-size:46px;color:var(--success);"></i>
                <div style="font-weight:800;font-size:18px;margin-top:10px;color:var(--success);">Payment Successful</div>
                <div style="font-size:12.5px;color:var(--muted2);margin-top:4px;">Invoice <b id="receiptInvNo">—</b> · <span id="receiptMethod">—</span></div>
            </div>
        </div>
        <div class="pos-modal-footer">
            <button class="pos-btn pos-btn-primary pos-btn-block" onclick="closePosModalAndReload()"><i class="fas fa-plus"></i> Start New Sale</button>
        </div>
    </div>
</div>

<?php include "include/footerscripts.php"; ?>

<script type="text/javascript">
/* =========================================================
   STATE
   ========================================================= */
var cart = [];
var selectedCustomer = { id: 1, name: 'Cash Customer', mobile: '', code: 'CASH', isCash: true };
var activeCategory   = '';
var activeStockFilter = '';
var pendingProduct   = null;
var creditFlowPending = false;
var lastInsertedInvoiceID = null;
var lastBillType = 1;

var BASE_URL = '<?php echo base_url() ?>';

/* =========================================================
   HELPERS
   ========================================================= */
function money(n){ return 'Rs. ' + Number(n||0).toLocaleString('en-US',{minimumFractionDigits:2,maximumFractionDigits:2}); }
function parseMoney(str){
    var cleaned = String(str || '').replace(/^Rs\.?\s*/i, '');
    return parseFloat(cleaned.replace(/[^0-9.]/g,'')) || 0;
}
function initials(name){ return (name||'').split(' ').filter(Boolean).slice(0,2).map(function(w){return w[0].toUpperCase();}).join(''); }
function openPosModal(id){ document.getElementById(id).classList.add('show'); }
function closePosModal(id){ document.getElementById(id).classList.remove('show'); }
function closePosModalAndReload(){ location.reload(); }

function posToast(icon, title, message, type){
    if (typeof $.notify === 'function') {
        $.notify({icon: icon, title: title, message: message}, {
            type: type, allow_dismiss: true, placement:{from:'top',align:'center'}, offset:100, delay:5000, timer:1000,
            template:'<div data-notify="container" class="col-xs-11 col-sm-3 alert alert-{0}" role="alert">' +
                '<button type="button" aria-hidden="true" class="close" data-notify="dismiss">&times;</button>' +
                '<span data-notify="icon"></span> <span data-notify="title">{1}</span> <span data-notify="message">{2}</span></div>'
        });
    } else {
        alert(title + ': ' + message);
    }
}

function escapeHtml(s){
    return String(s || '').replace(/[&<>"']/g, function(m){
        return ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'})[m];
    });
}

function debounce(fn, delay){
    var t;
    return function(){
        var args = arguments;
        clearTimeout(t);
        t = setTimeout(function(){ fn.apply(null, args); }, delay);
    };
}

/* =========================================================
   CATEGORY CHIPS
   ========================================================= */
function loadCategories(){
    $.post(BASE_URL + 'Directsale/Getcategorylist', {}, function(result){
        var cats = JSON.parse(result);
        var html = '';
        cats.forEach(function(c){
            html += '<div class="pos-cat-chip" data-cat="' + c.idtbl_material_category + '"><i class="fas fa-tag"></i> ' + c.categoryname + '</div>';
        });
        $('#categoryRow').append(html);

        $('.pos-cat-chip').off('click').on('click', function(){
            $('.pos-cat-chip').removeClass('active');
            $(this).addClass('active');
            activeCategory = $(this).data('cat') || '';
            loadProducts();
        });
    });
}

/* =========================================================
   PRODUCT GRID
   ========================================================= */
function stockInfo(stock){
    stock = parseFloat(stock) || 0;
    if (stock <= 0)  return {cls:'out', label:'Out of Stock'};
    if (stock <= 5)  return {cls:'low', label:'Low: ' + stock};
    return {cls:'in', label:'In Stock'};
}

function loadProducts(){
    var searchTerm = $('#productSearch').val();
    $.post(BASE_URL + 'Directsale/Getproductlist', {
        categoryID:  activeCategory,
        searchTerm:  searchTerm,
        stockFilter: activeStockFilter
    }, function(result){
        var products;
        try { products = JSON.parse(result); } catch(e){ products = []; }
        renderProductGrid(products);
    });
}

function renderProductGrid(products){
    var grid = $('#productGrid');

    if (!products || products.length === 0) {
        grid.html('<div style="grid-column:1/-1;display:flex;flex-direction:column;align-items:center;justify-content:center;text-align:center;min-height:calc(100vh - 320px);color:var(--muted2);">' +
            '<i class="fas fa-box-open" style="font-size:36px;margin-bottom:10px;color:var(--border);"></i>No products found</div>');
        return;
    }

    var html = '';
    products.forEach(function(p){
        var s = stockInfo(p.stock);
        html += '<div class="pos-pcard ' + (p.stock <= 0 ? 'out' : '') + '" ' +
            'data-id="'    + p.id              + '" ' +
            'data-name="'  + escapeHtml(p.name)  + '" ' +
            'data-code="'  + escapeHtml(p.code)  + '" ' +
            'data-price="' + p.price            + '" ' +
            'data-stock="' + p.stock            + '">' +
            '<div class="icon-wrap"><i class="fas fa-cube"></i></div>' +
            '<div class="pname" title="' + escapeHtml(p.name) + '">' + escapeHtml(p.name) + '</div>' +
            '<div class="pcode">' + escapeHtml(p.code) + '</div>' +
            '<div class="pfoot">' +
              '<span class="pprice">' + money(p.price) + '</span>' +
              '<span class="pos-stockbadge ' + s.cls + '">' + s.label + '</span>' +
            '</div></div>';
    });
    grid.html(html);

    /* ── CHANGED: product card click now opens batch picker first ── */
    $('.pos-pcard').off('click').on('click', function(){
        var stock = parseFloat($(this).data('stock')) || 0;
        if (stock <= 0) { posToast('fas fa-exclamation-triangle', '', 'Product is out of stock', 'danger'); return; }
        openBatchPicker({
            id:    $(this).data('id'),
            name:  $(this).data('name'),
            code:  $(this).data('code'),
            price: $(this).data('price'),
            stock: stock
        });
    });
}

$('#productSearch').on('input', debounce(loadProducts, 300));

$('.pos-sf-btn').on('click', function(){
    $('.pos-sf-btn').removeClass('active');
    $(this).addClass('active');
    activeStockFilter = $(this).data('stock') || '';
    loadProducts();
});

/* =========================================================
   BARCODE SCAN
   ========================================================= */
$('#barcodeInput').on('keypress', function(e){
    if (e.which === 13) {
        e.preventDefault();
        var code = $(this).val().trim();
        if (!code) return;

        $.post(BASE_URL + 'Directsale/Getproductlistaccobarcode', { barcode: code }, function(result){
            var obj = JSON.parse(result);
            if (!obj.found) {
                posToast('fas fa-exclamation-triangle', '', 'No product matches barcode "' + code + '"', 'danger');
                return;
            }
            if (parseFloat(obj.stock) <= 0) {
                posToast('fas fa-exclamation-triangle', '', obj.productname + ' is out of stock', 'danger');
                return;
            }
            /* ── CHANGED: barcode scan also opens batch picker first ── */
            openBatchPicker({ id: obj.id, name: obj.productname, code: obj.productcode, price: obj.price, stock: obj.stock });
        });
        $(this).val('');
    }
});

/* =========================================================
   BATCH PICKER
   Opens a modal listing every batch with qty > 0.
   Clicking a batch pre-fills openAddToCart() with that
   batch's specific price and available qty.
   ========================================================= */
    function openBatchPicker(product){
        pendingProduct = product;
        $('#batchProductName').text(product.name);
        $('#batchProductCode').text(product.code);
        $('#batchListBody').html(
            '<div style="text-align:center;padding:20px;color:var(--muted2);">' +
            '<i class="fas fa-spinner fa-spin"></i> Loading batches…</div>'
        );
        $('#batchNoneMsg').hide();
        openPosModal('modalBatchPicker');

        $.post(BASE_URL + 'Directsale/Getproductbatches', { productID: product.id }, function(result){
            var batches;
            try { batches = JSON.parse(result); } catch(e){ batches = []; }

            if (!batches.length) {
                $('#batchListBody').empty();
                $('#batchNoneMsg').show();
                return;
            }

            var html = '';
            batches.forEach(function(b, idx){
                var dateStr  = b.date ? b.date.substring(0, 10) : '—';
                var stockColor = b.qty <= 0 ? '#e74c3c' : (b.qty <= 5 ? '#f39c12' : '#27ae60');
                var stockBg    = b.qty <= 0 ? '#fdecea' : (b.qty <= 5 ? '#fef9e7' : '#eafaf1');
                var stockLbl   = b.qty <= 0 ? 'Out of Stock' : (b.qty <= 5 ? 'Low Stock' : 'In Stock');

                html +=
                    '<div class="pos-batch-card" data-idx="' + idx + '" style="' +
                        'display:flex;align-items:center;justify-content:space-between;' +
                        'background:var(--surface-2);' +
                        'border:1.5px solid var(--border);' +
                        'border-left:4px solid var(--primary);' +
                        'border-radius:10px;' +
                        'padding:12px 16px;' +
                        'cursor:pointer;' +
                        'gap:12px;' +
                        'transition:background 0.15s,box-shadow 0.15s;"' +
                    ' onmouseover="this.style.background=\'var(--accent-light)\';this.style.boxShadow=\'0 2px 10px rgba(0,0,0,0.08)\'"' +
                    ' onmouseout="this.style.background=\'var(--surface-2)\';this.style.boxShadow=\'none\'">' +

                    /* batch number + date */
                    '<div style="flex:1;min-width:0;">' +
                    '<div style="font-weight:700;font-size:13.5px;color:var(--primary-dark);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">' +
                        '<i class="fas fa-tag" style="margin-right:6px;font-size:11px;opacity:.7;"></i>' + escapeHtml(b.batchno || 'N/A') +
                    '</div>' +
                    '<div style="font-size:11px;color:var(--muted2);margin-top:3px;">' +
                        '<i class="fas fa-calendar-alt" style="margin-right:4px;font-size:10px;"></i>' + dateStr +
                    '</div>' +
                    '</div>' +

                    /* qty */
                    '<div style="text-align:center;min-width:54px;">' +
                    '<div style="font-size:10px;color:var(--muted2);text-transform:uppercase;letter-spacing:.6px;font-weight:600;">Qty</div>' +
                    '<div style="font-size:16px;font-weight:800;color:var(--primary-dark);margin-top:1px;">' + b.qty + '</div>' +
                    '</div>' +

                    /* stock badge */
                    '<div style="text-align:center;min-width:70px;">' +
                    '<span style="font-size:11px;font-weight:700;padding:3px 10px;border-radius:20px;' +
                        'background:' + stockBg + ';color:' + stockColor + ';">' + stockLbl + '</span>' +
                    '</div>' +

                    /* price */
                    '<div style="text-align:right;min-width:90px;">' +
                    '<div style="font-size:10px;color:var(--muted2);text-transform:uppercase;letter-spacing:.6px;font-weight:600;">Price</div>' +
                    '<div style="font-size:16px;font-weight:800;color:var(--success);margin-top:1px;">' + money(b.saleprice) + '</div>' +
                    '</div>' +

                    '</div>';
            });
            html += '</div>';

            $('#batchListBody').html(html);
            $('#batchListBody').data('batches', batches);

            $('.pos-batch-card').on('click', function(){
                var idx = $(this).data('idx');
                var b   = $('#batchListBody').data('batches')[idx];
                closePosModal('modalBatchPicker');
                openAddToCart({
                    id:      pendingProduct.id,
                    name:    pendingProduct.name,
                    code:    pendingProduct.code,
                    price:   b.saleprice,
                    stock:   b.qty,
                    batchno: b.batchno
                });
            });
        });
    }

/* =========================================================
   ADD TO CART MODAL
   ========================================================= */
function openAddToCart(product){
    pendingProduct = product;
    $('#addProductName').text(product.name);
    $('#addProductMeta').text(product.code + ' · Stock: ' + product.stock);
    /* show batch info if we came from batch picker */
    if (product.batchno) {
        $('#addBatchMeta').text('Batch: ' + product.batchno).show();
    } else {
        $('#addBatchMeta').hide();
    }
    $('#addUnitPrice').val(product.price);
    $('#addQty').val(1);
    $('#addDiscount').val(0);
    updateAddLineTotal();
    openPosModal('modalAddToCart');
    setTimeout(function(){ $('#addQty').focus().select(); }, 150);
}

function updateAddLineTotal(){
    var price = parseFloat($('#addUnitPrice').val()) || 0;
    var qty   = parseFloat($('#addQty').val())       || 0;
    var disc  = parseFloat($('#addDiscount').val())  || 0;
    var gross = price * qty;
    var total = gross - (gross * disc / 100);
    $('#addLineTotal').text(money(total));
}
$('#addUnitPrice, #addQty, #addDiscount').on('input', updateAddLineTotal);

$('#btnConfirmAddToCart').on('click', function(){
    var price = parseFloat($('#addUnitPrice').val()) || 0;
    var qty   = parseFloat($('#addQty').val())       || 0;
    var disc  = parseFloat($('#addDiscount').val())  || 0;

    if (qty <= 0) { posToast('fas fa-exclamation-triangle', '', 'Enter a quantity of at least 1', 'danger'); return; }
    if (qty > pendingProduct.stock) {
        posToast('fas fa-exclamation-triangle', '', 'Only ' + pendingProduct.stock + ' available in stock', 'danger');
        return;
    }

    /* live server-side stock re-check before committing */
    $.post(BASE_URL + 'Directsale/Getproductavalaibleqty', { product: pendingProduct.id, qty: qty }, function(result){
        var obj = JSON.parse(result);
        if (obj.checkqty == 1) {
            posToast('fas fa-exclamation-triangle', '', 'Only ' + obj.availableqty + ' available in stock now', 'danger');
            return;
        }

        cart.push({
            productId: pendingProduct.id,
            name:      pendingProduct.name,
            code:      pendingProduct.code,
            price:     price,
            origPrice: pendingProduct.price,
            qty:       qty,
            discPct:   disc,
            stock:     pendingProduct.stock,
            batchno:   pendingProduct.batchno || ''
        });
        closePosModal('modalAddToCart');
        renderCart();
        posToast('fas fa-cart-plus', '', pendingProduct.name + ' added to cart', 'success');
    });
});

/* =========================================================
   CART RENDER
   ========================================================= */
function lineTotal(item){
    var gross = item.price * item.qty;
    return gross - (gross * item.discPct / 100);
}

function renderCart(){
    var list = $('#cartList');
    $('#cartCount').text(cart.length);

    if (cart.length === 0) {
        list.html('<div class="pos-cart-empty" id="cartEmptyState"><i class="fas fa-shopping-cart"></i><p><b>Cart is empty</b></p><span>Scan a barcode or tap a product to begin</span></div>');
        $('#carttable tbody').empty();
        updateSummary();
        return;
    }

    var html = '';
    cart.forEach(function(item, idx){
        var edited = item.price != item.origPrice;
        if (edited) { $('#priceeditstatus').val('1'); }
        html += '<div class="pos-citem ' + (edited ? 'edited' : '') + '" data-idx="' + idx + '">' +
            '<div class="pos-citem-top">' +
              '<div>' +
                '<div class="pos-citem-name">' + escapeHtml(item.name) +
                  (edited ? ' <span class="pos-disc-tag">Price Edited</span>' : '') +
                '</div>' +
                '<div class="pos-citem-code">' + escapeHtml(item.code) +
                  (item.batchno ? ' &nbsp;·&nbsp; Batch: ' + escapeHtml(item.batchno) : '') +
                '</div>' +
              '</div>' +
              '<button class="pos-citem-remove" data-idx="' + idx + '"><i class="fas fa-trash-alt"></i></button>' +
            '</div>' +
            '<div class="pos-citem-controls">' +
              '<div><span class="pos-tiny-label">Qty</span>' +
                '<div class="pos-qty-stepper">' +
                  '<button class="qty-dec" data-idx="' + idx + '">−</button>' +
                  '<input type="number" class="qty-input" data-idx="' + idx + '" value="' + item.qty + '">' +
                  '<button class="qty-inc" data-idx="' + idx + '">+</button>' +
                '</div>' +
              '</div>' +
              '<div class="pos-citem-price"><span class="pos-tiny-label">Price</span>' +
                '<input type="number" class="price-input" data-idx="' + idx + '" value="' + item.price + '"></div>' +
              '<div class="pos-citem-disc"><span class="pos-tiny-label">Disc %</span>' +
                '<input type="number" class="disc-input" data-idx="' + idx + '" value="' + item.discPct + '"></div>' +
              '<div class="pos-citem-total">' + money(lineTotal(item)) + '</div>' +
            '</div></div>';
    });
    list.html(html);

    $('.pos-citem-remove').on('click', function(){ cart.splice($(this).data('idx'), 1); renderCart(); });
    $('.qty-inc').on('click', function(){
        var i = $(this).data('idx');
        if (cart[i].qty < cart[i].stock) { cart[i].qty++; renderCart(); }
        else { posToast('fas fa-exclamation-triangle', '', 'Only ' + cart[i].stock + ' in stock', 'danger'); }
    });
    $('.qty-dec').on('click', function(){
        var i = $(this).data('idx');
        if (cart[i].qty > 1) { cart[i].qty--; renderCart(); }
    });
    $('.qty-input').on('change', function(){
        var i = $(this).data('idx');
        var v = parseFloat($(this).val()) || 1;
        if (v > cart[i].stock) { v = cart[i].stock; posToast('fas fa-exclamation-triangle', '', 'Only ' + cart[i].stock + ' in stock', 'danger'); }
        cart[i].qty = Math.max(1, v);
        renderCart();
    });
    $('.price-input').on('change', function(){ cart[$(this).data('idx')].price = parseFloat($(this).val()) || 0; renderCart(); });
    $('.disc-input').on('change', function(){ cart[$(this).data('idx')].discPct = Math.min(100, Math.max(0, parseFloat($(this).val()) || 0)); renderCart(); });

    syncHiddenCartTable();
    updateSummary();
}

function syncHiddenCartTable(){
    var tbody = $('#carttable tbody');
    tbody.empty();

    cart.forEach(function(item){
        var total          = item.price * item.qty;
        var discountamount = total * item.discPct / 100;
        var netline        = total - discountamount;

        tbody.append('<tr>' +
            '<td>' + escapeHtml(item.name) + '</td>' +
            '<td>' + item.qty + '</td>' +
            '<td>' + item.price.toFixed(2) + '</td>' +
            '<td>' + discountamount.toFixed(2) + '</td>' +
            '<td>' + netline.toFixed(2) + '</td>' +
            '<td>' + item.productId + '</td>' +
            '<td>' + escapeHtml(item.code) + '</td>' +
            '<td>' + escapeHtml(item.batchno || '') + '</td>' +   // ✅ ADD THIS
            '</tr>');
    });
}

function billDiscountAmount(subtotalAfterLineDisc){
    var type = $('#billDiscType').val();
    var val  = parseFloat($('#billDiscValue').val()) || 0;
    if (type === 'pct') return subtotalAfterLineDisc * val / 100;
    return Math.min(val, subtotalAfterLineDisc);
}

function updateSummary(){
    var itemCount = cart.reduce(function(s,i){ return s + i.qty; }, 0);
    var gross     = cart.reduce(function(s,i){ return s + (i.price * i.qty); }, 0);
    var lineDisc  = cart.reduce(function(s,i){ return s + (i.price * i.qty * i.discPct / 100); }, 0);
    var afterLine = gross - lineDisc;
    var billDisc  = billDiscountAmount(afterLine);
    var net       = afterLine - billDisc;

    $('#sumItemCount').text(itemCount);
    $('#sumSubtotal').text(money(gross));
    $('#sumLineDiscount').text('- ' + money(lineDisc + billDisc));
    $('#grandTotal').text(money(net));

    var hasItems = cart.length > 0;
    $('#btnPayCash, #btnPayCard, #btnPayCredit').prop('disabled', !hasItems);

    return { net: net, gross: gross, totalDiscount: lineDisc + billDisc };
}
$('#billDiscType, #billDiscValue').on('change input', updateSummary);

$('#btnClearCart').on('click', function(){
    if (cart.length === 0) return;
    if (confirm('Clear all items from the current sale?')) { cart = []; renderCart(); }
});

/* =========================================================
   CUSTOMER SELECTION
   ========================================================= */
function setCustomer(c){
    selectedCustomer = c;
    $('#custAvatar').text(c.isCash ? 'CC' : initials(c.name));
    $('#custName').text(c.name);
    $('#custTag').text(c.isCash ? 'Walk-in · No account' : (c.code + ' · ' + c.mobile));
}

function renderCustomerList(term){
    $.post(BASE_URL + 'Directsale/Getcustomersearch', { searchTerm: term || '' }, function(result){
        var list = JSON.parse(result);
        var body = $('#custListBody');

        if (!list.length) {
            body.html('<div style="padding:20px;text-align:center;color:var(--muted2);font-size:12.5px;">No matching customers</div>');
            return;
        }

        var html = '';
        list.forEach(function(c){
            html += '<div class="pos-cust-row" ' +
                'data-id="'     + c.id                   + '" ' +
                'data-name="'   + escapeHtml(c.name)     + '" ' +
                'data-mobile="' + escapeHtml(c.mobile)   + '" ' +
                'data-code="'   + escapeHtml(c.code)     + '">' +
                '<div class="av">' + initials(c.name) + '</div>' +
                '<div class="info"><b>' + escapeHtml(c.name) + '</b>' +
                '<span>' + escapeHtml(c.mobile) + ' · ' + escapeHtml(c.code) + '</span></div></div>';
        });
        body.html(html);

        $('.pos-cust-row').on('click', function(){
            var c = {
                id:     $(this).data('id'),
                name:   $(this).data('name'),
                mobile: $(this).data('mobile'),
                code:   $(this).data('code'),
                isCash: false
            };
            setCustomer(c);
            closePosModal('modalCustomerSelect');
            if (creditFlowPending) { creditFlowPending = false; openCreditConfirm(); }
            else { posToast('fas fa-check-circle', '', c.name + ' selected', 'success'); }
        });
    });
}

$('#customerSelectBtn').on('click', function(){ renderCustomerList(''); $('#custSearchInput').val(''); openPosModal('modalCustomerSelect'); });
$('#custSearchInput').on('input', debounce(function(){ renderCustomerList($(this).val()); }, 300));

$('#pickCashCustomer').on('click', function(){
    setCustomer({ id: 1, name: 'Cash Customer', mobile: '', code: 'CASH', isCash: true });
    closePosModal('modalCustomerSelect');
    creditFlowPending = false;
});

$('#btnNewCustomer').on('click', function(){ $('#newCustError').hide(); openPosModal('modalNewCustomer'); });
$('#btnOpenAddFromSelect').on('click', function(){ closePosModal('modalCustomerSelect'); $('#newCustError').hide(); openPosModal('modalNewCustomer'); });

$('#btnSaveNewCustomer').on('click', function(){
    var name   = $('#newCustName').val().trim();
    var mobile = $('#newCustMobile').val().trim();

    if (!name || !mobile) { $('#newCustError').text('Name and mobile number are required.').show(); return; }

    $.post(BASE_URL + 'Directsale/Quickaddcustomer', { name: name, contact: mobile }, function(result){
        var obj = JSON.parse(result);
        if (!obj.success) { $('#newCustError').text(obj.message || 'Could not save customer.').show(); return; }
        setCustomer({ id: obj.id, name: obj.name, mobile: obj.mobile, code: obj.code, isCash: false });
        $('#newCustName').val(''); $('#newCustMobile').val('');
        closePosModal('modalNewCustomer');
        posToast('fas fa-user-plus', '', obj.name + ' added and selected', 'success');
        if (creditFlowPending) { creditFlowPending = false; openCreditConfirm(); }
    });
});

/* =========================================================
   PAYMENT: CASH
   ========================================================= */
$('#btnPayCash').on('click', function(){
    var s = updateSummary();
    $('#cashDueAmt').text(money(s.net));
    $('#cashReceived').val('');
    $('#cashChangeAmt').text(money(0));
    $('#cashBalanceLine').removeClass('due');
    $('#cashWarrantyCheck').prop('checked', false);
    openPosModal('modalPayCash');
    setTimeout(function(){ $('#cashReceived').focus(); }, 150);
});

function recalcCashChange(){
    var net    = parseMoney($('#cashDueAmt').text());
    var recv   = parseFloat($('#cashReceived').val()) || 0;
    var change = recv - net;
    $('#cashChangeAmt').text(money(Math.abs(change)));
    $('#cashBalanceLine').toggleClass('due', change < 0);
    $('#cashBalanceLine span:first').text(change < 0 ? 'Amount Short' : 'Change Due');
}
$('#cashReceived').on('input', recalcCashChange);
$('.pos-quick-cash-btn').on('click', function(){
    var net = parseMoney($('#cashDueAmt').text());
    var amt = $(this).data('amt');
    if (amt === 'exact') { $('#cashReceived').val(net.toFixed(2)); }
    else { $('#cashReceived').val(((parseFloat($('#cashReceived').val()) || 0) + parseFloat(amt)).toFixed(2)); }
    recalcCashChange();
});

$('#btnCompleteCash').on('click', function(){
    var net  = parseMoney($('#cashDueAmt').text());
    var recv = parseFloat($('#cashReceived').val()) || 0;
    if (recv < net) { posToast('fas fa-exclamation-triangle', '', 'Cash received is less than the total due', 'danger'); return; }
    $('#hidewarrantystatus').val($('#cashWarrantyCheck').is(':checked') ? '1' : '0');
    $('#paymenttable tbody').empty().append('<tr><td>Cash</td><td></td><td></td><td></td><td></td><td>' + recv.toFixed(2) + '</td></tr>');
    closePosModal('modalPayCash');
    submitSale(1);
});

/* =========================================================
   PAYMENT: CARD
   ========================================================= */
$('#btnPayCard').on('click', function(){
    var s = updateSummary();
    $('#cardDueAmt').text(money(s.net));
    $('#cardLast4').val('');
    $('#cardWarrantyCheck').prop('checked', false);
    openPosModal('modalPayCard');
    setTimeout(function(){ $('#cardLast4').focus(); }, 150);
});
$('#cardLast4').on('input', function(){ $(this).val($(this).val().replace(/\D/g,'').slice(0,4)); });

$('#btnCompleteCard').on('click', function(){
    var net      = parseFloat($('#cardDueAmt').text().replace(/[^0-9.]/g,'')) || 0;
    var last4    = $('#cardLast4').val();
    var cardType = $('#cardType').val();
    if (last4.length !== 4) { posToast('fas fa-exclamation-triangle', '', 'Enter the last 4 digits of the card', 'danger'); return; }
    $('#hidewarrantystatus').val($('#cardWarrantyCheck').is(':checked') ? '1' : '0');
    $('#paymenttable tbody').empty().append(
        '<tr><td>Card</td><td>' + cardType + '</td><td></td><td>' + last4 + '</td><td></td><td>' + net.toFixed(2) + '</td></tr>'
    );
    closePosModal('modalPayCard');
    submitSale(2);
});

/* =========================================================
   PAYMENT: CREDIT
   ========================================================= */
$('#btnPayCredit').on('click', function(){
    if (selectedCustomer.isCash) { openPosModal('modalCreditBlocked'); }
    else { openCreditConfirm(); }
});

$('#btnCreditSelectCustomer').on('click', function(){
    closePosModal('modalCreditBlocked');
    creditFlowPending = true;
    renderCustomerList('');
    openPosModal('modalCustomerSelect');
});

function openCreditConfirm(){
    var s = updateSummary();
    $('#creditCustName').text(selectedCustomer.name);
    $('#creditCustMeta').text(selectedCustomer.mobile + ' · ' + selectedCustomer.code);
    $('#creditDueAmt').text(money(s.net));
    $('#creditAdvance').val('');
    $('#creditWarrantyCheck').prop('checked', false);
    openPosModal('modalCreditConfirm');
}

$('#btnCompleteCredit').on('click', function(){
    var adv = parseFloat($('#creditAdvance').val()) || 0;
    $('#hidewarrantystatus').val($('#creditWarrantyCheck').is(':checked') ? '1' : '0');
    $('#paymenttable tbody').empty();
    if (adv > 0) {
        $('#paymenttable tbody').append('<tr><td>Cash</td><td></td><td></td><td></td><td></td><td>' + adv.toFixed(2) + '</td></tr>');
    }
    closePosModal('modalCreditConfirm');
    submitSale(3);
});

/* =========================================================
   SUBMIT SALE
   ========================================================= */
function submitSale(billtype){
    var jsonObj = [];
    $('#carttable tbody tr').each(function(){
        var item = {};
        $(this).find('td').each(function(col_idx){ item['col_' + (col_idx + 1)] = $(this).text(); });
        jsonObj.push(item);
    });

    var jsonObjPay = [];
    $('#paymenttable tbody tr').each(function(){
        var item = {};
        $(this).find('td').each(function(col_idx){ item['col_' + (col_idx + 1)] = $(this).text(); });
        jsonObjPay.push(item);
    });

    var s = updateSummary();
    var paytotal = 0;
    jsonObjPay.forEach(function(p){ paytotal += parseFloat(p.col_6) || 0; });

    $.ajax({
        type: 'POST',
        url:  BASE_URL + 'Directsale/Directsaleinsertupdate',
        data: {
            tableData:       jsonObj,
            tableDataPay:    jsonObjPay,
            total:           s.gross,
            distotal:        s.totalDiscount,
            nettotal:        s.net,
            paytotal:        paytotal,
            billtype:        billtype,
            customer:        selectedCustomer.id,
            priceeditstatus: $('#priceeditstatus').val(),
            billapproveuser: $('#hideapproveuser').val(),
            warrantystatus:  $('#hidewarrantystatus').val()
        },
        success: function(result){
            var obj;
            try { obj = JSON.parse(result); } catch(e){ posToast('fas fa-exclamation-triangle', '', 'Unexpected server response', 'danger'); return; }

            if (obj.actiontype == '1') {
                lastInsertedInvoiceID = obj.invoiceid;
                lastBillType = billtype;
                $('#receiptInvNo').text('INV-' + String(obj.invoiceid).padStart(6, '0'));
                $('#receiptMethod').text(billtype == 1 ? 'Cash' : (billtype == 2 ? 'Card' : 'Credit'));
                openPosModal('modalReceipt');
                if (billtype == 3) {
                    window.open(BASE_URL + 'Directsale/Getcreditprintbill/' + obj.invoiceid, '_blank');
                } else {
                    window.open(BASE_URL + 'Directsale/Getposprintbill/' + obj.invoiceid, '_blank');
                }
            } else {
                var actionObj = JSON.parse(obj.action);
                posToast(actionObj.icon, actionObj.title, actionObj.message, actionObj.type);
            }
        },
        error: function(){
            posToast('fas fa-exclamation-triangle', '', 'Could not reach server. Please try again.', 'danger');
        }
    });
}

/* =========================================================
   BROWSE PRODUCTS MODAL
   ========================================================= */
$('#btnBrowseProducts').on('click', function(){ openPosModal('modalBrowse'); loadBrowseCategories(); loadBrowseTable(); });

function loadBrowseCategories(){
    $.post(BASE_URL + 'Directsale/Getcategorylist', {}, function(result){
        var cats = JSON.parse(result);
        var sel  = $('#browseCategory');
        if (sel.find('option').length <= 1) {
            cats.forEach(function(c){ sel.append('<option value="' + c.idtbl_material_category + '">' + c.categoryname + '</option>'); });
        }
    });
}

function loadBrowseTable(){
    $.post(BASE_URL + 'Directsale/Getproductlist', {
        categoryID:  $('#browseCategory').val(),
        searchTerm:  $('#browseSearch').val(),
        stockFilter: $('#browseStock').val()
    }, function(result){
        var products = JSON.parse(result);
        var body = $('#browseTableBody');

        if (!products.length) {
            body.html('<tr><td colspan="6" class="text-center">No products found</td></tr>');
            return;
        }

        var html = '';
        products.forEach(function(p){
            var s = stockInfo(p.stock);
            html += '<tr ' +
                'data-id="'    + p.id              + '" ' +
                'data-name="'  + escapeHtml(p.name)  + '" ' +
                'data-code="'  + escapeHtml(p.code)  + '" ' +
                'data-price="' + p.price            + '" ' +
                'data-stock="' + p.stock            + '" ' +
                'style="cursor:pointer;">' +
                '<td>' + escapeHtml(p.name) + '</td>' +
                '<td>' + escapeHtml(p.code) + '</td>' +
                '<td>' + (p.categoryName || '-') + '</td>' +
                '<td><span class="pos-stockbadge ' + s.cls + '">' + p.stock + '</span></td>' +
                '<td class="text-right">' + money(p.price) + '</td>' +
                '<td><button class="btn btn-sm btn-outline-primary" ' + (p.stock <= 0 ? 'disabled' : '') + '>Add</button></td></tr>';
        });
        body.html(html);

        /* ── CHANGED: browse table row click also opens batch picker first ── */
        $('#tableBrowseProducts tbody tr').on('click', function(){
            var stock = parseFloat($(this).data('stock')) || 0;
            if (stock <= 0) { posToast('fas fa-exclamation-triangle', '', 'Out of stock', 'danger'); return; }
            closePosModal('modalBrowse');
            openBatchPicker({
                id:    $(this).data('id'),
                name:  $(this).data('name'),
                code:  $(this).data('code'),
                price: $(this).data('price'),
                stock: stock
            });
        });
    });
}

$('#browseSearch').on('input', debounce(loadBrowseTable, 300));
$('#browseCategory, #browseStock').on('change', loadBrowseTable);

/* =========================================================
   HELD BILLS / RETURNS — placeholders
   ========================================================= */
$('#btnHeldBills').on('click', function(){ posToast('fas fa-info-circle', '', 'Held bills feature is coming soon.', 'info'); });
$('#btnReturns').on('click',   function(){ posToast('fas fa-info-circle', '', 'Returns feature is coming soon.',    'info'); });

/* =========================================================
   PREVIOUS BILLS (DataTables SSP)
   ========================================================= */
var prevBillsTable = null;

$('#btnPrevBills').on('click', function(){ openPosModal('modalPrevBills'); initPrevBillsTable(); });

function initPrevBillsTable(){
    if (prevBillsTable) { prevBillsTable.ajax.reload(); return; }
    prevBillsTable = $('#tablePrevBills').DataTable({
        destroy:    true,
        processing: true,
        serverSide: true,
        ajax: {
            url:  BASE_URL + 'scripts/previousbillslist.php',
            type: 'POST',
            data: function(d){
                d.filterDateFrom = $('#prevBillsDateFrom').val();
                d.filterDateTo   = $('#prevBillsDateTo').val();
                d.filterInvType  = $('#prevBillsType').val();
            }
        },
        order: [[0, 'desc']],
        columns: [
            { data: 'idtbl_invoice', render: function(d){ return 'INV-' + String(d).padStart(6,'0'); } },
            { data: 'invdate' },
            { data: 'name',    render: function(d){ return d || 'Cash Customer'; } },
            { data: 'contact', render: function(d){ return d || '—'; } },
            { data: 'grosstotal', className: 'text-right', render: function(d){ return parseFloat(d).toFixed(2); } },
            { data: 'discount',   className: 'text-right', render: function(d){ return parseFloat(d).toFixed(2); } },
            { data: 'nettotal',   className: 'text-right', render: function(d){ return parseFloat(d).toFixed(2); } },
        {
            data: 'method',
            render: function(d, type, row) {

                // Returned overrides everything
                if (row.returnstatus == 1) {
                    return '<span style="background:#dc3545;color:#fff;padding:4px 10px;border-radius:12px;font-size:12px;font-weight:600;display:inline-block;">Returned</span>';
                }

                if (d == 1) {
                    return '<span style="background:#28a745;color:#fff;padding:4px 10px;border-radius:12px;font-size:12px;font-weight:600;display:inline-block;">Cash</span>';
                } 
                else if (d == 2) {
                    return '<span style="background:#007bff;color:#fff;padding:4px 10px;border-radius:12px;font-size:12px;font-weight:600;display:inline-block;">Card</span>';
                } 
                else {
                    return '<span style="background:#ffc107;color:#000;padding:4px 10px;border-radius:12px;font-size:12px;font-weight:600;display:inline-block;">Credit</span>';
                }
            }
        },
            {
                data: 'idtbl_invoice',
                orderable: false,
                render: function(d, type, row) {

                    var printUrl = '';

                    if (row.invtype == 2) {
                        // Credit invoice
                        printUrl = BASE_URL + 'Directsale/Getcreditprintbill/' + d;
                    } else if (row.invtype == 1) {
                        // POS invoice
                        printUrl = BASE_URL + 'Directsale/Getposprintbill/' + d;
                    } else {
                        // fallback (safe default)
                        printUrl = BASE_URL + 'Invoiceview/printreportpos/' + d;
                    }

                    return `
                        <div class="pos-row-actions">
                            <button onclick="window.open('${printUrl}','_blank')" title="Print">
                                <i class="fas fa-print"></i>
                            </button>
                        </div>
                    `;
                }
            }
        ]
    });
}
$('#btnApplyBillsFilter').on('click', function(){ if (prevBillsTable) prevBillsTable.ajax.reload(); });

/* =========================================================
   KEYBOARD SHORTCUTS
   ========================================================= */
$(document).on('keydown', function(e){
    if (e.key === 'Escape') { $('.pos-modal-overlay.show').removeClass('show'); }
    if (e.key === 'F2') { e.preventDefault(); openPosModal('modalBrowse'); loadBrowseCategories(); loadBrowseTable(); }
    if (e.key === 'F4' && !$('.pos-modal-overlay.show').length) {
        e.preventDefault();
        if (cart.length) $('#btnPayCash').click();
    }
});

/* =========================================================
   INIT
   ========================================================= */
$(document).ready(function(){
    document.body.classList.add('pos-fullscreen-page');
    loadCategories();
    loadProducts();
    renderCart();
    setCustomer(selectedCustomer);
    $('#hidewarrantystatus').val('0');
});
</script>

<?php include "include/footer.php"; ?>