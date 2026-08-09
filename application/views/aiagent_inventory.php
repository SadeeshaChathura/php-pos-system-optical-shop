<?php
include "include/header.php";
include "include/topnavbar.php";

$typeColors = [
    'danger'  => ['bg' => '#fdeaea', 'fg' => '#d64545'],
    'warning' => ['bg' => '#fff7e0', 'fg' => '#c98a05'],
    'success' => ['bg' => '#e8f8ee', 'fg' => '#28a745'],
    'info'    => ['bg' => '#eef6fb', 'fg' => '#17a2b8'],
];
?>
<style>
.icon-circle {
    display: inline-flex; justify-content: center; align-items: center;
    width: 64px; height: 64px; border-radius: var(--erp-radius-sm, 0.4rem); margin-bottom: 12px;
}
.stat-card {
    background: var(--erp-surface, #fff); border: 1px solid var(--erp-border, #e5e9f0);
    border-radius: var(--erp-radius, 0.6rem); padding: 1.5rem;
    box-shadow: var(--erp-shadow, 0 2px 10px rgba(30,41,59,0.06)); height: 100%;
}
.stat-label { font-size:.78rem; font-weight:700; letter-spacing:.6px; text-transform:uppercase; color: var(--erp-text-muted, #8a8f9a); }
.stat-value { font-size: 1.6rem; font-weight: 800; color: var(--erp-text, #222); }
.panel-card {
    background: var(--erp-surface, #fff); border: 1px solid var(--erp-border, #e5e9f0);
    border-radius: var(--erp-radius, 0.6rem); box-shadow: var(--erp-shadow, 0 2px 10px rgba(30,41,59,0.06));
    padding: 1.5rem; height: 100%;
}
.panel-title { font-weight: 700; color: var(--erp-text, #333); margin-bottom: 1rem; display:flex; align-items:center; gap:.5rem; }
.badge-soft { padding:.3rem .65rem; border-radius:999px; font-size:.75rem; font-weight:700; }
.insight-item {
    display:flex; gap:.85rem; align-items:flex-start; padding:.9rem 1rem; border-radius: var(--erp-radius-sm, 0.4rem);
    margin-bottom:.6rem; border-left:4px solid transparent;
}
.insight-item:last-child { margin-bottom:0; }
.insight-icon { font-size:1.1rem; margin-top:.1rem; }
.insight-text { font-size:.9rem; line-height:1.45; color: var(--erp-text, #333); }
.agent-header-icon {
    width:56px; height:56px; border-radius:50%; display:flex; align-items:center; justify-content:center;
    background: rgba(111,66,193,.12); color:#6f42c1; font-size:1.5rem; margin-right:.85rem;
}
.reorder-alert { background: rgba(214, 69, 69, 0.06); border-left: 4px solid var(--erp-danger, #d64545); }
.outofstock-alert { background: rgba(214, 69, 69, 0.14); border-left: 4px solid var(--erp-danger, #d64545); }
#supplierModalBody .supplier-row { padding:.5rem 0; border-bottom:1px solid var(--erp-border, #f0f1f4); }
#supplierModalBody .supplier-row:last-child { border-bottom:none; }
</style>

<div id="layoutSidenav">
    <div id="layoutSidenav_nav">
        <?php include "include/menubar.php"; ?>
    </div>
    <div id="layoutSidenav_content">
        <main>
            <div class="page-header shadow">
                <div class="container-fluid">
                    <div class="page-header-content py-3 d-flex align-items-center justify-content-between">
                        <h1 class="page-header-title d-flex align-items-center">
                            <div class="page-header-icon mr-2"><i class="fas fa-brain"></i></div>
                            <span>Inventory Agent</span>
                        </h1>
                    </div>
                </div>
            </div>

            <div class="container-fluid mt-2 p-0 p-2">

                <!-- INSIGHTS -->
                <div class="row mb-2">
                    <div class="col-12">
                        <div class="panel-card">
                            <div class="panel-title d-flex align-items-center">
                                <div class="agent-header-icon"><i class="fas fa-brain"></i></div>
                                <div>
                                    Inventory Agent Insights
                                    <div class="stat-label" style="margin-top:2px;">Auto-generated from live stock levels — updated live</div>
                                </div>
                            </div>
                            <?php foreach ($insights as $ins):
                                $c = $typeColors[$ins['type']] ?? $typeColors['info'];
                            ?>
                            <div class="insight-item" style="background:<?php echo $c['bg']; ?>; border-left-color:<?php echo $c['fg']; ?>;">
                                <div class="insight-icon" style="color:<?php echo $c['fg']; ?>;"><i class="fas <?php echo $ins['icon']; ?>"></i></div>
                                <div class="insight-text"><?php echo $ins['text']; ?></div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <!-- STAT CARDS -->
                <div class="row mt-2">
                    <div class="col-md-4 mb-3">
                        <div class="stat-card">
                            <div class="icon-circle" style="background:#fdeaea;"><i class="fas fa-exclamation-triangle" style="font-size:1.6rem;color:#dc3545;"></i></div>
                            <div class="stat-label">Items Needing Reorder</div>
                            <div class="stat-value"><?php echo count($reorder); ?></div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="stat-card">
                            <div class="icon-circle" style="background:#eef6fb;"><i class="fas fa-snowflake" style="font-size:1.6rem;color:#17a2b8;"></i></div>
                            <div class="stat-label">Dead Stock Items (90d)</div>
                            <div class="stat-value"><?php echo count($deadStock); ?></div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="stat-card">
                            <div class="icon-circle" style="background:#e8f8ee;"><i class="fas fa-warehouse" style="font-size:1.6rem;color:#28a745;"></i></div>
                            <div class="stat-label">Total Stock Value (Cost)</div>
                            <div class="stat-value">LKR <?php echo number_format($valuationTotal['costvalue'], 2); ?></div>
                        </div>
                    </div>
                </div>

                <!-- REORDER SUGGESTIONS -->
                <div class="row mt-2">
                    <div class="col-12 mb-3">
                        <div class="panel-card">
                            <div class="panel-title"><i class="fas fa-exclamation-triangle text-danger"></i> Reorder Suggestions
                                <?php if (!empty($reorder)): ?><span class="badge-soft" style="background:#fdeaea;color:#d64545;"><?php echo count($reorder); ?> item(s)</span><?php endif; ?>
                            </div>
                            <?php if (!empty($reorder)): ?>
                            <div class="table-responsive">
                                <table class="table table-sm mb-0">
                                    <thead>
                                        <tr>
                                            <th>Item</th>
                                            <th class="text-right">Current / Reorder Level</th>
                                            <th class="text-right">Avg Daily Sales</th>
                                            <th class="text-right">Days of Stock Left</th>
                                            <th class="text-right">Suggested Order Qty</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($reorder as $item):
                                            $outOfStock = $item->currentstock <= 0;
                                        ?>
                                            <tr class="<?php echo $outOfStock ? 'outofstock-alert' : 'reorder-alert'; ?>">
                                                <td><?php echo htmlspecialchars($item->materialname); ?></td>
                                                <td class="text-right font-weight-bold <?php echo $outOfStock ? 'text-danger' : ''; ?>">
                                                    <?php echo (int)$item->currentstock; ?> / <?php echo (int)$item->reorderlevel; ?>
                                                </td>
                                                <td class="text-right"><?php echo $item->dailyvelocity; ?>/day</td>
                                                <td class="text-right"><?php echo $item->daysofstock !== null ? $item->daysofstock . ' days' : '—'; ?></td>
                                                <td class="text-right font-weight-bold text-primary"><?php echo (int)$item->suggestedorderqty; ?></td>
                                                <td>
                                                    <button type="button" class="btn btn-sm btn-outline-secondary btn-find-suppliers"
                                                        data-materialid="<?php echo $item->idtbl_material_info; ?>"
                                                        data-materialname="<?php echo htmlspecialchars($item->materialname); ?>">
                                                        <i class="fas fa-truck mr-1"></i> Find Suppliers
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <?php else: ?>
                                <p class="text-muted mb-0">All stock levels are healthy — nothing to reorder right now.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="row mt-2 mb-4">
                    <!-- DEAD STOCK -->
                    <div class="col-md-6 mb-3">
                        <div class="panel-card">
                            <div class="panel-title"><i class="fas fa-snowflake text-info"></i> Dead Stock (No Sales in 90 Days)</div>
                            <?php if (!empty($deadStock)): ?>
                                <div class="table-responsive">
                                    <table class="table table-sm mb-0">
                                        <thead><tr><th>Item</th><th class="text-right">Qty on Hand</th><th class="text-right">Value (Cost)</th></tr></thead>
                                        <tbody>
                                        <?php foreach ($deadStock as $d): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($d->materialname); ?></td>
                                                <td class="text-right"><?php echo (int)$d->currentstock; ?></td>
                                                <td class="text-right">LKR <?php echo number_format($d->stockvalue, 2); ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <p class="text-muted mb-0">No dead stock detected — everything is moving.</p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- STOCK VALUATION BY CATEGORY -->
                    <div class="col-md-6 mb-3">
                        <div class="panel-card">
                            <div class="panel-title"><i class="fas fa-layer-group text-success"></i> Stock Value by Category</div>
                            <?php if (!empty($valuation)): ?>
                                <div class="table-responsive">
                                    <table class="table table-sm mb-0">
                                        <thead><tr><th>Category</th><th class="text-right">Qty</th><th class="text-right">Cost Value</th></tr></thead>
                                        <tbody>
                                        <?php foreach ($valuation as $v): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars(trim($v->categoryname)); ?></td>
                                                <td class="text-right"><?php echo (int)$v->totalqty; ?></td>
                                                <td class="text-right">LKR <?php echo number_format($v->costvalue, 2); ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <p class="text-muted mb-0">No stock data available.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

            </div>
        </main>
        <?php include "include/footerbar.php"; ?>
    </div>
</div>

<!-- SUPPLIER MODAL -->
<div class="modal fade" id="supplierModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-truck mr-2"></i>Suppliers for <span id="supplierModalItemName"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body" id="supplierModalBody">
                <p class="text-muted mb-0">Loading...</p>
            </div>
        </div>
    </div>
</div>

<?php include "include/footerscripts.php"; ?>
<script>
document.addEventListener("DOMContentLoaded", function () {
    var buttons = document.querySelectorAll('.btn-find-suppliers');
    buttons.forEach(function (btn) {
        btn.addEventListener('click', function () {
            var materialId = btn.getAttribute('data-materialid');
            var materialName = btn.getAttribute('data-materialname');
            document.getElementById('supplierModalItemName').textContent = materialName;
            document.getElementById('supplierModalBody').innerHTML = '<p class="text-muted mb-0">Loading...</p>';

            $('#supplierModal').modal('show');

            fetch("<?php echo base_url() ?>Aiagent/GetSuppliers", {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'materialId=' + encodeURIComponent(materialId)
            })
            .then(function (res) { return res.json(); })
            .then(function (data) {
                var body = document.getElementById('supplierModalBody');
                if (!data || data.length === 0) {
                    body.innerHTML = '<p class="text-muted mb-0">No suppliers linked to this item yet. Add one from the Supplier module.</p>';
                    return;
                }
                var html = '';
                data.forEach(function (s) {
                    html += '<div class="supplier-row d-flex justify-content-between align-items-center">' +
                        '<div><strong>' + s.suppliername + '</strong><br><small class="text-muted">' + (s.primarycontactno || 'No contact') + (s.email ? ' &middot; ' + s.email : '') + '</small></div>' +
                        '</div>';
                });
                body.innerHTML = html;
            })
            .catch(function () {
                document.getElementById('supplierModalBody').innerHTML = '<p class="text-danger mb-0">Could not load suppliers. Please try again.</p>';
            });
        });
    });
});
</script>
<?php include "include/footer.php"; ?>