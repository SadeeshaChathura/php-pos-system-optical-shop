<?php

/*
 * Server-side processing script for the "Previous Bills" panel on the POS screen.
 * Mirrors the existing scripts/<name>.php + SSP::simple() pattern used by the GRN module
 * and scripts/alreadycustomerlist.php.
 */

// DB table to use
$table = 'tbl_invoice';

// Table's primary key
$primaryKey = 'idtbl_invoice';

// Columns returned to DataTables.
$columns = array(
    array( 'db' => '`u`.`idtbl_invoice`',  'dt' => 'idtbl_invoice',  'field' => 'idtbl_invoice' ),
    array( 'db' => '`u`.`invdate`',        'dt' => 'invdate',        'field' => 'invdate' ),
    array( 'db' => '`ua`.`name`',          'dt' => 'name',   'field' => 'name' ),
    array( 'db' => '`ua`.`contact`',       'dt' => 'customercontact','field' => 'customercontact' ),
    array( 'db' => '`u`.`invtype`',        'dt' => 'invtype',        'field' => 'invtype' ),
    array( 'db' => '`u`.`grosstotal`',     'dt' => 'grosstotal',     'field' => 'grosstotal' ),
    array( 'db' => '`u`.`discount`',       'dt' => 'discount',       'field' => 'discount' ),
    array( 'db' => '`u`.`nettotal`',       'dt' => 'nettotal',       'field' => 'nettotal' ),
    array( 'db' => '`u`.`paycomplete`',    'dt' => 'paycomplete',    'field' => 'paycomplete' ),
    array( 'db' => '`u`.`returnstatus`',   'dt' => 'returnstatus',   'field' => 'returnstatus' ),
    array( 'db' => '`u`.`status`',         'dt' => 'status',         'field' => 'status' )
);

// SQL server connection information
require('config.php');
$sql_details = array(
    'user' => $db_username,
    'pass' => $db_password,
    'db'   => $db_name,
    'host' => $db_host
);

require('ssp.customized.class.php');

$joinQuery = "FROM `tbl_invoice` AS `u`
              LEFT JOIN `tbl_customer` AS `ua` ON (`ua`.`idtbl_customer` = `u`.`tbl_customer_idtbl_customer`)";

// Only show active (non-deleted) invoices. Adjust if returns should be excluded/included differently.
$extraWhere = "`u`.`status` = 1";

// Optional date-range / invoice-type filters sent from the Previous Bills modal,
// layered on top of the base extraWhere using the same $extraWhere intercept
// pattern already used in the GRN/material-search customization.
// NOTE: using addslashes() here for simple escaping since this script runs standalone
// (no CI db object available, same as the GRN ssp script). If ssp.customized.class.php
// exposes its underlying connection some other way in your setup, swap this for a
// proper prepared/escaped value.
if (!empty($_POST['filterDateFrom'])) {
    $dateFrom = addslashes($_POST['filterDateFrom']);
    $extraWhere .= " AND DATE(`u`.`invdate`) >= '" . $dateFrom . "'";
}
if (!empty($_POST['filterDateTo'])) {
    $dateTo = addslashes($_POST['filterDateTo']);
    $extraWhere .= " AND DATE(`u`.`invdate`) <= '" . $dateTo . "'";
}
if (!empty($_POST['filterInvType'])) {
    $invType = (int) $_POST['filterInvType'];
    $extraWhere .= " AND `u`.`invtype` = " . $invType;
}

echo json_encode(
    SSP::simple($_POST, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere)
);