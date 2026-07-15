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
else if($functionmenu=='RptGRN'){
    $addcheck=checkprivilege($menuprivilegearray, 16, 1);
    $editcheck=checkprivilege($menuprivilegearray, 16, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 16, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 16, 4);
}
else if($functionmenu=='RptSales'){
    $addcheck=checkprivilege($menuprivilegearray, 17, 1);
    $editcheck=checkprivilege($menuprivilegearray, 17, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 17, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 17, 4);
}
else if($functionmenu=='Prescription'){
    $addcheck=checkprivilege($menuprivilegearray, 17, 1);
    $editcheck=checkprivilege($menuprivilegearray, 17, 2);
    $statuscheck=checkprivilege($menuprivilegearray, 17, 3);
    $deletecheck=checkprivilege($menuprivilegearray, 17, 4);
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
<textarea class="d-none" id="actiontext"><?php if($this->session->flashdata('msg')) {echo $this->session->flashdata('msg');} ?></textarea>

<nav class="sidenav shadow-right sidenav-light">
    <div class="sidenav-menu">
        <div class="nav accordion" id="accordionSidenav">

            <div class="sidenav-item" data-flyout-title="Dashboard">
                <a class="nav-link<?php if($functionmenu2=='Dashboard'){echo ' active';} ?>" data-title="Dashboard" href="<?php echo base_url().'Welcome/Dashboard'; ?>">
                    <div class="nav-link-icon"><i class="fas fa-th-large"></i></div>
                    <span class="nav-link-text">Dashboard</span>
                </a>
            </div>

            <?php if(menucheck($menuprivilegearray, 4)==1 | menucheck($menuprivilegearray, 15)==1){ ?>
            <div class="sidenav-menu-heading">Sales &amp; Billing</div>
            <div class="sidenav-item" data-flyout-title="Point of Sale">
                <a class="nav-link collapsed" data-title="Point of Sale" href="javascript:void(0);" data-toggle="collapse" data-target="#collapseSales" aria-expanded="false" aria-controls="collapseSales">
                    <div class="nav-link-icon"><i class="fas fa-cash-register"></i></div>
                    <span class="nav-link-text">Point of Sale</span>
                    <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
            </div>
            <div class="collapse <?php if($controllermenu=="Directsale" | $controllermenu=="Invoiceview"){echo 'show';} ?>" id="collapseSales" data-parent="#accordionSidenav">
                <nav class="sidenav-menu-nested nav accordion">
                    <?php if(menucheck($menuprivilegearray, 4)==1){ ?>
                    <a class="nav-link<?php if($controllermenu=="Directsale"){echo ' active';} ?>" href="<?php echo base_url().'Directsale'; ?>">New Invoice</a>
                    <?php } if(menucheck($menuprivilegearray, 15)==1){ ?>
                    <a class="nav-link<?php if($controllermenu=="Invoiceview"){echo ' active';} ?>" href="<?php echo base_url().'Invoiceview'; ?>">Invoice History</a>
                    <?php } ?>
                </nav>
            </div>
            <?php } ?>

            <?php if(menucheck($menuprivilegearray, 7)==1 | menucheck($menuprivilegearray, 8)==1 | menucheck($menuprivilegearray, 11)==1 | menucheck($menuprivilegearray, 12)==1){ ?>
            <div class="sidenav-menu-heading">Procurement &amp; Stock</div>
            <?php if(menucheck($menuprivilegearray, 7)==1 | menucheck($menuprivilegearray, 8)==1){ ?>
            <div class="sidenav-item" data-flyout-title="Purchasing">
                <a class="nav-link collapsed" data-title="Purchasing" href="javascript:void(0);" data-toggle="collapse" data-target="#collapsePO" aria-expanded="false" aria-controls="collapsePO">
                    <div class="nav-link-icon"><i class="fas fa-truck"></i></div>
                    <span class="nav-link-text">Purchasing</span>
                    <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
            </div>
            <div class="collapse <?php if($controllermenu=="Purchaseorder" | $controllermenu=="Goodreceive"){echo 'show';} ?>" id="collapsePO" data-parent="#accordionSidenav">
                <nav class="sidenav-menu-nested nav accordion">
                    <?php if(menucheck($menuprivilegearray, 7)==1){ ?>
                    <a class="nav-link<?php if($controllermenu=="Purchaseorder"){echo ' active';} ?>" href="<?php echo base_url().'Purchaseorder'; ?>">Purchase Order</a>
                    <?php } if(menucheck($menuprivilegearray, 8)==1){ ?>
                    <a class="nav-link<?php if($controllermenu=="Goodreceive"){echo ' active';} ?>" href="<?php echo base_url().'Goodreceive'; ?>">Good Receive Note</a>
                    <?php } ?>
                </nav>
            </div>
            <?php } if(menucheck($menuprivilegearray, 11)==1 | menucheck($menuprivilegearray, 12)==1){ ?>
            <div class="sidenav-item" data-flyout-title="Stock Reports">
                <a class="nav-link collapsed" data-title="Stock Reports" href="javascript:void(0);" data-toggle="collapse" data-target="#collapseStock" aria-expanded="false" aria-controls="collapseStock">
                    <div class="nav-link-icon"><i class="fas fa-boxes"></i></div>
                    <span class="nav-link-text">Stock Reports</span>
                    <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
            </div>
            <div class="collapse <?php if($controllermenu=="Rptmatstock" | $controllermenu=="Rptmatstockbatchwise"){echo 'show';} ?>" id="collapseStock" data-parent="#accordionSidenav">
                <nav class="sidenav-menu-nested nav accordion">
                    <?php if(menucheck($menuprivilegearray, 11)==1){ ?>
                    <a class="nav-link<?php if($controllermenu=="Rptmatstock"){echo ' active';} ?>" href="<?php echo base_url().'Rptmatstock'; ?>">Item Stock</a>
                    <?php } if(menucheck($menuprivilegearray, 12)==1){ ?>
                    <a class="nav-link<?php if($controllermenu=="Rptmatstockbatchwise"){echo ' active';} ?>" href="<?php echo base_url().'Rptmatstockbatchwise'; ?>">Item Stock (Batchwise)</a>
                    <?php } ?>
                </nav>
            </div>
            <?php } } ?>

            <?php if(menucheck($menuprivilegearray, 9)==1 | menucheck($menuprivilegearray, 10)==1){ ?>
            <div class="sidenav-menu-heading">Catalog</div>
            <div class="sidenav-item" data-flyout-title="Products">
                <a class="nav-link collapsed" data-title="Products" href="javascript:void(0);" data-toggle="collapse" data-target="#collapseCatalog" aria-expanded="false" aria-controls="collapseCatalog">
                    <div class="nav-link-icon"><i class="fas fa-tags"></i></div>
                    <span class="nav-link-text">Products</span>
                    <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
            </div>
            <div class="collapse <?php if($controllermenu=="Materialcategory" | $controllermenu=="Materialdetail"){echo 'show';} ?>" id="collapseCatalog" data-parent="#accordionSidenav">
                <nav class="sidenav-menu-nested nav accordion">
                    <?php if(menucheck($menuprivilegearray, 9)==1){ ?>
                    <a class="nav-link<?php if($controllermenu=="Materialcategory"){echo ' active';} ?>" href="<?php echo base_url().'Materialcategory'; ?>">Category</a>
                    <?php } if(menucheck($menuprivilegearray, 10)==1){ ?>
                    <a class="nav-link<?php if($controllermenu=="Materialdetail"){echo ' active';} ?>" href="<?php echo base_url().'Materialdetail'; ?>">Product Detail</a>
                    <?php } ?>
                </nav>
            </div>
            <?php } ?>

            <?php if(menucheck($menuprivilegearray, 5)==1 | menucheck($menuprivilegearray, 6)==1){ ?>
            <div class="sidenav-menu-heading">Business Partners</div>
            <?php if(menucheck($menuprivilegearray, 5)==1){ ?>
            <div class="sidenav-item" data-flyout-title="Customers">
                <a class="nav-link<?php if($controllermenu=="Customer"){echo ' active';} ?>" data-title="Customers" href="<?php echo base_url().'Customer'; ?>">
                    <div class="nav-link-icon"><i class="fas fa-user-friends"></i></div>
                    <span class="nav-link-text">Customers</span>
                </a>
            </div>
            <?php } if(menucheck($menuprivilegearray, 6)==1){ ?>
            <div class="sidenav-item" data-flyout-title="Suppliers">
                <a class="nav-link<?php if($controllermenu=="Supplier"){echo ' active';} ?>" data-title="Suppliers" href="<?php echo base_url().'Supplier'; ?>">
                    <div class="nav-link-icon"><i class="fas fa-dolly"></i></div>
                    <span class="nav-link-text">Suppliers</span>
                </a>
            </div>
            <?php } } ?>

            <?php if(menucheck($menuprivilegearray, 16)==1 | menucheck($menuprivilegearray, 17)==1){ ?>
            <div class="sidenav-menu-heading">Reports</div>
            <div class="sidenav-item" data-flyout-title="Reports">
                <a class="nav-link collapsed" data-title="Reports" href="javascript:void(0);" data-toggle="collapse" data-target="#collapseReports" aria-expanded="false" aria-controls="collapseReports">
                    <div class="nav-link-icon"><i class="fas fa-chart-line"></i></div>
                    <span class="nav-link-text">Reports</span>
                    <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
            </div>
            <div class="collapse <?php if($controllermenu=="RptGRN" | $controllermenu=="RptSales"){echo 'show';} ?>" id="collapseReports" data-parent="#accordionSidenav">
                <nav class="sidenav-menu-nested nav accordion">
                    <?php if(menucheck($menuprivilegearray, 16)==1){ ?>
                    <a class="nav-link<?php if($controllermenu=="RptGRN"){echo ' active';} ?>" href="<?php echo base_url().'RptGRN'; ?>">GRN Report</a>
                    <?php } if(menucheck($menuprivilegearray, 17)==1){ ?>
                    <a class="nav-link<?php if($controllermenu=="RptSales"){echo ' active';} ?>" href="<?php echo base_url().'RptSales'; ?>">Sales Report</a>
                    <?php } ?>
                </nav>
            </div>
            <?php } ?>

            <?php if(menucheck($menuprivilegearray, 13)==1 | menucheck($menuprivilegearray, 14)==1 | menucheck($menuprivilegearray, 1)==1 | menucheck($menuprivilegearray, 2)==1 | menucheck($menuprivilegearray, 3)==1){ ?>
            <div class="sidenav-menu-heading">Administration</div>
            <?php if(menucheck($menuprivilegearray, 13)==1 | menucheck($menuprivilegearray, 14)==1){ ?>
            <div class="sidenav-item" data-flyout-title="Company Info">
                <a class="nav-link collapsed" data-title="Company Info" href="javascript:void(0);" data-toggle="collapse" data-target="#collapseCompany" aria-expanded="false" aria-controls="collapseCompany">
                    <div class="nav-link-icon"><i class="fas fa-building"></i></div>
                    <span class="nav-link-text">Company Info</span>
                    <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
            </div>
            <div class="collapse <?php if($controllermenu=="Company" | $controllermenu=="Companybranch"){echo 'show';} ?>" id="collapseCompany" data-parent="#accordionSidenav">
                <nav class="sidenav-menu-nested nav accordion">
                    <?php if(menucheck($menuprivilegearray, 13)==1){ ?>
                    <a class="nav-link<?php if($controllermenu=="Company"){echo ' active';} ?>" href="<?php echo base_url().'Company'; ?>">Company</a>
                    <?php } if(menucheck($menuprivilegearray, 14)==1){ ?>
                    <a class="nav-link<?php if($controllermenu=="Companybranch"){echo ' active';} ?>" href="<?php echo base_url().'Companybranch'; ?>">Company Branch</a>
                    <?php } ?>
                </nav>
            </div>
            <?php } if(menucheck($menuprivilegearray, 1)==1 | menucheck($menuprivilegearray, 2)==1 | menucheck($menuprivilegearray, 3)==1){ ?>
            <div class="sidenav-item" data-flyout-title="Users & Access">
                <a class="nav-link collapsed" data-title="Users & Access" href="javascript:void(0);" data-toggle="collapse" data-target="#collapseUser" aria-expanded="false" aria-controls="collapseUser">
                    <div class="nav-link-icon"><i class="fas fa-user-shield"></i></div>
                    <span class="nav-link-text">Users &amp; Access</span>
                    <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
            </div>
            <div class="collapse <?php if($functionmenu2=="Useraccount" | $functionmenu2=="Usertype" | $functionmenu2=="Userprivilege"){echo 'show';} ?>" id="collapseUser" data-parent="#accordionSidenav">
                <nav class="sidenav-menu-nested nav accordion">
                    <?php if(menucheck($menuprivilegearray, 1)==1){ ?>
                    <a class="nav-link<?php if($functionmenu2=="Useraccount"){echo ' active';} ?>" data-title="User Account" href="<?php echo base_url().'User/Useraccount'; ?>">User Account</a>
                    <?php } if(menucheck($menuprivilegearray, 2)==1){ ?>
                    <a class="nav-link<?php if($functionmenu2=="Usertype"){echo ' active';} ?>" data-title="User Type" href="<?php echo base_url().'User/Usertype'; ?>">User Type</a>
                    <?php } if(menucheck($menuprivilegearray, 3)==1){ ?>
                    <a class="nav-link<?php if($functionmenu2=="Userprivilege"){echo ' active';} ?>" data-title="Privileges" href="<?php echo base_url().'User/Userprivilege'; ?>">Privileges</a>
                    <?php } ?>
                </nav>
            </div>
            <?php } } ?>

        </div>
    </div>

    <div class="sidenav-footer d-flex align-items-center">
        <div class="qp-avatar-initials"><?php echo strtoupper(substr($_SESSION['name'], 0, 1)); ?></div>
        <div class="sidenav-footer-content">
            <div class="sidenav-footer-subtitle">Logged in as</div>
            <div class="sidenav-footer-title"><?php echo ucfirst($_SESSION['name']); ?></div>
        </div>
    </div>
</nav>