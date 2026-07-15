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
<textarea class="d-none" id="actiontext"><?php if($this->session->flashdata('msg')) {echo $this->session->flashdata('msg');} ?></textarea>

<nav class="sidenav shadow-right sidenav-light">
    <div class="sidenav-menu">
        <div class="nav accordion" id="accordionSidenav">
            <div class="sidenav-menu-heading">Core</div>
            <a class="nav-link p-0 px-3 py-2" href="<?php echo base_url().'Welcome/Dashboard'; ?>">
                <div class="nav-link-icon"><i class="fas fa-desktop"></i></div>
                Dashboard
            </a>  
            <?php if(menucheck($menuprivilegearray, 13)==1 | menucheck($menuprivilegearray, 14)==1){ ?>
            <a class="nav-link p-0 px-3 py-2 collapsed" href="javascript:void(0);" data-toggle="collapse" data-target="#collapseCompany" aria-expanded="false" aria-controls="collapseCompany">
                <div class="nav-link-icon"><i class="fas fa-building"></i></div>
                Company Info
                <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse <?php if($controllermenu=="Company" | $controllermenu=="Companybranch"){echo 'show';} ?>" id="collapseCompany" data-parent="#accordionSidenav">
                <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages">
                    <?php if(menucheck($menuprivilegearray, 13)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1" href="<?php echo base_url().'Company'; ?>">Company</a>
                    <?php } if(menucheck($menuprivilegearray, 14)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1" href="<?php echo base_url().'Companybranch'; ?>">Company Branch</a>
                    <?php } ?>
                </nav>
            </div>
            <?php } if(menucheck($menuprivilegearray, 5)==1){ ?> 
            <a class="nav-link p-0 px-3 py-2" href="<?php echo base_url().'Customer'; ?>">
                <div class="nav-link-icon"><i class="fas fa-users"></i></div>
                Customer
            </a>
            <?php } if(menucheck($menuprivilegearray, 6)==1){ ?> 
            <a class="nav-link p-0 px-3 py-2" href="<?php echo base_url().'Supplier'; ?>">
                <div class="nav-link-icon"><i class="fas fa-users"></i></div>
                Supplier
            </a>   
            <?php } if(menucheck($menuprivilegearray, 9)==1 | menucheck($menuprivilegearray, 10)==1){ ?>
            <a class="nav-link p-0 px-3 py-2 collapsed" href="javascript:void(0);" data-toggle="collapse" data-target="#collapsematerialinfo" aria-expanded="false" aria-controls="collapsematerialinfo">
                <div class="nav-link-icon"><i class="fas fa-shopping-basket"></i></div>
                Product Info
                <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse <?php if($controllermenu=="Materialcategory" | $controllermenu=="Materialdetail" ){echo 'show';} ?>" id="collapsematerialinfo" data-parent="#accordionSidenav">
                <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages">
                    <?php if(menucheck($menuprivilegearray, 9)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1" href="<?php echo base_url().'Materialcategory'; ?>">Product Category</a>
                    <?php } if(menucheck($menuprivilegearray, 10)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1" href="<?php echo base_url().'Materialdetail'; ?>">Product Detail</a>
                    <?php } ?>
                </nav>
            </div> 
            <?php } if(menucheck($menuprivilegearray, 7)==1 | menucheck($menuprivilegearray, 8)==1){ ?>
            <a class="nav-link p-0 px-3 py-2 collapsed" href="javascript:void(0);" data-toggle="collapse" data-target="#collapsepordergrn" aria-expanded="false" aria-controls="collapsepordergrn">
                <div class="nav-link-icon"><i class="fas fa-truck"></i></div>
                Porder & GRN Info
                <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse <?php if($controllermenu=="Purchaseorder" | $controllermenu=="Goodreceive"){echo 'show';} ?>" id="collapsepordergrn" data-parent="#accordionSidenav">
                <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages">
                    <?php if(menucheck($menuprivilegearray, 7)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1" href="<?php echo base_url().'Purchaseorder'; ?>">Purchase Order</a>
                    <?php } if(menucheck($menuprivilegearray, 8)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1" href="<?php echo base_url().'Goodreceive'; ?>">Good Receive Note</a>
                    <?php } ?>
                </nav>
            </div>
            <?php } if(menucheck($menuprivilegearray, 4)==1 | menucheck($menuprivilegearray, 15) | menucheck($menuprivilegearray, 16)==1){ ?>
            <a class="nav-link p-0 px-3 py-2 collapsed" href="javascript:void(0);" data-toggle="collapse"
            	data-target="#collapseshopinfo" aria-expanded="false" aria-controls="collapseshopinfo">
            	<div class="nav-link-icon"><i class="fas fa-cash-register"></i></div>
            	Invoice Info
            	<div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse <?php if($controllermenu=="Directsale" | $controllermenu=="Invoiceview" | $controllermenu=="Cashier"){echo 'show';} ?>"
            	id="collapseshopinfo" data-parent="#accordionSidenav">
            	<nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages">
            		<?php if(menucheck($menuprivilegearray, 4)==1){ ?>
            		<a class="nav-link p-0 px-3 py-1" href="<?php echo base_url().'Directsale'; ?>">Invoice</a> 
                    <?php } if(menucheck($menuprivilegearray, 16)==1){ ?>
                        <a class="nav-link p-0 px-3 py-1" href="<?php echo base_url().'Cashier'; ?>">Cashier</a>
                    <?php } if(menucheck($menuprivilegearray, 15)==1){ ?>
                        <a class="nav-link p-0 px-3 py-1" href="<?php echo base_url().'Invoiceview'; ?>">View Invoice</a>
                    <?php } ?>
            	</nav>
            </div>
            <?php } if(menucheck($menuprivilegearray, 11)==1 | menucheck($menuprivilegearray, 12)==1){ ?>
            <a class="nav-link p-0 px-3 py-2 collapsed" href="javascript:void(0);" data-toggle="collapse" data-target="#collapsestockinfo" aria-expanded="false" aria-controls="collapsestockinfo">
                <div class="nav-link-icon"><i class="fas fa-store"></i></div>
                Stock Info
                <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse <?php if($controllermenu=="Rptmatstock" | $controllermenu=="Rptmatstockbatchwise"){echo 'show';} ?>" id="collapsestockinfo" data-parent="#accordionSidenav">
                <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages">
                    <?php if(menucheck($menuprivilegearray, 11)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1" href="<?php echo base_url().'Rptmatstock'; ?>">Item Stock</a>
                    <?php } if(menucheck($menuprivilegearray, 12)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1" href="<?php echo base_url().'Rptmatstockbatchwise'; ?>">Item Stock Batchwise</a>
                    <?php } ?>
                </nav>
            </div>
            <?php } if(menucheck($menuprivilegearray, 1)==1 | menucheck($menuprivilegearray, 2)==1 | menucheck($menuprivilegearray, 3)==1){ ?>
            <a class="nav-link p-0 px-3 py-2 collapsed" href="javascript:void(0);" data-toggle="collapse" data-target="#collapseUser" aria-expanded="false" aria-controls="collapseUser">
                <div class="nav-link-icon"><i class="fas fa-user"></i></div>
                User Info
                <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse <?php if($functionmenu2=="Useraccount" | $functionmenu2=="Usertype" | $functionmenu2=="Userprivilege"){echo 'show';} ?>" id="collapseUser" data-parent="#accordionSidenav">
                <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages">
                    <?php if(menucheck($menuprivilegearray, 1)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1" href="<?php echo base_url().'User/Useraccount'; ?>">User Account</a>
                    <?php } if(menucheck($menuprivilegearray, 2)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1" href="<?php echo base_url().'User/Usertype'; ?>">Type</a>
                    <?php } if(menucheck($menuprivilegearray, 3)==1){ ?>
                    <a class="nav-link p-0 px-3 py-1" href="<?php echo base_url().'User/Userprivilege'; ?>">Privilege</a>
                    <?php } ?>
                </nav>
            </div>
            <?php } ?>
        </div>
    </div>
    <div class="sidenav-footer">
        <div class="sidenav-footer-content">
            <div class="sidenav-footer-subtitle">Logged in as:</div>
            <div class="sidenav-footer-title"><?php echo ucfirst($_SESSION['name']); ?></div>
        </div>
    </div>
</nav>
