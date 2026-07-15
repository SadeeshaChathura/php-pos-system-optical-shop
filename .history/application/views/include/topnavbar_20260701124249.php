<nav class="topnav navbar navbar-expand shadow navbar-light" id="sidenavAccordion">
<a class="navbar-brand d-none d-sm-block" href="<?php echo base_url() ?>Welcome/Dashboard"><img src="<?php echo base_url() ?>images/QP logo transparent.png" class="img-fluid" alt="" style="width:75%; height: 16%"><button class="btn btn-icon btn-transparent-light order-1 order-lg-0 mr-lg-2" id="sidebarToggle" href="#"><i data-feather="menu"></i></button>
    <ul class="navbar-nav align-items-center ml-auto">
        <li class="nav-item dropdown no-caret mr-3 dropdown-user">
        <a class="btn btn-link"><h3 class="dashboard-link text-light m-0" id="currentDateTime"></h3></a>
            <a class="btn btn-icon btn-transparent-dark dropdown-toggle" id="navbarDropdownUserImage" href="javascript:void(0);" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img src="<?php echo base_url() ?>images/user7.png" class="img-fluid" alt=""></a>
            <div class="dropdown-menu dropdown-menu-right border-0 shadow animated--fade-in-up" aria-labelledby="navbarDropdownUserImage">
                <h6 class="dropdown-header d-flex align-items-center">
                    <img class="dropdown-user-img" src="<?php echo base_url() ?>images/logout.png" />
                    <div class="dropdown-user-details">
                        <div class="dropdown-user-details-name"><?php echo ucfirst($_SESSION['name']); ?></div>
                        <div class="dropdown-user-details-email"><?php echo $_SESSION['typename']; ?></div>
                    </div>
                </h6>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="<?php echo base_url() ?>Welcome/Logout">
                    <div class="dropdown-item-icon"><i data-feather="log-out"></i></div>
                    Logout
                </a>
            </div>
        </li>
    </ul>
</nav>
<script>
function updateDateTime() {
    var currentDate = new Date();
    var day = currentDate.getDate();
    var month = currentDate.getMonth() + 1;
    var year = currentDate.getFullYear();
    var hours = currentDate.getHours();
    var minutes = currentDate.getMinutes();
    var seconds = currentDate.getSeconds();

    day = (day < 10 ? '0' : '') + day;
    month = (month < 10 ? '0' : '') + month;
    hours = (hours < 10 ? '0' : '') + hours;
    minutes = (minutes < 10 ? '0' : '') + minutes;
    seconds = (seconds < 10 ? '0' : '') + seconds;

    var dateTimeString = year + '-' + month + '-' + day + ' ' + hours + ':' + minutes + ':' + seconds;

    document.getElementById('currentDateTime').textContent = dateTimeString;
}

updateDateTime();

setInterval(updateDateTime, 1000);
</script>