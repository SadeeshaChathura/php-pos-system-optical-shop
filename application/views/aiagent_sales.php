<?php
include "include/header.php";
include "include/topnavbar.php";

$paymentLabels = [1 => 'Cash', 2 => 'Card / Cheque'];
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
.stat-sub { font-size: .8rem; font-weight: 600; margin-top: .25rem; }
.panel-card {
    background: var(--erp-surface, #fff); border: 1px solid var(--erp-border, #e5e9f0);
    border-radius: var(--erp-radius, 0.6rem); box-shadow: var(--erp-shadow, 0 2px 10px rgba(30,41,59,0.06));
    padding: 1.5rem; height: 100%;
}
.panel-title { font-weight: 700; color: var(--erp-text, #333); margin-bottom: 1rem; display:flex; align-items:center; gap:.5rem; }
.movement-row { display:flex; justify-content:space-between; align-items:center; padding: .6rem 0; border-bottom: 1px solid var(--erp-border, #f0f1f4); }
.movement-row:last-child { border-bottom:none; }
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
    background: rgba(0,123,255,.12); color:#007bff; font-size:1.5rem; margin-right:.85rem;
}
.reorder-alert { background: rgba(214, 69, 69, 0.06); border-left: 4px solid var(--erp-danger, #d64545); }
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
                            <div class="page-header-icon mr-2"><i class="fas fa-robot"></i></div>
                            <span>Sales Agent</span>
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
                                <div class="agent-header-icon"><i class="fas fa-robot"></i></div>
                                <div>
                                    Sales Agent Insights
                                    <div class="stat-label" style="margin-top:2px;">Auto-generated from your sales data — updated live</div>
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
                    <div class="col-md-3 mb-3">
                        <div class="stat-card">
                            <div class="icon-circle" style="background:#e7f1ff;"><i class="fas fa-coins" style="font-size:1.6rem;color:#007bff;"></i></div>
                            <div class="stat-label">This Month's Revenue</div>
                            <div class="stat-value">LKR <?php echo number_format($revenueTrend['current'], 2); ?></div>
                            <div class="stat-sub" style="color:<?php echo $revenueTrend['change'] >= 0 ? '#28a745' : '#d64545'; ?>;">
                                <i class="fas fa-<?php echo $revenueTrend['change'] >= 0 ? 'arrow-up' : 'arrow-down'; ?>"></i>
                                <?php echo abs($revenueTrend['change']); ?>% vs last month
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="stat-card">
                            <div class="icon-circle" style="background:#e8f8ee;"><i class="fas fa-receipt" style="font-size:1.6rem;color:#28a745;"></i></div>
                            <div class="stat-label">Avg Invoice Value (30d)</div>
                            <div class="stat-value">LKR <?php echo number_format($avgInvoice['current'], 2); ?></div>
                            <div class="stat-sub" style="color:<?php echo $avgInvoice['change'] >= 0 ? '#28a745' : '#d64545'; ?>;">
                                <i class="fas fa-<?php echo $avgInvoice['change'] >= 0 ? 'arrow-up' : 'arrow-down'; ?>"></i>
                                <?php echo abs($avgInvoice['change']); ?>% vs prior 30d
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="stat-card">
                            <div class="icon-circle" style="background:#fdeaea;"><i class="fas fa-user-clock" style="font-size:1.6rem;color:#dc3545;"></i></div>
                            <div class="stat-label">Customers At Risk</div>
                            <div class="stat-value"><?php echo count($customerRisk); ?></div>
                            <div class="stat-sub text-muted">Overdue advance orders</div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="stat-card">
                            <div class="icon-circle" style="background:#fff7e0;"><i class="fas fa-star" style="font-size:1.6rem;color:#ffc107;"></i></div>
                            <div class="stat-label">Top Seller (30d)</div>
                            <div class="stat-value" style="font-size:1.1rem;"><?php echo !empty($topRevenueItems) ? htmlspecialchars($topRevenueItems[0]->materialname) : '—'; ?></div>
                            <div class="stat-sub text-muted"><?php echo !empty($topRevenueItems) ? 'LKR ' . number_format($topRevenueItems[0]->totalrevenue, 2) : ''; ?></div>
                        </div>
                    </div>
                </div>

                <div class="row mt-2 mb-4">
                    <!-- TOP REVENUE ITEMS -->
                    <div class="col-md-6 mb-3">
                        <div class="panel-card">
                            <div class="panel-title"><i class="fas fa-chart-bar text-primary"></i> Top Revenue Items (Last 30 Days)</div>
                            <?php if (!empty($topRevenueItems)): ?>
                                <?php foreach ($topRevenueItems as $item): ?>
                                    <div class="movement-row">
                                        <span><?php echo htmlspecialchars($item->materialname); ?><br><small class="text-muted"><?php echo (int)$item->totalqty; ?> units sold</small></span>
                                        <span class="badge-soft" style="background:#e7f1ff;color:#007bff;">LKR <?php echo number_format($item->totalrevenue, 2); ?></span>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p class="text-muted mb-0">No sales data in this window yet.</p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- PAYMENT METHOD BREAKDOWN -->
                    <div class="col-md-6 mb-3">
                        <div class="panel-card">
                            <div class="panel-title"><i class="fas fa-wallet text-success"></i> Payment Method Split (Last 30 Days)</div>
                            <?php if (!empty($paymentBreakdown)):
                                $grandTotal = array_sum(array_map(function ($p) { return $p->total; }, $paymentBreakdown));
                            ?>
                                <?php foreach ($paymentBreakdown as $p):
                                    $pct = $grandTotal > 0 ? round(($p->total / $grandTotal) * 100, 1) : 0;
                                    $label = $paymentLabels[$p->method] ?? ('Method #' . $p->method);
                                ?>
                                    <div class="movement-row">
                                        <span><?php echo htmlspecialchars($label); ?><br><small class="text-muted"><?php echo (int)$p->txcount; ?> transactions</small></span>
                                        <span class="badge-soft" style="background:#e8f8ee;color:#28a745;">LKR <?php echo number_format($p->total, 2); ?> (<?php echo $pct; ?>%)</span>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p class="text-muted mb-0">No payment data in this window yet.</p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- CUSTOMER RISK -->
                    <?php if (!empty($customerRisk)): ?>
                    <div class="col-12 mb-3">
                        <div class="panel-card" style="border-left:4px solid var(--erp-danger, #d64545);">
                            <div class="panel-title"><i class="fas fa-user-clock text-danger"></i> Customer Payment Risk
                                <span class="badge-soft" style="background:#fdeaea;color:#d64545;"><?php echo count($customerRisk); ?> overdue</span>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-sm mb-0">
                                    <thead><tr><th>Invoice</th><th>Customer</th><th>Due Date</th><th class="text-right">Balance</th><th></th></tr></thead>
                                    <tbody>
                                    <?php foreach ($customerRisk as $o):
                                        $balance = $o->nettotal - $o->paidsofar;
                                    ?>
                                        <tr class="reorder-alert">
                                            <td>INV-<?php echo str_pad($o->idtbl_invoice, 6, '0', STR_PAD_LEFT); ?></td>
                                            <td><?php echo htmlspecialchars($o->customername); ?><br><small class="text-muted"><?php echo htmlspecialchars($o->customercontact); ?></small></td>
                                            <td class="text-danger font-weight-bold"><?php echo date('M j, Y', strtotime($o->duedate)); ?> (<?php echo $o->dayslate; ?>d late)</td>
                                            <td class="text-right font-weight-bold">LKR <?php echo number_format($balance, 2); ?></td>
                                            <td><a href="<?php echo base_url() ?>Directsale" class="btn btn-sm btn-outline-danger">Settle</a></td>
                                        </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>

            </div>
        </main>
        <?php include "include/footerbar.php"; ?>
    </div>
</div>

<?php include "include/footerscripts.php"; ?>
<?php include "include/footer.php"; ?>