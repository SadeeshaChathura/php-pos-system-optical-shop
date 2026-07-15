<style>
    /* default (expanded sidebar) */
.menu-text {
    display: inline-block;
    margin-left: 10px;
}

/* collapsed sidebar mode */
.sidenav-collapsed .menu-text {
    display: none;
}

/* center icons when collapsed */
.sidenav-collapsed .nav-link {
    justify-content: center;
}

.sidenav-collapsed .nav-link-icon {
    margin-right: 0;
}

/* hide arrows in collapsed mode */
.sidenav-collapsed .sidenav-collapse-arrow {
    display: none;
}
</style>
<?php 
$controllermenu=$this->router->fetch_class();
$functionmenu=uri_string();
$functionmenu2=$this->router->fetch_method();

$menuprivilegearray=$menuaccess;

if($functionmenu2=='Useraccount'){
    $addcheck=checkprivilege($menuprivilegearray, 1, 1);
    $editcheck=checkprivilege($menuprivilegearray, 1, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 1, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 1, 4);
}
else if($functionmenu2=='Usertype'){
    $addcheck=checkprivilege($menuprivilegearray, 2, 1);
    $editcheck=checkprivilege($menuprivilegearray, 2, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 2, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 2, 4);
}
else if($functionmenu2=='Userprivilege'){
    $addcheck=checkprivilege($menuprivilegearray, 3, 1);
    $editcheck=checkprivilege($menuprivilegearray, 3, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 3, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 3, 4);
}
else if($functionmenu=='Directsale'){
    $addcheck=checkprivilege($menuprivilegearray, 4, 1);
    $editcheck=checkprivilege($menuprivilegearray, 4, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 4, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 4, 4);
}
else if($functionmenu=='Customer'){
    $addcheck=checkprivilege($menuprivilegearray, 5, 1);
    $editcheck=checkprivilege($menuprivilegearray, 5, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 5, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 5, 4);
}
else if($functionmenu=='Supplier'){
    $addcheck=checkprivilege($menuprivilegearray, 6, 1);
    $editcheck=checkprivilege($menuprivilegearray, 6, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 6, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 6, 4);
}
else if($functionmenu=='Purchaseorder'){
    $addcheck=checkprivilege($menuprivilegearray, 7, 1);
    $editcheck=checkprivilege($menuprivilegearray, 7, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 7, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 7, 4);
}
else if($functionmenu=='Goodreceive'){
    $addcheck=checkprivilege($menuprivilegearray, 8, 1);
    $editcheck=checkprivilege($menuprivilegearray, 8, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 8, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 8, 4);
}
else if($functionmenu=='Materialcategory'){
    $addcheck=checkprivilege($menuprivilegearray, 9, 1);
    $editcheck=checkprivilege($menuprivilegearray, 9, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 9, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 9, 4);
}
else if($functionmenu=='Materialdetail'){
    $addcheck=checkprivilege($menuprivilegearray, 10, 1);
    $editcheck=checkprivilege($menuprivilegearray, 10, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 10, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 10, 4);
}
else if($functionmenu=='Rptmatstock'){
    $addcheck=checkprivilege($menuprivilegearray, 11, 1);
    $editcheck=checkprivilege($menuprivilegearray, 11, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 11, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 11, 4);
}
else if($functionmenu=='Rptmatstockbatchwise'){
    $addcheck=checkprivilege($menuprivilegearray, 12, 1);
    $editcheck=checkprivilege($menuprivilegearray, 12, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 12, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 12, 4);
}
else if($functionmenu=='Company'){
    $addcheck=checkprivilege($menuprivilegearray, 13, 1);
    $editcheck=checkprivilege($menuprivilegearray, 13, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 13, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 13, 4);
}
else if($functionmenu=='Companybranch'){
    $addcheck=checkprivilege($menuprivilegearray, 14, 1);
    $editcheck=checkprivilege($menuprivilegearray, 14, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 14, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 14, 4);
}
else if($functionmenu=='Invoiceview'){
    $addcheck=checkprivilege($menuprivilegearray, 15, 1);
    $editcheck=checkprivilege($menuprivilegearray, 15, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 15, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 15, 4);
}
else if($functionmenu=='Cashier'){
    $addcheck=checkprivilege($menuprivilegearray, 16, 1);
    $editcheck=checkprivilege($menuprivilegearray, 16, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 16, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 16, 4);
}

function checkprivilege($arraymenu, $menuID, $type){
    foreach($arraymenu as $array){
        if($array->menuid==$menuID){
            if($type==1){
                return $array->add;
            }
            else if($type==2){
                return $array->edit;
            }
            else if($type==3){
                return $array->statuschange;
            }
            else if($type==4){
                return $array->remove;
            }
        }
    }
}
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
scr