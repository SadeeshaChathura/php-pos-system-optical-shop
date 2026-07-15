<nav class="topnav navbar navbar-expand shadow-sm navbar-light" id="sidenavAccordion">

    <a class="navbar-brand d-none d-sm-flex align-items-center" href="<?php echo base_url() ?>Welcome/Dashboard">
        <img src="<?php echo base_url() ?>images/QP logo transparent.png" class="navbar-brand-logo" alt="Logo">
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
            <a class="dropdown-toggle qp-user-trigger" id="navbarDropdownUserImage" href="javascript:void(0);" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <img src="<?php echo base_url() ?>images/user7.png" alt="User avatar">
            </a>
            <div class="dropdown-menu dropdown-menu-right border-0 shadow animated--fade-in-up" aria-labelledby="navbarDropdownUserImage">
                <h6 class="dropdown-header d-flex align-items-center">
                    <img class="dropdown-user-img" src="<?php echo base_url() ?>images/user7.png" alt="User avatar" />
                    <div class="dropdown-user-details">
                        <div class="dropdown-user-details-name"><?php echo ucfirst($_SESSION['name']); ?></div>
                        <div class="dropdown-user-details-email"><?php echo $_SESSION['typename']; ?></div>
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

<style>
:root{
    --qp-accent: #0d7890;      /* swap per client e.g. #254F96 for navy */
    --qp-accent-dark: #095c70;
    --qp-text-muted: #6b7785;
    --qp-border: #eef1f4;
}

.topnav{
    background: #ffffff;
    border-bottom: 1px solid var(--qp-border);
    padding: 0.55rem 1.25rem;
    min-height: 64px;
}

.navbar-brand-logo{
    width: auto;
    height: 34px;
    object-fit: contain;
}

#sidebarToggle{
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: #f4f6f8;
    color: #444;
    transition: background .15s ease, color .15s ease;
}
#sidebarToggle:hover{
    background: var(--qp-accent);
    color: #fff;
}

/* Clock pill */
.qp-clock-badge{
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: #f4f8f9;
    border: 1px solid var(--qp-border);
    color: #384352;
    font-size: 0.85rem;
    font-weight: 500;
    letter-spacing: 0.2px;
    padding: 6px 14px;
    border-radius: 999px;
}
.qp-clock-badge i{
    width: 15px;
    height: 15px;
    color: var(--qp-accent);
}
.qp-clock-badge span{
    font-variant-numeric: tabular-nums;
}

/* User trigger */
.qp-user-trigger{
    display: inline-flex;
    align-items: center;
    padding: 2px;
    border-radius: 50%;
    border: 2px solid transparent;
    transition: border-color .15s ease;
}
.qp-user-trigger:hover{
    border-color: var(--qp-accent);
}
.qp-user-trigger img{
    width: 38px;
    height: 38px;
    border-radius: 50%;
    object-fit: cover;
}

/* Dropdown */
.dropdown-user .dropdown-menu{
    min-width: 260px;
    border-radius: 12px;
    padding: 0;
    overflow: hidden;
    margin-top: 10px;
}
.dropdown-user .dropdown-header{
    background: linear-gradient(135deg, var(--qp-accent), var(--qp-accent-dark));
    padding: 18px 16px;
    margin: 0;
    border: none;
}
.dropdown-user-img{
    width: 44px;
    height: 44px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid rgba(255,255,255,0.6);
    margin-right: 12px;
}
.dropdown-user-details-name{
    color: #fff;
    font-weight: 600;
    font-size: 0.95rem;
    line-height: 1.2;
}
.dropdown-user-details-email{
    color: rgba(255,255,255,0.8);
    font-size: 0.78rem;
    text-transform: capitalize;
}
.dropdown-user .dropdown-item{
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 12px 16px;
    font-size: 0.9rem;
    color: #3a4552;
    transition: background .15s ease;
}
.dropdown-user .dropdown-item:hover{
    background: #f4f8f9;
    color: var(--qp-accent-dark);
}
.dropdown-item-icon{
    display: flex;
    align-items: center;
}
.dropdown-item-icon i{
    width: 16px;
    height: 16px;
}
</style>

<script>
function updateDateTime() {
    var now = new Date();
    var day = ('0' + now.getDate()).slice(-2);
    var month = ('0' + (now.getMonth() + 1)).slice(-2);
    var year = now.getFullYear();
    var hours = ('0' + now.getHours()).slice(-2);
    var minutes = ('0' + now.getMinutes()).slice(-2);
    var seconds = ('0' + now.getSeconds()).slice(-2);

    document.getElementById('currentDateTime').textContent =
        year + '-' + month + '-' + day + '  ' + hours + ':' + minutes + ':' + seconds;
}

updateDateTime();
setInterval(updateDateTime, 1000);
</script>