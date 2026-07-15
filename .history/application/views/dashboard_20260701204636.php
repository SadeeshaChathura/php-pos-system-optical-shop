<?php
include "include/header.php";
include "include/topnavbar.php";
?>
<style>
.icon-circle {
    display: inline-flex;
    justify-content: center;
    align-items: center;
    width: 64px;
    height: 64px;
    border-radius: 16px;
    margin-bottom: 12px;
}
.stat-card {
    background: #fff;
    border-radius: 16px;
    padding: 1.5rem;
    box-shadow: 0 4px 20px rgba(0,0,0,0.06);
    transition: transform .25s ease, box-shadow .25s ease;
    height: 100%;
}
.stat-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.12);
}
.stat-label {
    font-size: .78rem;
    font-weight: 700;
    letter-spacing: .6px;
    text-transform: uppercase;
    color: #8a8f9a;
}
.stat-value {
    font-size: 1.6rem;
    font-weight: 800;
    color: #222;
}
.panel-card {
    background:#fff;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.06);
    padding: 1.5rem;
    height: 100%;
}
.panel-title {
    font-weight: 700;
    color: #333;
    margin-bottom: 1rem;
    display:flex;
    align-items:center;
    gap:.5rem;
}
.movement-row {
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding: .6rem 0;
    border-bottom: 1px solid #f0f1f4;
}
.movement-row:last-child{ border-bottom:none; }
.badge-soft {
    padding: .3rem .65rem;
    border-radius: 50px;
    font-size: .75rem;
    font-weight: 700;
}
.welcome-company-badge {
    display: inline-flex;
    align-items: center;
    background: rgba(255,255,255,.15);
    backdrop-filter: blur(10px);
    padding: .65rem 1.25rem;
    border-radius: 50px;
    border: 2px solid rgba(255,255,255,.3);
}
.welcome-company-badge .welcome-icon {
    background: rgba(255,255,255,.25);
    width: 36px; height: 36px;
    border-radius: 50%;
    display:flex; align-items:center; justify-content:center;
    margin-right:.65rem;
}
.welcome-company-badge .company-name { color:#fff; font-weight:700; }
.chart-container { position: relative; height: 320px; }
.chart-container-sm { position: relative; height: 280px; }
</style>

<div id="layoutSidenav">
    <div id="layoutSidenav_nav">
        <?php include "include/menubar.php"; ?>
    </div>
    <div id="layoutSidenav_content">
        <main>
            <div class="page-header text-white">
                <div class="container-fluid">
                    <div class="page-header-content py-3 d-flex align-items-center justify-content-between">
                        <h1 class="page-header-title d-flex align-items-center">
                            <div class="page-header-icon mr-2"><i class="fas fa-store-alt"></i></div>
                            <span>Dashboard</span>
                        </h1>
                        <div class="welcome-company-badge">
                            <div class="welcome-icon"><i class="fas fa-building"></i></div>
                            <span class="company-name"><?php echo $_SESSION['company']; ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="container-fluid mt-4 ml-2">

                <!-- STAT CARDS -->
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <div class="stat-card">
                            <div class="icon-circle" style="background:#e7f1ff;">
                                <i class="fas fa-coins" style="font-size:1.6rem;color:#007bff;"></i>
                            </div>
                            <div class="stat-label">Today's Sales</div>
                            <div class="stat-value">LKR <?php echo number_format($dailySalesTotal, 2); ?></div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="stat-card">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="icon-circle" style="background:#e8f8ee;">
                                    <i class="fas fa-chart-line" style="font-size:1.6rem;color:#28a745;"></i>
                                </div>
                                <form id="monthForm" method="get" style="margin:0;">
                                    <input type="month" name="month" id="monthPicker" class="form-control form-control-sm" style="height:34px;font-size:.9rem;" value="<?php echo isset($selectedMonth)?htmlspecialchars($selectedMonth):''; ?>">
                                </form>
                            </div>
                            <?php if (!empty($selectedMonth)): ?>
                                <div class="stat-label">Sales for <?php echo date('F Y', strtotime($selectedMonth.'-01')); ?></div>
                            <?php else: ?>
                                <div class="stat-label">This Month's Sales</div>
                            <?php endif; ?>
                            <div class="stat-value">LKR <?php echo number_format($monthlySalesTotal, 2); ?></div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="stat-card">
                            <div class="icon-circle" style="background:#fff7e0;">
                                <i class="fas fa-boxes" style="font-size:1.6rem;color:#ffc107;"></i>
                            </div>
                            <div class="stat-label">Total Products</div>
                            <div class="stat-value"><?php echo number_format($productsCount); ?></div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="stat-card d-flex flex-column justify-content-between">
                            <div>
                                <div class="icon-circle" style="background:#fdeaea;">
                                    <i class="fas fa-receipt" style="font-size:1.6rem;color:#dc3545;"></i>
                                </div>
                                <div class="stat-label">Orders Today</div>
                                <div class="stat-value"><?php echo number_format($ordersToday); ?></div>
                            </div>
                            <a href="<?php echo base_url() ?>Directsale" class="btn btn-primary btn-block mt-3">
                                <i class="fas fa-plus-circle mr-1"></i> New Direct Sale
                            </a>
                        </div>
                    </div>
                </div>

                <!-- CHARTS -->
                <div class="row mt-2">
                    <div class="col-md-6 mb-3">
                        <div class="panel-card">
                            <div class="panel-title"><i class="fas fa-calendar-day text-primary"></i> Daily Sales (Last 7 Days)</div>
                            <div class="chart-container">
                                <canvas id="dailySalesChart"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="panel-card">
                            <div class="panel-title"><i class="fas fa-calendar-alt text-success"></i>
                                <?php if (!empty($selectedMonth)): ?>
                                    Monthly Sales (Last 12 Months ending <?php echo date('M Y', strtotime($selectedMonth.'-01')); ?>)
                                <?php else: ?>
                                    Monthly Sales (Last 12 Months)
                                <?php endif; ?>
                            </div>
                            <div class="chart-container">
                                <canvas id="monthlySalesChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- STOCK / MOVEMENT PANELS -->
                <div class="row mt-2 mb-4">
                    <div class="col-md-4 mb-3">
                        <div class="panel-card">
                            <div class="panel-title"><i class="fas fa-exclamation-triangle text-danger"></i> Reorder Level Alerts</div>
                            <?php if (!empty($lowStockItems)): ?>
                                <?php foreach ($lowStockItems as $item): ?>
                                    <div class="movement-row">
                                        <span><?php echo htmlspecialchars($item->materialname); ?></span>
                                        <span class="badge-soft" style="background:#fdeaea;color:#dc3545;">
                                            <?php echo (int)$item->currentstock; ?> / <?php echo (int)$item->reorderlevel; ?>
                                        </span>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p class="text-muted mb-0">All stock levels are healthy.</p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <div class="panel-card">
                            <div class="panel-title"><i class="fas fa-fire text-success"></i> Fast Moving Items</div>
                            <?php if (!empty($fastMoving)): ?>
                                <?php foreach ($fastMoving as $item): ?>
                                    <div class="movement-row">
                                        <span><?php echo htmlspecialchars($item->materialname); ?></span>
                                        <span class="badge-soft" style="background:#e8f8ee;color:#28a745;">
                                            <?php echo (int)$item->totalqty; ?> sold
                                        </span>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p class="text-muted mb-0">No sales data yet.</p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <div class="panel-card">
                            <div class="panel-title"><i class="fas fa-snowflake text-info"></i> Slow Moving Items</div>
                            <?php if (!empty($slowMoving)): ?>
                                <?php foreach ($slowMoving as $item): ?>
                                    <div class="movement-row">
                                        <span><?php echo htmlspecialchars($item->materialname); ?></span>
                                        <span class="badge-soft" style="background:#eef6fb;color:#17a2b8;">
                                            <?php echo (int)$item->totalqty; ?> sold
                                        </span>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p class="text-muted mb-0">No data available.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

            </div>
        </main>
        <?php include "include/footerbar.php"; ?>
    </div>
</div>

<?php include "include/footerscripts.php"; ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    var dailyLabels = <?php echo json_encode(array_map(function($d){ return date('M d', strtotime($d)); }, array_keys($dailySalesChart))); ?>;
    var dailyData = <?php echo json_encode(array_values($dailySalesChart)); ?>;

    new Chart(document.getElementById('dailySalesChart').getContext('2d'), {
        type: 'line',
        data: {
            labels: dailyLabels,
            datasets: [{
                label: 'Daily Sales (LKR)',
                data: dailyData,
                borderColor: '#007bff',
                backgroundColor: 'rgba(0,123,255,0.1)',
                fill: true,
                tension: 0.35,
                pointBackgroundColor: '#007bff'
            }]
        },
        options: { responsive: true, maintainAspectRatio: false }
    });

    var monthlyLabels = <?php echo json_encode(array_map(function($m){ return date('M Y', strtotime($m.'-01')); }, array_keys($monthlySalesChart))); ?>;
    var monthlyData = <?php echo json_encode(array_values($monthlySalesChart)); ?>;

    new Chart(document.getElementById('monthlySalesChart').getContext('2d'), {
        type: 'line',
        data: {
            labels: monthlyLabels,
            datasets: [{
                label: 'Monthly Sales (LKR)',
                data: monthlyData,
                borderColor: '#28a745',
                backgroundColor: 'rgba(40,167,69,0.1)',
                fill: true,
                tension: 0.35,
                pointBackgroundColor: '#28a745'
            }]
        },
        options: { responsive: true, maintainAspectRatio: false }
    });
});
</script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    var mp = document.getElementById('monthPicker');
    if (mp) mp.addEventListener('change', function(){ document.getElementById('monthForm').submit(); });
});
</script>
<?php include "include/footer.php"; ?>