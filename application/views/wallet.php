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
            <div class="page-header shadow">
                <div class="container-fluid">
                    <div class="page-header-content py-3">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i class="fas fa-wallet"></i></div>
                            <span>Business Wallet</span>
                        </h1>
                    </div>
                </div>
            </div>

            <style>
                .wallet-hero { border-radius: .6rem; border: none; color: #fff; overflow: hidden; position: relative; }
                .wallet-hero .card-body { padding: 1.5rem; }
                .wallet-hero .wallet-icon { font-size: 2.4rem; opacity: .35; position: absolute; right: 1rem; top: 1rem; }
                .wallet-hero .wallet-label { font-size: .8rem; text-transform: uppercase; letter-spacing: .05em; opacity: .9; }
                .wallet-hero .wallet-amount { font-size: 1.9rem; font-weight: 700; margin-top: .25rem; }
                .wallet-hero.bg-cash { background: linear-gradient(135deg, #1cc88a 0%, #17a673 100%); }
                .wallet-hero.bg-bank { background: linear-gradient(135deg, #4e73df 0%, #2e59d9 100%); }
                .wallet-hero.bg-total { background: linear-gradient(135deg, #36b9cc 0%, #258391 100%); }

                .wallet-stat-card { border-radius: .5rem; border: 1px solid #e3e6f0; }
                .wallet-stat-card .stat-label { font-size: .78rem; color: #858796; text-transform: uppercase; letter-spacing: .03em; }
                .wallet-stat-card .stat-value { font-size: 1.25rem; font-weight: 700; color: #2c3e50; }
                .wallet-stat-card .stat-icon { font-size: 1.6rem; opacity: .25; }
                .wallet-section-title { font-weight: 700; font-size: 1rem; color: #4e73df; margin: 1.5rem 0 .75rem; text-transform: uppercase; letter-spacing: .04em; }
                .wallet-progress { height: .55rem; border-radius: .3rem; }
                .wallet-flow-in { color: #1cc88a; font-weight: 700; }
                .wallet-flow-out { color: #e74a3b; font-weight: 700; }
                .wallet-net-positive { color: #1cc88a; }
                .wallet-net-negative { color: #e74a3b; }
            </style>

            <div class="container-fluid mt-2 p-0 p-2">

                <!-- ===== HERO: Core Wallet Balances ===== -->
                <div class="row">
                    <div class="col-xl-4 col-md-6 mb-3">
                        <div class="card wallet-hero bg-cash shadow">
                            <div class="card-body">
                                <i class="fas fa-hand-holding-usd wallet-icon"></i>
                                <div class="wallet-label">Cash In Hand</div>
                                <div class="wallet-amount">LKR <?php echo number_format($wallet->cashinhand, 2); ?></div>
                                <small>After all cash expenses deducted</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-md-6 mb-3">
                        <div class="card wallet-hero bg-bank shadow">
                            <div class="card-body">
                                <i class="fas fa-university wallet-icon"></i>
                                <div class="wallet-label">Bank / Card Balance</div>
                                <div class="wallet-amount">LKR <?php echo number_format($wallet->bankbalance, 2); ?></div>
                                <small>Bank transfers &amp; cheques, net of expenses</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-md-12 mb-3">
                        <div class="card wallet-hero bg-total shadow">
                            <div class="card-body">
                                <i class="fas fa-wallet wallet-icon"></i>
                                <div class="wallet-label">Total Available Funds</div>
                                <div class="wallet-amount">LKR <?php echo number_format($wallet->totalavailable, 2); ?></div>
                                <small>Cash + Bank combined</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ===== TODAY SNAPSHOT ===== -->
                <div class="wallet-section-title"><i class="fas fa-calendar-day mr-1"></i> Today's Snapshot</div>
                <div class="row">
                    <div class="col-xl-3 col-md-6 mb-3">
                        <div class="card wallet-stat-card shadow-sm">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="stat-label">Sales</div>
                                    <div class="stat-value">LKR <?php echo number_format($wallet->todaysales, 2); ?></div>
                                </div>
                                <i class="fas fa-shopping-cart stat-icon"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 mb-3">
                        <div class="card wallet-stat-card shadow-sm">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="stat-label">Received</div>
                                    <div class="stat-value wallet-flow-in">LKR <?php echo number_format($wallet->todayreceived, 2); ?></div>
                                </div>
                                <i class="fas fa-arrow-circle-down stat-icon"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 mb-3">
                        <div class="card wallet-stat-card shadow-sm">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="stat-label">Expenses</div>
                                    <div class="stat-value wallet-flow-out">LKR <?php echo number_format($wallet->todayexpense, 2); ?></div>
                                </div>
                                <i class="fas fa-arrow-circle-up stat-icon"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 mb-3">
                        <div class="card wallet-stat-card shadow-sm">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="stat-label">Net Movement</div>
                                    <div class="stat-value <?php echo ($wallet->todaynet>=0)?'wallet-net-positive':'wallet-net-negative'; ?>">
                                        LKR <?php echo number_format($wallet->todaynet, 2); ?>
                                    </div>
                                </div>
                                <i class="fas fa-exchange-alt stat-icon"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ===== THIS MONTH SNAPSHOT ===== -->
                <div class="wallet-section-title"><i class="fas fa-calendar-alt mr-1"></i> This Month (<?php echo date('F Y'); ?>)</div>
                <div class="row">
                    <div class="col-xl-3 col-md-6 mb-3">
                        <div class="card wallet-stat-card shadow-sm">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="stat-label">Sales</div>
                                    <div class="stat-value">LKR <?php echo number_format($wallet->monthsales, 2); ?></div>
                                </div>
                                <i class="fas fa-chart-line stat-icon"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 mb-3">
                        <div class="card wallet-stat-card shadow-sm">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="stat-label">Received</div>
                                    <div class="stat-value wallet-flow-in">LKR <?php echo number_format($wallet->monthreceived, 2); ?></div>
                                </div>
                                <i class="fas fa-money-check-alt stat-icon"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 mb-3">
                        <div class="card wallet-stat-card shadow-sm">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="stat-label">Expenses</div>
                                    <div class="stat-value wallet-flow-out">LKR <?php echo number_format($wallet->monthexpense, 2); ?></div>
                                </div>
                                <i class="fas fa-receipt stat-icon"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 mb-3">
                        <div class="card wallet-stat-card shadow-sm">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="stat-label">Net Profit Flow</div>
                                    <div class="stat-value <?php echo ($wallet->monthnet>=0)?'wallet-net-positive':'wallet-net-negative'; ?>">
                                        LKR <?php echo number_format($wallet->monthnet, 2); ?>
                                    </div>
                                </div>
                                <i class="fas fa-balance-scale stat-icon"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ===== CASH FLOW BREAKDOWN ===== -->
                <div class="wallet-section-title"><i class="fas fa-random mr-1"></i> Cash Flow Breakdown (All-Time)</div>
                <div class="row">
                    <div class="col-lg-6 mb-3">
                        <div class="card shadow-sm h-100">
                            <div class="card-header font-weight-bold"><i class="fas fa-arrow-down text-success mr-1"></i> Money In (by method)</div>
                            <div class="card-body">
                                <?php
                                    $totalIn = $wallet->cashin + $wallet->bankin + $wallet->chequein;
                                    $totalIn = $totalIn > 0 ? $totalIn : 1;
                                    $cashPct = round(($wallet->cashin/$totalIn)*100, 1);
                                    $bankPct = round(($wallet->bankin/$totalIn)*100, 1);
                                    $chqPct  = round(($wallet->chequein/$totalIn)*100, 1);
                                ?>
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between"><span>Cash</span><span>LKR <?php echo number_format($wallet->cashin,2); ?> (<?php echo $cashPct; ?>%)</span></div>
                                    <div class="progress wallet-progress"><div class="progress-bar bg-success" style="width: <?php echo $cashPct; ?>%"></div></div>
                                </div>
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between"><span>Bank / Card</span><span>LKR <?php echo number_format($wallet->bankin,2); ?> (<?php echo $bankPct; ?>%)</span></div>
                                    <div class="progress wallet-progress"><div class="progress-bar bg-primary" style="width: <?php echo $bankPct; ?>%"></div></div>
                                </div>
                                <div>
                                    <div class="d-flex justify-content-between"><span>Cheque</span><span>LKR <?php echo number_format($wallet->chequein,2); ?> (<?php echo $chqPct; ?>%)</span></div>
                                    <div class="progress wallet-progress"><div class="progress-bar bg-info" style="width: <?php echo $chqPct; ?>%"></div></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 mb-3">
                        <div class="card shadow-sm h-100">
                            <div class="card-header font-weight-bold"><i class="fas fa-arrow-up text-danger mr-1"></i> Money Out (by method)</div>
                            <div class="card-body">
                                <?php
                                    $totalOut = $wallet->cashexp + $wallet->bankexp + $wallet->chequeexp;
                                    $totalOut = $totalOut > 0 ? $totalOut : 1;
                                    $cashOPct = round(($wallet->cashexp/$totalOut)*100, 1);
                                    $bankOPct = round(($wallet->bankexp/$totalOut)*100, 1);
                                    $chqOPct  = round(($wallet->chequeexp/$totalOut)*100, 1);
                                ?>
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between"><span>Cash</span><span>LKR <?php echo number_format($wallet->cashexp,2); ?> (<?php echo $cashOPct; ?>%)</span></div>
                                    <div class="progress wallet-progress"><div class="progress-bar bg-danger" style="width: <?php echo $cashOPct; ?>%"></div></div>
                                </div>
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between"><span>Bank / Card</span><span>LKR <?php echo number_format($wallet->bankexp,2); ?> (<?php echo $bankOPct; ?>%)</span></div>
                                    <div class="progress wallet-progress"><div class="progress-bar bg-warning" style="width: <?php echo $bankOPct; ?>%"></div></div>
                                </div>
                                <div>
                                    <div class="d-flex justify-content-between"><span>Cheque</span><span>LKR <?php echo number_format($wallet->chequeexp,2); ?> (<?php echo $chqOPct; ?>%)</span></div>
                                    <div class="progress wallet-progress"><div class="progress-bar bg-secondary" style="width: <?php echo $chqOPct; ?>%"></div></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ===== BUSINESS SNAPSHOT ===== -->
                <div class="wallet-section-title"><i class="fas fa-briefcase mr-1"></i> Business Snapshot</div>
                <div class="row">
                    <div class="col-xl-3 col-md-6 mb-3">
                        <div class="card wallet-stat-card shadow-sm">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="stat-label">Customer Receivables</div>
                                    <div class="stat-value">LKR <?php echo number_format($wallet->receivables, 2); ?></div>
                                </div>
                                <i class="fas fa-user-clock stat-icon"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 mb-3">
                        <div class="card wallet-stat-card shadow-sm">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="stat-label">Total Purchases (GRN)</div>
                                    <div class="stat-value">LKR <?php echo number_format($wallet->totalpurchases, 2); ?></div>
                                </div>
                                <i class="fas fa-truck-loading stat-icon"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 mb-3">
                        <div class="card wallet-stat-card shadow-sm">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="stat-label">Stock Value (Cost)</div>
                                    <div class="stat-value">LKR <?php echo number_format($wallet->stockcostvalue, 2); ?></div>
                                </div>
                                <i class="fas fa-boxes stat-icon"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 mb-3">
                        <div class="card wallet-stat-card shadow-sm">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="stat-label">Stock Value (Sale)</div>
                                    <div class="stat-value">LKR <?php echo number_format($wallet->stocksalevalue, 2); ?></div>
                                </div>
                                <i class="fas fa-tags stat-icon"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ===== SUMMARY TABLE ===== -->
                <div class="wallet-section-title"><i class="fas fa-file-invoice-dollar mr-1"></i> Full Summary</div>
                <div class="card shadow-sm mb-4">
                    <div class="card-body p-0">
                        <table class="table table-sm table-striped mb-0">
                            <tbody>
                                <tr><td class="pl-3">Total Sales (All-Time)</td><td class="text-right pr-3">LKR <?php echo number_format($wallet->totalsales, 2); ?></td></tr>
                                <tr><td class="pl-3">Total Amount Received</td><td class="text-right pr-3 wallet-flow-in">LKR <?php echo number_format($wallet->totalreceived, 2); ?></td></tr>
                                <tr><td class="pl-3">Customer Receivables (Outstanding)</td><td class="text-right pr-3">LKR <?php echo number_format($wallet->receivables, 2); ?></td></tr>
                                <tr><td class="pl-3">Total Expenses (All-Time)</td><td class="text-right pr-3 wallet-flow-out">LKR <?php echo number_format($wallet->totalexpense, 2); ?></td></tr>
                                <tr><td class="pl-3">Total Purchases (GRN Value)</td><td class="text-right pr-3">LKR <?php echo number_format($wallet->totalpurchases, 2); ?></td></tr>
                                <tr class="font-weight-bold"><td class="pl-3">Cash In Hand</td><td class="text-right pr-3">LKR <?php echo number_format($wallet->cashinhand, 2); ?></td></tr>
                                <tr class="font-weight-bold"><td class="pl-3">Bank / Card Balance</td><td class="text-right pr-3">LKR <?php echo number_format($wallet->bankbalance, 2); ?></td></tr>
                                <tr class="font-weight-bold table-active"><td class="pl-3">Total Available Funds</td><td class="text-right pr-3">LKR <?php echo number_format($wallet->totalavailable, 2); ?></td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </main>
        <?php include "include/footerbar.php"; ?>
    </div>
</div>
<?php include "include/footerscripts.php"; ?>
<?php include "include/footer.php"; ?>