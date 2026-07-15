<?php 
include "include/header.php";  
include "include/topnavbar.php"; 
?>
<style>
    .icon-circle {
    display: inline-flex;
    justify-content: center;
    align-items: center;
    width: 80px;
    height: 80px;
    border-radius: 50%;
    margin-bottom: 15px;
}
.welcome-company-badge {
    display: inline-flex;
    align-items: center;
    background: rgba(255, 255, 255, 0.15);
    backdrop-filter: blur(10px);
    padding: 0.75rem 1.5rem;
    border-radius: 50px;
    border: 2px solid rgba(255, 255, 255, 0.3);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    transition: all 0.3s ease;
}
.welcome-company-badge:hover {
    background: rgba(255, 255, 255, 0.2);
    border-color: rgba(255, 255, 255, 0.5);
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
}
.welcome-company-badge .welcome-icon {
    background: rgba(255, 255, 255, 0.25);
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 0.75rem;
    font-size: 1.1rem;
}
.welcome-company-badge .welcome-text {
    font-size: 1rem;
    font-weight: 600;
    letter-spacing: 0.5px;
    text-transform: uppercase;
    margin-right: 0.5rem;
    opacity: 0.9;
}
.welcome-company-badge .company-name {
    font-size: 1.1rem;
    font-weight: 700;
    letter-spacing: 0.5px;
    color: #fff;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
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
                        <div class="d-flex justify-content-end flex-grow-1">
                            <div class="welcome-company-badge">
                                <div class="welcome-icon">
                                    <i class="fas fa-building"></i>
                                </div>
                                <span class="welcome-text">Welcome,</span>
                                <span class="company-name"><?php echo $_SESSION['company']; ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dashboard Cards -->
            <div class="container-fluid mt-4">
                <div class="row">
                    <div class="col-md-3">
                        <div class="card card-3d sales-card text-center p-4" style="background: #fff; border-radius: 15px; box-shadow: 5px 5px 15px rgba(0, 0, 0, 0.1), -5px -5px 15px rgba(255, 255, 255, 0.8); transition: transform 0.3s ease, box-shadow 0.3s ease;" onmouseover="this.style.transform='translateY(-10px)'; this.style.boxShadow='10px 10px 20px rgba(0, 0, 0, 0.15), -10px -10px 20px rgba(255, 255, 255, 0.9)';" onmouseout="this.style.transform=''; this.style.boxShadow='5px 5px 15px rgba(0, 0, 0, 0.1), -5px -5px 15px rgba(255, 255, 255, 0.8)';">
                            <!-- Circle icon container -->
                            <div class="icon-circle" style="background-color: #007bff;">
                                <i class="fas fa-dollar-sign" style="font-size: 2.5rem; color: white;"></i>
                            </div>
                            <h2 style="font-weight: bold; margin-bottom: 10px; color: #333; font-family: 'Arial', sans-serif; text-transform: uppercase; text-shadow: 2px 2px 6px rgba(0, 0, 0, 0.4), -2px -2px 6px rgba(255, 255, 255, 0.6);">Total Sales</h2>
                            <h2 style="font-weight: bold; color: #222; font-family: 'Arial', sans-serif;">LKR. 12,500</h2>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card card-3d orders-card text-center p-4" style="background: #fff; border-radius: 15px; box-shadow: 5px 5px 15px rgba(0, 0, 0, 0.1), -5px -5px 15px rgba(255, 255, 255, 0.8); transition: transform 0.3s ease, box-shadow 0.3s ease;" onmouseover="this.style.transform='translateY(-10px)'; this.style.boxShadow='10px 10px 20px rgba(0, 0, 0, 0.15), -10px -10px 20px rgba(255, 255, 255, 0.9)';" onmouseout="this.style.transform=''; this.style.boxShadow='5px 5px 15px rgba(0, 0, 0, 0.1), -5px -5px 15px rgba(255, 255, 255, 0.8)';">
                            <!-- Circle icon container -->
                            <div class="icon-circle" style="background-color: #28a745;">
                                <i class="fas fa-shopping-cart" style="font-size: 2.5rem; color: white;"></i>
                            </div>
                            <h2 style="font-weight: bold; margin-bottom: 10px; color: #333; font-family: 'Arial', sans-serif; text-transform: uppercase; text-shadow: 2px 2px 6px rgba(0, 0, 0, 0.4), -2px -2px 6px rgba(255, 255, 255, 0.6);">Orders</h2>
                            <h2 style="font-weight: bold; color: #222; font-family: 'Arial', sans-serif;">420</h2>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card card-3d customers-card text-center p-4" style="background: #fff; border-radius: 15px; box-shadow: 5px 5px 15px rgba(0, 0, 0, 0.1), -5px -5px 15px rgba(255, 255, 255, 0.8); transition: transform 0.3s ease, box-shadow 0.3s ease;" onmouseover="this.style.transform='translateY(-10px)'; this.style.boxShadow='10px 10px 20px rgba(0, 0, 0, 0.15), -10px -10px 20px rgba(255, 255, 255, 0.9)';" onmouseout="this.style.transform=''; this.style.boxShadow='5px 5px 15px rgba(0, 0, 0, 0.1), -5px -5px 15px rgba(255, 255, 255, 0.8)';">
                            <!-- Circle icon container -->
                            <div class="icon-circle" style="background-color: #ffc107;">
                                <i class="fas fa-users" style="font-size: 2.5rem; color: white;"></i>
                            </div>
                            <h2 style="font-weight: bold; margin-bottom: 10px; color: #333; font-family: 'Arial', sans-serif; text-transform: uppercase; text-shadow: 2px 2px 6px rgba(0, 0, 0, 0.4), -2px -2px 6px rgba(255, 255, 255, 0.6);">Customers</h2>
                            <h2 style="font-weight: bold; color: #222; font-family: 'Arial', sans-serif;">1,780</h2>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card card-3d stock-card text-center p-4" style="background: #fff; border-radius: 15px; box-shadow: 5px 5px 15px rgba(0, 0, 0, 0.1), -5px -5px 15px rgba(255, 255, 255, 0.8); transition: transform 0.3s ease, box-shadow 0.3s ease;" onmouseover="this.style.transform='translateY(-10px)'; this.style.boxShadow='10px 10px 20px rgba(0, 0, 0, 0.15), -10px -10px 20px rgba(255, 255, 255, 0.9)';" onmouseout="this.style.transform=''; this.style.boxShadow='5px 5px 15px rgba(0, 0, 0, 0.1), -5px -5px 15px rgba(255, 255, 255, 0.8)';">
                            <!-- Circle icon container -->
                            <div class="icon-circle" style="background-color: #dc3545;">
                                <i class="fas fa-boxes" style="font-size: 2.5rem; color: white;"></i>
                            </div>
                            <h2 style="font-weight: bold; margin-bottom: 10px; color: #333; font-family: 'Arial', sans-serif; text-transform: uppercase; text-shadow: 2px 2px 6px rgba(0, 0, 0, 0.4), -2px -2px 6px rgba(255, 255, 255, 0.6);">Stock</h2>
                            <h2 style="font-weight: bold; color: #222; font-family: 'Arial', sans-serif;">512</h2>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Charts Section -->
            <div class="container-fluid mt-4">
                <div class="row">
                    <!-- Sales Chart -->
                    <div class="col-md-8">
                        <div class="card card-3d p-4">
                            <h5 class="text-primary">Sales Overview</h5>
                            <div class="chart-container">
                                <canvas id="salesChart"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Orders Chart -->
                    <div class="col-md-4">
                        <div class="card card-3d p-4">
                            <h5 class="text-primary">Order Status</h5>
                            <div class="chart-container">
                                <canvas id="orderChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="container-fluid mt-4">
                <div class="row">
                    <div class="col-md-3">
                        <a href="<?php echo base_url() ?>Directsale" class="btn btn-primary btn-lg btn-block">
                            <i class="fas fa-plus-circle mr-2"></i> Add New Sale
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="<?php echo base_url() ?>Purchaseorder" class="btn btn-success btn-lg btn-block">
                            <i class="fas fa-truck mr-2"></i> Manage Orders
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="<?php echo base_url() ?>Customer" class="btn btn-warning btn-lg btn-block">
                            <i class="fas fa-user mr-2"></i> Customers
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="<?php echo base_url() ?>Reports" class="btn btn-danger btn-lg btn-block">
                            <i class="fas fa-chart-line mr-2"></i> Reports
                        </a>
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
    // Sales Chart
    var salesCtx = document.getElementById('salesChart').getContext('2d');
    var salesChart = new Chart(salesCtx, {
        type: 'bar',
        data: {
            labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun"],
            datasets: [{
                label: 'Sales ($)',
                data: [6000, 7500, 9000, 12000, 14000, 16000],
                backgroundColor: 'rgba(0, 123, 255, 0.5)',
                borderColor: '#007BFF',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });

    // Orders Chart
    var orderCtx = document.getElementById('orderChart').getContext('2d');
    var orderChart = new Chart(orderCtx, {
        type: 'pie',
        data: {
            labels: ["Pending", "Completed", "Cancelled"],
            datasets: [{
                data: [120, 260, 40],
                backgroundColor: ['#FFC107', '#28A745', '#DC3545']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });
});
</script>

<?php include "include/footer.php"; ?>
