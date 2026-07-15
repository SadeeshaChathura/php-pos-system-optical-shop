<?php 
$controllermenu = $this->router->fetch_class();
$functionmenu = uri_string();
$functionmenu2 = $this->router->fetch_method();

$menuprivilegearray = $menuaccess;

// Define menu configuration for cleaner privilege checking
$menuConfig = [
    'Useraccount' => 1,
    'Usertype' => 2,
    'Userprivilege' => 3,
    'Directsale' => 4,
    'Customer' => 5,
    'Supplier' => 6,
    'Purchaseorder' => 7,
    'Goodreceive' => 8,
    'Materialcategory' => 9,
    'Materialdetail' => 10,
    'Rptmatstock' => 11,
    'Rptmatstockbatchwise' => 12,
    'Company' => 13,
    'Companybranch' => 14,
    'Invoiceview' => 15,
    'Cashier' => 16
];

// Determine which menu ID to check
$currentMenuID = null;
if (isset($menuConfig[$functionmenu2])) {
    $currentMenuID = $menuConfig[$functionmenu2];
} elseif (isset($menuConfig[$functionmenu])) {
    $currentMenuID = $menuConfig[$functionmenu];
}

// Set privilege checks
if ($currentMenuID !== null) {
    $addcheck = checkprivilege($menuprivilegearray, $currentMenuID, 1);
    $editcheck = checkprivilege($menuprivilegearray, $currentMenuID, 2);
    $statuscheck = checkprivilege($menuprivilegearray, $currentMenuID, 3);
    $deletecheck = checkprivilege($menuprivilegearray, $currentMenuID, 4);
}

function checkprivilege($arraymenu, $menuID, $type){
    foreach($arraymenu as $array){
        if($array->menuid == $menuID){
            if($type == 1) return $array->add;
            else if($type == 2) return $array->edit;
            else if($type == 3) return $array->statuschange;
            else if($type == 4) return $array->remove;
        }
    }
    return 0;
}
?>
<textarea class="d-none" id="actiontext"><?php if($this->session->flashdata('msg')) {echo $this->session->flashdata('msg');} ?></textarea>

<nav class="sidenav shadow-right sidenav-light">
    <div class="sidenav-menu">
        <div class="nav accordion" id="accordionSidenav">
            
            <!-- DASHBOARD - Core -->
            <div class="sidenav-menu-heading">Core</div>
            <a class="nav-link p-0 px-3 py-2" href="<?php echo base_url().'Welcome/Dashboard'; ?>">
                <div class="nav-link-icon"><i class="fas fa-desktop"></i></div>
                Dashboard
            </a>

            <!-- COMPANY MANAGEMENT -->
            <?php if(menucheck($menuprivilegearray, 13) == 1 || menucheck($menuprivilegearray, 14) == 1){ ?>
            <div class="sidenav-menu-heading mt-3">Company</div>
            <a class="nav-link p-0 px-3 py-2 collapsed" href="javascript:void(0);" data-toggle="collapse" data-target="#collapseCompany" aria-expanded="false" aria-controls="collapseCompany">
                <div class="nav-link-icon"><i class="fas fa-building"></i></div>
                Company Management
                <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse <?php if($controllermenu == "Company" || $controllermenu == "Companybranch"){ echo 'show'; } ?>" id="collapseCompany" data-parent="#accordionSidenav">
                <nav class="sidenav-menu-nested nav accordion">
                    <?php if(menucheck($menuprivilegearray, 13) == 1){ ?>
                    <a class="nav-link p-0 px-3 py-1" href="<?php echo base_url().'Company'; ?>">
                        <i class="fas fa-building mr-2"></i>Company
                    </a>
                    <?php } ?>
                    <?php if(menucheck($menuprivilegearray, 14) == 1){ ?>
                    <a class="nav-link p-0 px-3 py-1" href="<?php echo base_url().'Companybranch'; ?>">
                        <i class="fas fa-store-alt mr-2"></i>Company Branch
                    </a>
                    <?php } ?>
                </nav>
            </div>
            <?php } ?>

            <!-- CUSTOMER & SUPPLIER MANAGEMENT -->
            <?php if(menucheck($menuprivilegearray, 5) == 1 || menucheck($menuprivilegearray, 6) == 1){ ?>
            <div class="sidenav-menu-heading mt-3">Partners</div>
            <?php if(menucheck($menuprivilegearray, 5) == 1){ ?> 
            <a class="nav-link p-0 px-3 py-2" href="<?php echo base_url().'Customer'; ?>">
                <div class="nav-link-icon"><i class="fas fa-user-friends"></i></div>
                Customers
            </a>
            <?php } ?>
            <?php if(menucheck($menuprivilegearray, 6) == 1){ ?> 
            <a class="nav-link p-0 px-3 py-2" href="<?php echo base_url().'Supplier'; ?>">
                <div class="nav-link-icon"><i class="fas fa-handshake"></i></div>
                Suppliers
            </a>   
            <?php } ?>
            <?php } ?>

            <!-- PRODUCT MANAGEMENT -->
            <?php if(menucheck($menuprivilegearray, 9) == 1 || menucheck($menuprivilegearray, 10) == 1){ ?>
            <div class="sidenav-menu-heading mt-3">Products</div>
            <a class="nav-link p-0 px-3 py-2 collapsed" href="javascript:void(0);" data-toggle="collapse" data-target="#collapsematerialinfo" aria-expanded="false" aria-controls="collapsematerialinfo">
                <div class="nav-link-icon"><i class="fas fa-shopping-basket"></i></div>
                Product Management
                <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse <?php if($controllermenu == "Materialcategory" || $controllermenu == "Materialdetail"){ echo 'show'; } ?>" id="collapsematerialinfo" data-parent="#accordionSidenav">
                <nav class="sidenav-menu-nested nav accordion">
                    <?php if(menucheck($menuprivilegearray, 9) == 1){ ?>
                    <a class="nav-link p-0 px-3 py-1" href="<?php echo base_url().'Materialcategory'; ?>">
                        <i class="fas fa-tags mr-2"></i>Product Categories
                    </a>
                    <?php } ?>
                    <?php if(menucheck($menuprivilegearray, 10) == 1){ ?>
                    <a class="nav-link p-0 px-3 py-1" href="<?php echo base_url().'Materialdetail'; ?>">
                        <i class="fas fa-cube mr-2"></i>Product Details
                    </a>
                    <?php } ?>
                </nav>
            </div> 
            <?php } ?>

            <!-- PROCUREMENT (PO & GRN) -->
            <?php if(menucheck($menuprivilegearray, 7) == 1 || menucheck($menuprivilegearray, 8) == 1){ ?>
            <div class="sidenav-menu-heading mt-3">Procurement</div>
            <a class="nav-link p-0 px-3 py-2 collapsed" href="javascript:void(0);" data-toggle="collapse" data-target="#collapsepordergrn" aria-expanded="false" aria-controls="collapsepordergrn">
                <div class="nav-link-icon"><i class="fas fa-truck"></i></div>
                Procurement Management
                <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse <?php if($controllermenu == "Purchaseorder" || $controllermenu == "Goodreceive"){ echo 'show'; } ?>" id="collapsepordergrn" data-parent="#accordionSidenav">
                <nav class="sidenav-menu-nested nav accordion">
                    <?php if(menucheck($menuprivilegearray, 7) == 1){ ?>
                    <a class="nav-link p-0 px-3 py-1" href="<?php echo base_url().'Purchaseorder'; ?>">
                        <i class="fas fa-file-signature mr-2"></i>Purchase Orders
                    </a>
                    <?php } ?>
                    <?php if(menucheck($menuprivilegearray, 8) == 1){ ?>
                    <a class="nav-link p-0 px-3 py-1" href="<?php echo base_url().'Goodreceive'; ?>">
                        <i class="fas fa-clipboard-check mr-2"></i>Good Receive Notes
                    </a>
                    <?php } ?>
                </nav>
            </div>
            <?php } ?>

            <!-- SALES & INVOICING -->
            <?php if(menucheck($menuprivilegearray, 4) == 1 || menucheck($menuprivilegearray, 15) == 1 || menucheck($menuprivilegearray, 16) == 1){ ?>
            <div class="sidenav-menu-heading mt-3">Sales</div>
            <a class="nav-link p-0 px-3 py-2 collapsed" href="javascript:void(0);" data-toggle="collapse" data-target="#collapseshopinfo" aria-expanded="false" aria-controls="collapseshopinfo">
                <div class="nav-link-icon"><i class="fas fa-cash-register"></i></div>
                Invoice Management
                <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse <?php if($controllermenu == "Directsale" || $controllermenu == "Invoiceview" || $controllermenu == "Cashier"){ echo 'show'; } ?>" id="collapseshopinfo" data-parent="#accordionSidenav">
                <nav class="sidenav-menu-nested nav accordion">
                    <?php if(menucheck($menuprivilegearray, 4) == 1){ ?>
                    <a class="nav-link p-0 px-3 py-1" href="<?php echo base_url().'Directsale'; ?>">
                        <i class="fas fa-file-invoice mr-2"></i>New Invoice
                    </a> 
                    <?php } ?>
                    <?php if(menucheck($menuprivilegearray, 16) == 1){ ?>
                    <a class="nav-link p-0 px-3 py-1" href="<?php echo base_url().'Cashier'; ?>">
                        <i class="fas fa-money-bill-wave mr-2"></i>Cashier
                    </a>
                    <?php } ?>
                    <?php if(menucheck($menuprivilegearray, 15) == 1){ ?>
                    <a class="nav-link p-0 px-3 py-1" href="<?php echo base_url().'Invoiceview'; ?>">
                        <i class="fas fa-eye mr-2"></i>View Invoices
                    </a>
                    <?php } ?>
                </nav>
            </div>
            <?php } ?>

            <!-- INVENTORY / STOCK MANAGEMENT -->
            <?php if(menucheck($menuprivilegearray, 11) == 1 || menucheck($menuprivilegearray, 12) == 1){ ?>
            <div class="sidenav-menu-heading mt-3">Inventory</div>
            <a class="nav-link p-0 px-3 py-2 collapsed" href="javascript:void(0);" data-toggle="collapse" data-target="#collapsestockinfo" aria-expanded="false" aria-controls="collapsestockinfo">
                <div class="nav-link-icon"><i class="fas fa-store"></i></div>
                Stock Management
                <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse <?php if($controllermenu == "Rptmatstock" || $controllermenu == "Rptmatstockbatchwise"){ echo 'show'; } ?>" id="collapsestockinfo" data-parent="#accordionSidenav">
                <nav class="sidenav-menu-nested nav accordion">
                    <?php if(menucheck($menuprivilegearray, 11) == 1){ ?>
                    <a class="nav-link p-0 px-3 py-1" href="<?php echo base_url().'Rptmatstock'; ?>">
                        <i class="fas fa-boxes mr-2"></i>Item Stock
                    </a>
                    <?php } ?>
                    <?php if(menucheck($menuprivilegearray, 12) == 1){ ?>
                    <a class="nav-link p-0 px-3 py-1" href="<?php echo base_url().'Rptmatstockbatchwise'; ?>">
                        <i class="fas fa-layer-group mr-2"></i>Item Stock (Batchwise)
                    </a>
                    <?php } ?>
                </nav>
            </div>
            <?php } ?>

            <!-- USER MANAGEMENT -->
            <?php if(menucheck($menuprivilegearray, 1) == 1 || menucheck($menuprivilegearray, 2) == 1 || menucheck($menuprivilegearray, 3) == 1){ ?>
            <div class="sidenav-menu-heading mt-3">System</div>
            <a class="nav-link p-0 px-3 py-2 collapsed" href="javascript:void(0);" data-toggle="collapse" data-target="#collapseUser" aria-expanded="false" aria-controls="collapseUser">
                <div class="nav-link-icon"><i class="fas fa-user-cog"></i></div>
                User Management
                <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse <?php if($functionmenu2 == "Useraccount" || $functionmenu2 == "Usertype" || $functionmenu2 == "Userprivilege"){ echo 'show'; } ?>" id="collapseUser" data-parent="#accordionSidenav">
                <nav class="sidenav-menu-nested nav accordion">
                    <?php if(menucheck($menuprivilegearray, 1) == 1){ ?>
                    <a class="nav-link p-0 px-3 py-1" href="<?php echo base_url().'User/Useraccount'; ?>">
                        <i class="fas fa-users mr-2"></i>User Accounts
                    </a>
                    <?php } ?>
                    <?php if(menucheck($menuprivilegearray, 2) == 1){ ?>
                    <a class="nav-link p-0 px-3 py-1" href="<?php echo base_url().'User/Usertype'; ?>">
                        <i class="fas fa-user-tag mr-2"></i>User Types
                    </a>
                    <?php } ?>
                    <?php if(menucheck($menuprivilegearray, 3) == 1){ ?>
                    <a class="nav-link p-0 px-3 py-1" href="<?php echo base_url().'User/Userprivilege'; ?>">
                        <i class="fas fa-lock mr-2"></i>User Privileges
                    </a>
                    <?php } ?>
                </nav>
            </div>
            <?php } ?>

        </div>
    </div>
    
    <!-- FOOTER -->
    <div class="sidenav-footer">
        <div class="sidenav-footer-content">
            <div class="sidenav-footer-subtitle">Logged in as:</div>
            <div class="sidenav-footer-title"><?php echo ucfirst($_SESSION['name']); ?></div>
        </div>
    </div>
</nav>