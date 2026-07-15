<?php
include "include/header.php";
include "include/topnavbar.php";
?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
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
.stat-card .btn-block.mt-3 {
    font-weight: 700;
    border: none;
}
.btn-products {
    background: #f0ad4e;
    color: #fff;
}
.btn-products:hover {
    background: #ec971f;
    color: #fff;
}
.btn-products:focus, .btn-products:active {
    background: #ec971f !important;
    color: #fff !important;
    box-shadow: 0 0 0 .2rem rgba(240,173,78,.4) !important;
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
/* Reorder alert row styling */
.reorder-alert {
    background: rgba(214, 69, 69, 0.06);
    border-left: 4px solid #d64545;
}

/* Calendar card */
/* Calendar card */
.calendar-card {
    background:#fff;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.06);
    padding: 1.25rem;
    height: 100%;
}
.calendar-card .calendar-header {
    font-weight: 700;
    color: #333;
    margin-bottom: .75rem;
    display:flex;
    align-items:center;
    gap:.5rem;
}
.calendar-selected-note {
    margin-top: .85rem;
    padding-top: .85rem;
    border-top: 1px solid #f0f1f4;
    font-size: .85rem;
    color: #555;
    display:flex;
    align-items:center;
    justify-content:space-between;
    flex-wrap: wrap;
    gap: .5rem;
}
.calendar-selected-note strong { color:#222; }
.today-link {
    font-size: .78rem;
    font-weight: 700;
    color: #495057;
    text-decoration: none;
}
.today-link:hover { text-decoration: underline; color:#212529; }

/* Inline flatpickr, professional neutral theme */
#inlineDatePicker { width: 100%; }
.flatpickr-calendar.inline {
    width: 100% !important;
    box-shadow: none;
    border: 1px solid #e9ecef;
    border-radius: 10px;
    overflow: hidden;
}
.flatpickr-calendar.inline .flatpickr-innerContainer,
.flatpickr-calendar.inline .flatpickr-rContainer,
.flatpickr-calendar.inline .flatpickr-days,
.flatpickr-calendar.inline .dayContainer {
    width: 100% !important;
    max-width: 100% !important;
}

/* Header (month/year) */
.flatpickr-calendar.inline .flatpickr-month {
    background: #f8f9fa;
    border-bottom: 1px solid #e9ecef;
    height: 44px;
}
.flatpickr-current-month {
    padding-top: 6px;
}
.flatpickr-current-month .flatpickr-monthDropdown-months,
.flatpickr-current-month input.cur-year {
    color: #212529 !important;
    font-weight: 700;
}
.flatpickr-current-month .flatpickr-monthDropdown-months option {
    color: #212529;
}
.flatpickr-months .flatpickr-prev-month,
.flatpickr-months .flatpickr-next-month {
    fill: #495057;
    color: #495057;
}
.flatpickr-months .flatpickr-prev-month:hover svg,
.flatpickr-months .flatpickr-next-month:hover svg {
    fill: #212529;
}

/* Weekday row */
.flatpickr-weekdays {
    background: #fff;
    border-bottom: 1px solid #e9ecef;
}
span.flatpickr-weekday {
    background: #fff !important;
    color: #6c757d !important;
    font-weight: 700;
    font-size: .72rem;
    text-transform: uppercase;
}

/* Day numbers - black by default */
.flatpickr-calendar.inline .flatpickr-day {
    max-width: none;
    border-radius: 8px;
    color: #000 !important;
    font-weight: 500;
    background: transparent;
    border-color: transparent;
}
.flatpickr-calendar.inline .flatpickr-day.prevMonthDay,
.flatpickr-calendar.inline .flatpickr-day.nextMonthDay {
    color: #adb5bd !important;
}
.flatpickr-calendar.inline .flatpickr-day.flatpickr-disabled,
.flatpickr-calendar.inline .flatpickr-day.flatpickr-disabled:hover {
    color: #dee2e6 !important;
}

/* Hover on a normal (non-selected) day - light gray bg, keep black text */
.flatpickr-calendar.inline .flatpickr-day:hover,
.flatpickr-calendar.inline .flatpickr-day:focus {
    background: #e9ecef !important;
    border-color: #e9ecef !important;
    color: #000 !important;
}

/* Today - outline only, black text */
.flatpickr-calendar.inline .flatpickr-day.today {
    border: 1px solid #adb5bd !important;
    color: #000 !important;
}
.flatpickr-calendar.inline .flatpickr-day.today:hover {
    border-color: #495057 !important;
    background: #e9ecef !important;
    color: #000 !important;
}

/* Selected day - dark bg, WHITE text, and this must win over hover/focus/active */
.flatpickr-calendar.inline .flatpickr-day.selected,
.flatpickr-calendar.inline .flatpickr-day.selected:hover,
.flatpickr-calendar.inline .flatpickr-day.selected:focus,
.flatpickr-calendar.inline .flatpickr-day.selected:active,
.flatpickr-calendar.inline .flatpickr-day.selected.today,
.flatpickr-calendar.inline .flatpickr-day.selected.today:hover {
    background: #212529 !important;
    border-color: #212529 !important;
    color: #fff !important;
}
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

                <div class="row">
                    <!-- CALENDAR CARD -->
                    <div class="col-lg-4 mb-3">
                        <div class="calendar-card">
                            <div class="calendar-header"><i class="fas fa-calendar-alt text-primary"></i> Filter by Date</div>
                            <div id="inlineDatePicker"></div>
                            <div class="calendar-selected-note">
                                <span>Viewing: <strong><?php echo date('F j, Y', strtotime($selectedDate)); ?></strong></span>
                                <?php if ($selectedDate !== date('Y-m-d')): ?>
                                    <a href="javascript:void(0);" class="today-link" onclick="goToDate('<?php echo date('Y-m-d'); ?>');">Back to Today</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <!-- STAT CARDS -->
                    <div class="col-lg-8">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="stat-card">
                                    <div class="icon-circle" style="background:#e7f1ff;">
                                        <i class="fas fa-coins" style="font-size:1.6rem;color:#007bff;"></i>
                                    </div>
                                    <div class="stat-label"><?php echo $selectedDate === date('Y-m-d') ? "Today's Sales" : "Sales on Date"; ?></div>
                                    <div class="stat-value">LKR <?php echo number_format($dailySalesTotal, 2); ?></div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="stat-card">
                                    <div class="icon-circle" style="background:#e8f8ee;">
                                        <i class="fas fa-chart-line" style="font-size:1.6rem;color:#28a745;"></i>
                                    </div>
                                    <div class="stat-label"><?php echo date('F', strtotime($selectedDate)); ?>'s Sales</div>
                                    <div class="stat-value">LKR <?php echo number_format($monthlySalesTotal, 2); ?></div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="stat-card">
                                    <div class="icon-circle" style="background:#fff7e0;">
                                        <i class="fas fa-boxes" style="font-size:1.6rem;color:#ffc107;"></i>
                                    </div>
                                    <div class="stat-label">Total Products</div>
                                    <div class="stat-value"><?php echo number_format($productsCount); ?></div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="stat-card d-flex flex-column justify-content-between">
                                    <div>
                                        <div class="icon-circle" style="background:#fdeaea;">
                                            <i class="fas fa-receipt" style="font-size:1.6rem;color:#dc3545;"></i>
                                        </div>
                                        <div class="stat-label"><?php echo $selectedDate === date('Y-m-d') ? "Orders Today" : "Orders on Date"; ?></div>
                                        <div class="stat-value"><?php echo number_format($ordersToday); ?></div>
                                    </div>
                                    <a href="<?php echo base_url() ?>Directsale" class="btn btn-primary btn-block mt-3">
                                        <i class="fas fa-plus-circle mr-1"></i> New Direct Sale
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- CHARTS -->
                <div class="row mt-2">
                    <div class="col-md-6 mb-3">
                        <div class="panel-card">
                            <div class="panel-title"><i class="fas fa-calendar-day text-primary"></i> Daily Sales (7 Days ending <?php echo date('M j', strtotime($selectedDate)); ?>)</div>
                            <div class="chart-container">
                                <canvas id="dailySalesChart"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="panel-card">
                            <div class="panel-title"><i class="fas fa-calendar-alt text-success"></i> Monthly Sales (12 Months ending <?php echo date('M Y', strtotime($selectedDate)); ?>)</div>
                            <div class="chart-container">
                                <canvas id="monthlySalesChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- STOCK / MOVEMENT PANELS -->
                <div class="row mt-2 mb-4">
                    <div class="col-md-6 mb-3">
                        <div class="panel-card">
                            <div class="panel-title"><i class="fas fa-fire text-success"></i> Fast Moving Items (30 Days ending <?php echo date('M j', strtotime($selectedDate)); ?>)</div>
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

                    <div class="col-md-6 mb-3">
                        <div class="panel-card">
                            <div class="panel-title"><i class="fas fa-snowflake text-info"></i> Slow Moving Items (30 Days ending <?php echo date('M j', strtotime($selectedDate)); ?>)</div>
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
                    <div class="col-12 mb-3">
                        <div class="panel-card">
                            <div class="panel-title"><i class="fas fa-exclamation-triangle text-danger"></i> Reorder Level Alerts <small class="text-muted font-weight-normal">(live stock, not date filtered)</small></div>
                            <?php if (!empty($lowStockItems)): ?>
                                <div class="table-responsive">
                                    <table class="table table-sm mb-0">
                                        <thead>
                                            <tr>
                                                <th>Item</th>
                                                <th class="text-right">Current / Reorder</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($lowStockItems as $item): 
                                                $is_alert = ((int)$item->currentstock <= (int)$item->reorderlevel);
                                            ?>
                                                <tr class="<?php echo $is_alert ? 'reorder-alert' : ''; ?>">
                                                    <td><?php echo htmlspecialchars($item->materialname); ?></td>
                                                    <td class="text-right <?php echo $is_alert ? 'text-danger font-weight-bold' : ''; ?>"><?php echo (int)$item->currentstock; ?> / <?php echo (int)$item->reorderlevel; ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <p class="text-muted mb-0">All stock levels are healthy.</p>
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
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
function goToDate(dateStr) {
    var url = new URL(window.location.href);
    url.searchParams.set('date', dateStr);
    window.location.href = url.toString();
}

document.addEventListener("DOMContentLoaded", function() {
    flatpickr("#inlineDatePicker", {
        inline: true,
        defaultDate: "<?php echo $selectedDate; ?>",
        maxDate: "today",
        dateFormat: "Y-m-d",
        onChange: function(selectedDates, dateStr) {
            goToDate(dateStr);
        }
    });

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
<?php include "include/footer.php"; ?>