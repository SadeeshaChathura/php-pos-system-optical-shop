<?php 
$controllermenu=$this->router->fetch_class();
$functionmenu=uri_string();
$functionmenu2=$this->router->fetch_method();

$menuprivilegearray=$menuaccess;
?>

<nav class="sidenav shadow-right sidenav-light" id="sidebar">
    <div class="sidenav-menu">
        <div class="nav accordion" id="accordionSidenav">

            <div class="sidenav-menu-heading">Core</div>

            <!-- Dashboard -->
            <a class="nav-link p-0 px-3 py-2 menu-link" href="<?php echo base_url().'Welcome/Dashboard'; ?>">
                <div class="nav-link-icon"><i class="fas fa-desktop"></i></div>
                <span class="menu-text">Dashboard</span>
            </a>

            <!-- Company Info -->
            <?php if(menucheck($menuprivilegearray, 13)==1 || menucheck($menuprivilegearray, 14)==1){ ?>
            <a class="nav-link p-0 px-3 py-2 collapsed menu-link"
               href="javascript:void(0);"
               data-toggle="collapse"
               data-target="#collapseCompany">

                <div class="nav-link-icon"><i class="fas fa-building"></i></div>
                <span class="menu-text">Company Info</span>
                <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>

            <div class="collapse <?php if($controllermenu=="Company" || $controllermenu=="Companybranch") echo 'show'; ?>"
                 id="collapseCompany"
                 data-parent="#accordionSidenav">

                <nav class="sidenav-menu-nested nav">
                    <?php if(menucheck($menuprivilegearray, 13)==1){ ?>
                    <a class="nav-link menu-link" href="<?php echo base_url().'Company'; ?>">
                        <span class="menu-text">Company</span>
                    </a>
                    <?php } ?>

                    <?php if(menucheck($menuprivilegearray, 14)==1){ ?>
                    <a class="nav-link menu-link" href="<?php echo base_url().'Companybranch'; ?>">
                        <span class="menu-text">Company Branch</span>
                    </a>
                    <?php } ?>
                </nav>
            </div>
            <?php } ?>

            <!-- Customer -->
            <?php if(menucheck($menuprivilegearray, 5)==1){ ?>
            <a class="nav-link p-0 px-3 py-2 menu-link" href="<?php echo base_url().'Customer'; ?>">
                <div class="nav-link-icon"><i class="fas fa-users"></i></div>
                <span class="menu-text">Customer</span>
            </a>
            <?php } ?>

            <!-- Supplier -->
            <?php if(menucheck($menuprivilegearray, 6)==1){ ?>
            <a class="nav-link p-0 px-3 py-2 menu-link" href="<?php echo base_url().'Supplier'; ?>">
                <div class="nav-link-icon"><i class="fas fa-users"></i></div>
                <span class="menu-text">Supplier</span>
            </a>
            <?php } ?>

            <!-- Product Info -->
            <?php if(menucheck($menuprivilegearray, 9)==1 || menucheck($menuprivilegearray, 10)==1){ ?>
            <a class="nav-link p-0 px-3 py-2 collapsed menu-link"
               href="javascript:void(0);"
               data-toggle="collapse"
               data-target="#collapsematerialinfo">

                <div class="nav-link-icon"><i class="fas fa-shopping-basket"></i></div>
                <span class="menu-text">Product Info</span>
                <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>

            <div class="collapse <?php if($controllermenu=="Materialcategory" || $controllermenu=="Materialdetail") echo 'show'; ?>"
                 id="collapsematerialinfo"
                 data-parent="#accordionSidenav">

                <nav class="sidenav-menu-nested nav">

                    <?php if(menucheck($menuprivilegearray, 9)==1){ ?>
                    <a class="nav-link menu-link" href="<?php echo base_url().'Materialcategory'; ?>">
                        <span class="menu-text">Product Category</span>
                    </a>
                    <?php } ?>

                    <?php if(menucheck($menuprivilegearray, 10)==1){ ?>
                    <a class="nav-link menu-link" href="<?php echo base_url().'Materialdetail'; ?>">
                        <span class="menu-text">Product Detail</span>
                    </a>
                    <?php } ?>

                </nav>
            </div>
            <?php } ?>

            <!-- PO & GRN -->
            <?php if(menucheck($menuprivilegearray, 7)==1 || menucheck($menuprivilegearray, 8)==1){ ?>
            <a class="nav-link p-0 px-3 py-2 collapsed menu-link"
               data-toggle="collapse"
               data-target="#collapsepordergrn">

                <div class="nav-link-icon"><i class="fas fa-truck"></i></div>
                <span class="menu-text">PO & GRN Info</span>
                <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>

            <div class="collapse <?php if($controllermenu=="Purchaseorder" || $controllermenu=="Goodreceive") echo 'show'; ?>"
                 id="collapsepordergrn"
                 data-parent="#accordionSidenav">

                <nav class="sidenav-menu-nested nav">

                    <?php if(menucheck($menuprivilegearray, 7)==1){ ?>
                    <a class="nav-link menu-link" href="<?php echo base_url().'Purchaseorder'; ?>">
                        <span class="menu-text">Purchase Order</span>
                    </a>
                    <?php } ?>

                    <?php if(menucheck($menuprivilegearray, 8)==1){ ?>
                    <a class="nav-link menu-link" href="<?php echo base_url().'Goodreceive'; ?>">
                        <span class="menu-text">Good Receive</span>
                    </a>
                    <?php } ?>

                </nav>
            </div>
            <?php } ?>

            <!-- Invoice -->
            <?php if(menucheck($menuprivilegearray, 4)==1 || menucheck($menuprivilegearray, 15)==1 || menucheck($menuprivilegearray, 16)==1){ ?>
            <a class="nav-link p-0 px-3 py-2 collapsed menu-link"
               data-toggle="collapse"
               data-target="#collapseshopinfo">

                <div class="nav-link-icon"><i class="fas fa-cash-register"></i></div>
                <span class="menu-text">Invoice Info</span>
                <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>

            <div class="collapse <?php if($controllermenu=="Directsale" || $controllermenu=="Invoiceview" || $controllermenu=="Cashier") echo 'show'; ?>"
                 id="collapseshopinfo"
                 data-parent="#accordionSidenav">

                <nav class="sidenav-menu-nested nav">

                    <?php if(menucheck($menuprivilegearray, 4)==1){ ?>
                    <a class="nav-link menu-link" href="<?php echo base_url().'Directsale'; ?>">
                        <span class="menu-text">Invoice</span>
                    </a>
                    <?php } ?>

                    <?php if(menucheck($menuprivilegearray, 16)==1){ ?>
                    <a class="nav-link menu-link" href="<?php echo base_url().'Cashier'; ?>">
                        <span class="menu-text">Cashier</span>
                    </a>
                    <?php } ?>

                    <?php if(menucheck($menuprivilegearray, 15)==1){ ?>
                    <a class="nav-link menu-link" href="<?php echo base_url().'Invoiceview'; ?>">
                        <span class="menu-text">View Invoice</span>
                    </a>
                    <?php } ?>

                </nav>
            </div>
            <?php } ?>

        </div>
    </div>
</nav>