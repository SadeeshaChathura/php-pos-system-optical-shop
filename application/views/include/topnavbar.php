<nav class="topnav navbar navbar-expand shadow navbar-light" id="sidenavAccordion">

    <a class="navbar-brand d-none d-sm-flex align-items-center" href="<?php echo base_url() ?>Welcome/Dashboard">
        <img src="<?php echo base_url() ?>images/QP logo transparent.png" class="navbar-brand-logo" alt="Logo" style="width:75%; height: 16%">
    </a>

    <button class="btn btn-icon btn-transparent-light order-1 order-lg-0 mr-lg-2" id="sidebarToggle" href="#">
        <i data-feather="menu"></i>
    </button>

    <ul class="navbar-nav align-items-center ml-auto">

        <li class="nav-item mr-3 d-none d-md-block">
            <span class="qp-clock-badge">
                <i data-feather="clock"></i>
                <span id="currentDateTime"></span>
            </span>
        </li>

        <li class="nav-item dropdown no-caret dropdown-user">
            <a class="btn btn-icon btn-transparent-dark dropdown-toggle qp-user-trigger" id="navbarDropdownUserImage" href="javascript:void(0);" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <img src="<?php echo base_url() ?>images/user7.png" alt="User avatar">
            </a>
            <div class="dropdown-menu dropdown-menu-right border-0 shadow animated--fade-in-up" aria-labelledby="navbarDropdownUserImage">
                <h6 class="dropdown-header d-flex align-items-center">
                    <img class="dropdown-user-img" src="<?php echo base_url() ?>images/user7.png" alt="User avatar" />
                    <div class="dropdown-user-details">
                        <div class="dropdown-user-details-name"><?php echo ucfirst($_SESSION['name']); ?></div>
                        <div class="dropdown-user-details-email text-light"><?php echo $_SESSION['typename']; ?></div>
                    </div>
                </h6>
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
    var day = ('0' + currentDate.getDate()).slice(-2);
    var month = ('0' + (currentDate.getMonth() + 1)).slice(-2);
    var year = currentDate.getFullYear();
    var hours = ('0' + currentDate.getHours()).slice(-2);
    var minutes = ('0' + currentDate.getMinutes()).slice(-2);
    var seconds = ('0' + currentDate.getSeconds()).slice(-2);

    var dateTimeString = year + '-' + month + '-' + day + '  ' + hours + ':' + minutes + ':' + seconds;

    document.getElementById('currentDateTime').textContent = dateTimeString;
}

updateDateTime();
setInterval(updateDateTime, 1000);
</script>