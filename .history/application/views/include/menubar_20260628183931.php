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
            if($type==1) return $array->add;
            else if($type==2) return $array->edit;
            else if($type==3) return $array->statuschange;
            else if($type==4) return $array->remove;
        }
    }
}
?>

<style>
/* ============================================================
   SIDEBAR — Modern collapsible with icon-only mode + tooltips
   Matches your existing #0d7890 / #1fb3c4 design system.
   ============================================================ */

:root {
  --sb-width-open:   240px;
  --sb-width-closed:  64px;
  --sb-bg:           #0b2b35;       /* deep teal-navy */
  --sb-bg2:          #0d3342;       /* slightly lighter for hover */
  --sb-accent:       #1fb3c4;
  --sb-accent2:      #0d7890;
  --sb-text:         #c8dfe4;
  --sb-text-muted:   #6a9aa8;
  --sb-heading:      #3d7080;
  --sb-active-bg:    rgba(31,179,196,.15);
  --sb-active-border:#1fb3c4;
  --sb-radius:       8px;
  --sb-transition:   all .22s cubic-bezier(.4,0,.2,1);
}

/* --- Reset nav wrapping so our sidebar is full-height --- */
#layoutSidenav_nav { width: var(--sb-width-open); transition: var(--sb-transition); flex-shrink: 0; }
#layoutSidenav { display: flex; }
#layoutSidenav_content { flex: 1; min-width: 0; transition: var(--sb-transition); }

/* Collapsed state applied to #layoutSidenav */
#layoutSidenav.sb-closed #layoutSidenav_nav   { width: var(--sb-width-closed); }
#layoutSidenav.sb-closed #layoutSidenav_content { margin-left: 0; }

/* --- The sidebar element itself --- */
.app-sidenav {
  width: var(--sb-width-open);
  min-height: 100vh;
  background: var(--sb-bg);
  display: flex;
  flex-direction: column;
  overflow: hidden;
  transition: var(--sb-transition);
  position: fixed;
  top: 0; left: 0;
  z-index: 1030;
  box-shadow: 2px 0 20px rgba(0,0,0,.25);
}

#layoutSidenav.sb-closed .app-sidenav {
  width: var(--sb-width-closed);
}

/* --- Brand / logo area --- */
.sb-brand {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 0 14px;
  height: 60px;
  background: rgba(0,0,0,.2);
  border-bottom: 1px solid rgba(255,255,255,.06);
  flex-shrink: 0;
  overflow: hidden;
  white-space: nowrap;
}
.sb-brand .sb-logo {
  width: 34px; height: 34px;
  border-radius: 9px;
  background: linear-gradient(135deg, var(--sb-accent2), var(--sb-accent));
  display: flex; align-items: center; justify-content: center;
  flex-shrink: 0;
  font-size: 16px; color: #fff; font-weight: 800;
}
.sb-brand .sb-title {
  font-size: 14px; font-weight: 700;
  color: #fff;
  letter-spacing: .3px;
  transition: var(--sb-transition);
  opacity: 1;
}
#layoutSidenav.sb-closed .sb-brand .sb-title { opacity: 0; width: 0; overflow: hidden; }

/* --- Toggle button inside sidebar --- */
.sb-toggle {
  position: absolute;
  top: 15px; right: 10px;
  width: 28px; height: 28px;
  border-radius: 7px;
  background: rgba(255,255,255,.08);
  border: none;
  color: var(--sb-text);
  cursor: pointer;
  display: flex; align-items: center; justify-content: center;
  font-size: 13px;
  transition: var(--sb-transition);
  flex-shrink: 0;
}
.sb-toggle:hover { background: rgba(255,255,255,.16); color: #fff; }
#layoutSidenav.sb-closed .sb-toggle { right: 50%; transform: translateX(50%); top: 16px; }

/* --- Scroll area --- */
.sb-scroll {
  flex: 1;
  overflow-y: auto;
  overflow-x: hidden;
  padding: 10px 0 20px;
  scrollbar-width: thin;
  scrollbar-color: rgba(255,255,255,.08) transparent;
}
.sb-scroll::-webkit-scrollbar { width: 4px; }
.sb-scroll::-webkit-scrollbar-thumb { background: rgba(255,255,255,.1); border-radius: 4px; }

/* --- Section headings --- */
.sb-heading {
  font-size: 9.5px;
  font-weight: 700;
  letter-spacing: 1.2px;
  text-transform: uppercase;
  color: var(--sb-heading);
  padding: 18px 18px 6px;
  white-space: nowrap;
  overflow: hidden;
  transition: var(--sb-transition);
}
#layoutSidenav.sb-closed .sb-heading {
  opacity: 0;
  padding: 18px 0 6px;
  height: 0;
  padding: 0;
}

/* --- Nav items --- */
.sb-item {
  position: relative;
  display: flex;
  align-items: center;
  gap: 11px;
  padding: 9px 14px;
  margin: 1px 8px;
  border-radius: var(--sb-radius);
  color: var(--sb-text);
  text-decoration: none !important;
  font-size: 13px;
  font-weight: 500;
  cursor: pointer;
  white-space: nowrap;
  overflow: hidden;
  transition: var(--sb-transition);
  border: none;
  background: none;
  width: calc(100% - 16px);
  text-align: left;
  line-height: 1.4;
}
.sb-item:hover {
  background: rgba(255,255,255,.07);
  color: #fff;
  text-decoration: none !important;
}
.sb-item.active,
.sb-item.sb-parent-active {
  background: var(--sb-active-bg);
  color: var(--sb-accent);
  border-left: 3px solid var(--sb-active-border);
  padding-left: 11px;
}
.sb-item.active .sb-icon,
.sb-item.sb-parent-active .sb-icon { color: var(--sb-accent); }

/* --- Icon --- */
.sb-icon {
  width: 20px;
  height: 20px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 14px;
  flex-shrink: 0;
  color: var(--sb-text-muted);
  transition: color .15s;
}
.sb-item:hover .sb-icon { color: #fff; }

/* --- Label text --- */
.sb-label {
  flex: 1;
  transition: opacity .18s, width .22s;
  opacity: 1;
}
#layoutSidenav.sb-closed .sb-label { opacity: 0; width: 0; overflow: hidden; }

/* --- Chevron for dropdowns --- */
.sb-chevron {
  font-size: 10px;
  color: var(--sb-text-muted);
  transition: transform .22s, opacity .18s;
  flex-shrink: 0;
}
.sb-item[aria-expanded="true"] .sb-chevron { transform: rotate(90deg); }
#layoutSidenav.sb-closed .sb-chevron { opacity: 0; width: 0; overflow: hidden; }

/* --- Sub-menu --- */
.sb-submenu {
  overflow: hidden;
  max-height: 0;
  transition: max-height .25s ease;
}
.sb-submenu.open { max-height: 400px; }

/* In icon-only mode, sub-menu appears as a flyout tooltip panel */
#layoutSidenav.sb-closed .sb-submenu {
  position: fixed;
  left: var(--sb-width-closed);
  background: #0f3545;
  border-radius: 0 10px 10px 0;
  box-shadow: 4px 4px 20px rgba(0,0,0,.3);
  min-width: 180px;
  max-height: 0;
  z-index: 1040;
  padding: 0;
  transition: max-height .0s, opacity .15s;
  opacity: 0;
  pointer-events: none;
}
/* flyout is shown when parent .sb-group is hovered in closed mode */
#layoutSidenav.sb-closed .sb-group:hover .sb-submenu {
  max-height: 400px;
  opacity: 1;
  pointer-events: auto;
  padding: 8px 0;
}

.sb-subitem {
  display: flex;
  align-items: center;
  gap: 9px;
  padding: 7px 16px 7px 20px;
  color: var(--sb-text);
  font-size: 12.5px;
  font-weight: 400;
  text-decoration: none !important;
  border-radius: 6px;
  margin: 1px 6px;
  transition: var(--sb-transition);
  white-space: nowrap;
}
.sb-subitem:hover { background: rgba(255,255,255,.07); color: #fff; text-decoration: none !important; }
.sb-subitem.active { color: var(--sb-accent); font-weight: 600; }
.sb-subitem::before {
  content: '';
  width: 5px; height: 5px;
  border-radius: 50%;
  background: var(--sb-text-muted);
  flex-shrink: 0;
}
.sb-subitem.active::before { background: var(--sb-accent); }

/* --- Tooltip (icon-only mode, non-group items) --- */
.sb-tooltip {
  position: fixed;
  left: calc(var(--sb-width-closed) + 8px);
  background: #0f3545;
  color: #fff;
  font-size: 12px;
  font-weight: 600;
  padding: 6px 12px;
  border-radius: 7px;
  box-shadow: 2px 2px 12px rgba(0,0,0,.3);
  white-space: nowrap;
  pointer-events: none;
  opacity: 0;
  z-index: 1041;
  transition: opacity .15s;
  /* positioned by JS */
}
.sb-tooltip.visible { opacity: 1; }

/* --- Footer / user info --- */
.sb-footer {
  border-top: 1px solid rgba(255,255,255,.06);
  padding: 12px 14px;
  display: flex;
  align-items: center;
  gap: 10px;
  overflow: hidden;
  white-space: nowrap;
  flex-shrink: 0;
}
.sb-footer-avatar {
  width: 32px; height: 32px;
  border-radius: 50%;
  background: linear-gradient(135deg, var(--sb-accent2), var(--sb-accent));
  display: flex; align-items: center; justify-content: center;
  font-size: 12px; font-weight: 700; color: #fff;
  flex-shrink: 0;
}
.sb-footer-info { transition: opacity .18s; }
.sb-footer-info .sb-footer-name { font-size: 12.5px; font-weight: 700; color: #fff; }
.sb-footer-info .sb-footer-role { font-size: 10.5px; color: var(--sb-text-muted); }
#layoutSidenav.sb-closed .sb-footer-info { opacity: 0; width: 0; overflow: hidden; }

/* --- Content area left offset to match sidebar width --- */
#layoutSidenav_content {
  margin-left: var(--sb-width-open) !important;
  transition: margin-left .22s cubic-bezier(.4,0,.2,1);
}
#layoutSidenav.sb-closed #layoutSidenav_content {
  margin-left: var(--sb-width-closed) !important;
}

/* Hide the old sidenav that was here before */
.sidenav { display: none !important; }
</style>

<!-- ============================================================
     SIDEBAR MARKUP
     ============================================================ -->
<div class="app-sidenav" id="appSidenav">

  <!-- Brand -->
  <div class="sb-brand" style="position:relative;">
    <div class="sb-logo"><i class="fas fa-glasses"></i></div>
    <span class="sb-title">Vision Optical</span>
    <button class="sb-toggle" id="sbToggleBtn" title="Toggle sidebar">
      <i class="fas fa-bars"></i>
    </button>
  </div>

  <!-- Scrollable menu -->
  <div class="sb-scroll">

    <!-- Dashboard -->
    <div class="sb-heading">Main</div>
    <a class="sb-item <?php echo ($controllermenu=='Welcome') ? 'active' : ''; ?>"
       href="<?php echo base_url().'Welcome/Dashboard'; ?>"
       data-tooltip="Dashboard">
      <span class="sb-icon"><i class="fas fa-chart-pie"></i></span>
      <span class="sb-label">Dashboard</span>
    </a>

    <!-- Company Info -->
    <?php if(menucheck($menuprivilegearray, 13)==1 || menucheck($menuprivilegearray, 14)==1): ?>
    <div class="sb-heading">Organisation</div>
    <div class="sb-group">
      <button class="sb-item <?php echo ($controllermenu=='Company'||$controllermenu=='Companybranch') ? 'sb-parent-active' : ''; ?>"
              data-target="sub-company" data-tooltip="Company Info"
              aria-expanded="<?php echo ($controllermenu=='Company'||$controllermenu=='Companybranch') ? 'true' : 'false'; ?>">
        <span class="sb-icon"><i class="fas fa-building"></i></span>
        <span class="sb-label">Company Info</span>
        <span class="sb-chevron"><i class="fas fa-chevron-right"></i></span>
      </button>
      <div class="sb-submenu <?php echo ($controllermenu=='Company'||$controllermenu=='Companybranch') ? 'open' : ''; ?>" id="sub-company">
        <?php if(menucheck($menuprivilegearray, 13)==1): ?>
        <a class="sb-subitem <?php echo ($controllermenu=='Company') ? 'active' : ''; ?>" href="<?php echo base_url().'Company'; ?>">Company</a>
        <?php endif; ?>
        <?php if(menucheck($menuprivilegearray, 14)==1): ?>
        <a class="sb-subitem <?php echo ($controllermenu=='Companybranch') ? 'active' : ''; ?>" href="<?php echo base_url().'Companybranch'; ?>">Company Branch</a>
        <?php endif; ?>
      </div>
    </div>
    <?php endif; ?>

    <!-- Customer -->
    <?php if(menucheck($menuprivilegearray, 5)==1): ?>
    <a class="sb-item <?php echo ($controllermenu=='Customer') ? 'active' : ''; ?>"
       href="<?php echo base_url().'Customer'; ?>"
       data-tooltip="Customer">
      <span class="sb-icon"><i class="fas fa-user-friends"></i></span>
      <span class="sb-label">Customer</span>
    </a>
    <?php endif; ?>

    <!-- Supplier -->
    <?php if(menucheck($menuprivilegearray, 6)==1): ?>
    <a class="sb-item <?php echo ($controllermenu=='Supplier') ? 'active' : ''; ?>"
       href="<?php echo base_url().'Supplier'; ?>"
       data-tooltip="Supplier">
      <span class="sb-icon"><i class="fas fa-truck"></i></span>
      <span class="sb-label">Supplier</span>
    </a>
    <?php endif; ?>

    <!-- Product Info -->
    <?php if(menucheck($menuprivilegearray, 9)==1 || menucheck($menuprivilegearray, 10)==1): ?>
    <div class="sb-heading">Inventory</div>
    <div class="sb-group">
      <button class="sb-item <?php echo ($controllermenu=='Materialcategory'||$controllermenu=='Materialdetail') ? 'sb-parent-active' : ''; ?>"
              data-target="sub-product" data-tooltip="Product Info"
              aria-expanded="<?php echo ($controllermenu=='Materialcategory'||$controllermenu=='Materialdetail') ? 'true' : 'false'; ?>">
        <span class="sb-icon"><i class="fas fa-boxes"></i></span>
        <span class="sb-label">Product Info</span>
        <span class="sb-chevron"><i class="fas fa-chevron-right"></i></span>
      </button>
      <div class="sb-submenu <?php echo ($controllermenu=='Materialcategory'||$controllermenu=='Materialdetail') ? 'open' : ''; ?>" id="sub-product">
        <?php if(menucheck($menuprivilegearray, 9)==1): ?>
        <a class="sb-subitem <?php echo ($controllermenu=='Materialcategory') ? 'active' : ''; ?>" href="<?php echo base_url().'Materialcategory'; ?>">Product Category</a>
        <?php endif; ?>
        <?php if(menucheck($menuprivilegearray, 10)==1): ?>
        <a class="sb-subitem <?php echo ($controllermenu=='Materialdetail') ? 'active' : ''; ?>" href="<?php echo base_url().'Materialdetail'; ?>">Product Detail</a>
        <?php endif; ?>
      </div>
    </div>
    <?php endif; ?>

    <!-- PO & GRN -->
    <?php if(menucheck($menuprivilegearray, 7)==1 || menucheck($menuprivilegearray, 8)==1): ?>
    <div class="sb-group">
      <button class="sb-item <?php echo ($controllermenu=='Purchaseorder'||$controllermenu=='Goodreceive') ? 'sb-parent-active' : ''; ?>"
              data-target="sub-pogratn" data-tooltip="PO & GRN"
              aria-expanded="<?php echo ($controllermenu=='Purchaseorder'||$controllermenu=='Goodreceive') ? 'true' : 'false'; ?>">
        <span class="sb-icon"><i class="fas fa-file-import"></i></span>
        <span class="sb-label">PO &amp; GRN</span>
        <span class="sb-chevron"><i class="fas fa-chevron-right"></i></span>
      </button>
      <div class="sb-submenu <?php echo ($controllermenu=='Purchaseorder'||$controllermenu=='Goodreceive') ? 'open' : ''; ?>" id="sub-pogratn">
        <?php if(menucheck($menuprivilegearray, 7)==1): ?>
        <a class="sb-subitem <?php echo ($controllermenu=='Purchaseorder') ? 'active' : ''; ?>" href="<?php echo base_url().'Purchaseorder'; ?>">Purchase Order</a>
        <?php endif; ?>
        <?php if(menucheck($menuprivilegearray, 8)==1): ?>
        <a class="sb-subitem <?php echo ($controllermenu=='Goodreceive') ? 'active' : ''; ?>" href="<?php echo base_url().'Goodreceive'; ?>">Good Receive Note</a>
        <?php endif; ?>
      </div>
    </div>
    <?php endif; ?>

    <!-- Stock Info -->
    <?php if(menucheck($menuprivilegearray, 11)==1 || menucheck($menuprivilegearray, 12)==1): ?>
    <div class="sb-group">
      <button class="sb-item <?php echo ($controllermenu=='Rptmatstock'||$controllermenu=='Rptmatstockbatchwise') ? 'sb-parent-active' : ''; ?>"
              data-target="sub-stock" data-tooltip="Stock Info"
              aria-expanded="<?php echo ($controllermenu=='Rptmatstock'||$controllermenu=='Rptmatstockbatchwise') ? 'true' : 'false'; ?>">
        <span class="sb-icon"><i class="fas fa-warehouse"></i></span>
        <span class="sb-label">Stock Info</span>
        <span class="sb-chevron"><i class="fas fa-chevron-right"></i></span>
      </button>
      <div class="sb-submenu <?php echo ($controllermenu=='Rptmatstock'||$controllermenu=='Rptmatstockbatchwise') ? 'open' : ''; ?>" id="sub-stock">
        <?php if(menucheck($menuprivilegearray, 11)==1): ?>
        <a class="sb-subitem <?php echo ($controllermenu=='Rptmatstock') ? 'active' : ''; ?>" href="<?php echo base_url().'Rptmatstock'; ?>">Item Stock</a>
        <?php endif; ?>
        <?php if(menucheck($menuprivilegearray, 12)==1): ?>
        <a class="sb-subitem <?php echo ($controllermenu=='Rptmatstockbatchwise') ? 'active' : ''; ?>" href="<?php echo base_url().'Rptmatstockbatchwise'; ?>">Stock Batchwise</a>
        <?php endif; ?>
      </div>
    </div>
    <?php endif; ?>

    <!-- Invoice Info -->
    <?php if(menucheck($menuprivilegearray, 4)==1 || menucheck($menuprivilegearray, 15) || menucheck($menuprivilegearray, 16)==1): ?>
    <div class="sb-heading">Sales</div>
    <div class="sb-group">
      <button class="sb-item <?php echo ($controllermenu=='Directsale'||$controllermenu=='Invoiceview'||$controllermenu=='Cashier') ? 'sb-parent-active' : ''; ?>"
              data-target="sub-invoice" data-tooltip="Invoice Info"
              aria-expanded="<?php echo ($controllermenu=='Directsale'||$controllermenu=='Invoiceview'||$controllermenu=='Cashier') ? 'true' : 'false'; ?>">
        <span class="sb-icon"><i class="fas fa-cash-register"></i></span>
        <span class="sb-label">Invoice Info</span>
        <span class="sb-chevron"><i class="fas fa-chevron-right"></i></span>
      </button>
      <div class="sb-submenu <?php echo ($controllermenu=='Directsale'||$controllermenu=='Invoiceview'||$controllermenu=='Cashier') ? 'open' : ''; ?>" id="sub-invoice">
        <?php if(menucheck($menuprivilegearray, 4)==1): ?>
        <a class="sb-subitem <?php echo ($controllermenu=='Directsale') ? 'active' : ''; ?>" href="<?php echo base_url().'Directsale'; ?>">Invoice / POS</a>
        <?php endif; ?>
        <?php if(menucheck($menuprivilegearray, 16)==1): ?>
        <a class="sb-subitem <?php echo ($controllermenu=='Cashier') ? 'active' : ''; ?>" href="<?php echo base_url().'Cashier'; ?>">Cashier</a>
        <?php endif; ?>
        <?php if(menucheck($menuprivilegearray, 15)==1): ?>
        <a class="sb-subitem <?php echo ($controllermenu=='Invoiceview') ? 'active' : ''; ?>" href="<?php echo base_url().'Invoiceview'; ?>">View Invoice</a>
        <?php endif; ?>
      </div>
    </div>
    <?php endif; ?>

    <!-- User Info -->
    <?php if(menucheck($menuprivilegearray, 1)==1 || menucheck($menuprivilegearray, 2)==1 || menucheck($menuprivilegearray, 3)==1): ?>
    <div class="sb-heading">System</div>
    <div class="sb-group">
      <button class="sb-item <?php echo ($functionmenu2=='Useraccount'||$functionmenu2=='Usertype'||$functionmenu2=='Userprivilege') ? 'sb-parent-active' : ''; ?>"
              data-target="sub-user" data-tooltip="User Info"
              aria-expanded="<?php echo ($functionmenu2=='Useraccount'||$functionmenu2=='Usertype'||$functionmenu2=='Userprivilege') ? 'true' : 'false'; ?>">
        <span class="sb-icon"><i class="fas fa-user-shield"></i></span>
        <span class="sb-label">User Info</span>
        <span class="sb-chevron"><i class="fas fa-chevron-right"></i></span>
      </button>
      <div class="sb-submenu <?php echo ($functionmenu2=='Useraccount'||$functionmenu2=='Usertype'||$functionmenu2=='Userprivilege') ? 'open' : ''; ?>" id="sub-user">
        <?php if(menucheck($menuprivilegearray, 1)==1): ?>
        <a class="sb-subitem <?php echo ($functionmenu2=='Useraccount') ? 'active' : ''; ?>" href="<?php echo base_url().'User/Useraccount'; ?>">User Account</a>
        <?php endif; ?>
        <?php if(menucheck($menuprivilegearray, 2)==1): ?>
        <a class="sb-subitem <?php echo ($functionmenu2=='Usertype') ? 'active' : ''; ?>" href="<?php echo base_url().'User/Usertype'; ?>">User Type</a>
        <?php endif; ?>
        <?php if(menucheck($menuprivilegearray, 3)==1): ?>
        <a class="sb-subitem <?php echo ($functionmenu2=='Userprivilege') ? 'active' : ''; ?>" href="<?php echo base_url().'User/Userprivilege'; ?>">Privilege</a>
        <?php endif; ?>
      </div>
    </div>
    <?php endif; ?>

  </div><!-- end .sb-scroll -->

  <!-- Footer / logged-in user -->
  <div class="sb-footer">
    <div class="sb-footer-avatar">
      <?php echo strtoupper(substr($_SESSION['name'], 0, 1)); ?>
    </div>
    <div class="sb-footer-info">
      <div class="sb-footer-name"><?php echo ucfirst($_SESSION['name']); ?></div>
      <div class="sb-footer-role"><?php echo isset($_SESSION['typename']) ? $_SESSION['typename'] : 'Staff'; ?></div>
    </div>
  </div>

</div><!-- end .app-sidenav -->

<!-- Floating tooltip element (moved by JS) -->
<div class="sb-tooltip" id="sbTooltip"></div>

<script>
(function(){
  var layout   = document.getElementById('layoutSidenav');
  var toggleBtn = document.getElementById('sbToggleBtn');
  var tooltip  = document.getElementById('sbTooltip');
  var CLOSED_KEY = 'sb_closed';

  /* ---- Restore last state ---- */
  if(localStorage.getItem(CLOSED_KEY) === '1') layout.classList.add('sb-closed');

  /* ---- Toggle ---- */
  toggleBtn.addEventListener('click', function(){
    var closed = layout.classList.toggle('sb-closed');
    localStorage.setItem(CLOSED_KEY, closed ? '1' : '0');
    // Close all open submenus when collapsing
    if(closed){
      document.querySelectorAll('.sb-submenu.open').forEach(function(el){ el.classList.remove('open'); });
      document.querySelectorAll('.sb-item[aria-expanded="true"]').forEach(function(el){ el.setAttribute('aria-expanded','false'); });
    }
  });

  /* ---- Dropdown submenus (expanded mode only) ---- */
  document.querySelectorAll('.sb-item[data-target]').forEach(function(btn){
    btn.addEventListener('click', function(){
      if(layout.classList.contains('sb-closed')) return; // flyout handles it in closed mode
      var targetID = btn.getAttribute('data-target');
      var sub = document.getElementById(targetID);
      if(!sub) return;

      // Close others
      document.querySelectorAll('.sb-submenu.open').forEach(function(el){
        if(el !== sub){ el.classList.remove('open'); }
      });
      document.querySelectorAll('.sb-item[aria-expanded="true"]').forEach(function(el){
        if(el !== btn){ el.setAttribute('aria-expanded','false'); }
      });

      var open = sub.classList.toggle('open');
      btn.setAttribute('aria-expanded', open ? 'true' : 'false');
    });
  });

  /* ---- Tooltips for simple (non-group) items in closed mode ---- */
  document.querySelectorAll('.sb-item[data-tooltip]:not([data-target])').forEach(function(item){
    item.addEventListener('mouseenter', function(){
      if(!layout.classList.contains('sb-closed')) return;
      var rect = item.getBoundingClientRect();
      tooltip.textContent = item.getAttribute('data-tooltip');
      tooltip.style.top = (rect.top + rect.height/2 - 14) + 'px';
      tooltip.classList.add('visible');
    });
    item.addEventListener('mouseleave', function(){
      tooltip.classList.remove('visible');
    });
  });

  /* ---- In closed mode, position the flyout sub-panel vertically ---- */
  document.querySelectorAll('.sb-group').forEach(function(group){
    var btn = group.querySelector('.sb-item[data-target]');
    var sub = group.querySelector('.sb-submenu');
    if(!btn || !sub) return;

    group.addEventListener('mouseenter', function(){
      if(!layout.classList.contains('sb-closed')) return;
      var rect = btn.getBoundingClientRect();
      sub.style.top = rect.top + 'px';
    });
  });

})();
</script>

<?php
/* ---- Keep the existing topnavbar toggle wired if it references #sidebarToggle ---- */
?>
<textarea class="d-none" id="actiontext"><?php if($this->session->flashdata('msg')) {echo $this->session->flashdata('msg');} ?></textarea>