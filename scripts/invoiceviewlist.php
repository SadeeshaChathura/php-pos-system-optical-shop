<?php

$table = 'tbl_invoice';
$primaryKey = 'idtbl_invoice';

$columns = array(
    array('db' => '`u`.`idtbl_invoice`', 'dt' => 'idtbl_invoice', 'field' => 'idtbl_invoice'),
    array('db' => '`u`.`tbl_customer_idtbl_customer`', 'dt' => 'tbl_customer_idtbl_customer', 'field' => 'tbl_customer_idtbl_customer'),
    array('db' => '`u`.`invdate`', 'dt' => 'invdate', 'field' => 'invdate'),
    array('db' => '`u`.`invtype`', 'dt' => 'invtype', 'field' => 'invtype'),

    array('db' => '`ud`.`name`', 'dt' => 'name', 'field' => 'name'),

    array('db' => '`ub`.`materialname`', 'dt' => 'materialname', 'field' => 'materialname'),
    array('db' => '`ub`.`materialinfocode`', 'dt' => 'materialinfocode', 'field' => 'materialinfocode'),

    array('db' => '`ua`.`qty`', 'dt' => 'qty', 'field' => 'qty'),

    array('db' => '`u`.`grosstotal`', 'dt' => 'grosstotal', 'field' => 'grosstotal'),
    array('db' => '`u`.`discount`', 'dt' => 'discount', 'field' => 'discount'),
    array('db' => '`u`.`nettotal`', 'dt' => 'nettotal', 'field' => 'nettotal'),
    array('db' => '`u`.`status`', 'dt' => 'status', 'field' => 'status'),

    /* =========================
       PAYMENT METHOD (NEW)
    ========================== */
    array('db' => '`pd`.`method`', 'dt' => 'method', 'field' => 'method'),
    array('db' => '`pd`.`bank`', 'dt' => 'bank', 'field' => 'bank'),
    array('db' => '`pd`.`chequeno`', 'dt' => 'chequeno', 'field' => 'chequeno'),
);

require('config.php');

$sql_details = array(
    'user' => $db_username,
    'pass' => $db_password,
    'db'   => $db_name,
    'host' => $db_host
);

require('ssp.customized.class.php');

/* =========================
   JOIN FIXED (PAYMENT ADDED)
========================== */
$joinQuery = "
FROM `tbl_invoice` AS `u`

LEFT JOIN `tbl_invoice_detail` AS `ua`
    ON (`ua`.`tbl_invoice_idtbl_invoice` = `u`.`idtbl_invoice`)

LEFT JOIN `tbl_material_info` AS `ub`
    ON (`ub`.`idtbl_material_info` = `ua`.`tbl_material_info_idtbl_material_info`)

LEFT JOIN `tbl_customer` AS `ud`
    ON (`ud`.`idtbl_customer` = `u`.`tbl_customer_idtbl_customer`)

/* =========================
   PAYMENT RELATION (IMPORTANT FIX)
========================== */
LEFT JOIN `tbl_invoice_payment_has_tbl_invoice` AS `ph`
    ON (`ph`.`tbl_invoice_idtbl_invoice` = `u`.`idtbl_invoice`)

LEFT JOIN `tbl_invoice_payment` AS `p`
    ON (`p`.`idtbl_invoice_payment` = `ph`.`tbl_invoice_payment_idtbl_invoice_payment`)

LEFT JOIN `tbl_invoice_payment_detail` AS `pd`
    ON (`pd`.`tbl_invoice_payment_idtbl_invoice_payment` = `p`.`idtbl_invoice_payment`)
";

$extraWhere = "`u`.`status` IN (1,2)";

/* IMPORTANT: prevents duplicate invoice rows from detail join */
$groupBy = "`u`.`idtbl_invoice`";

echo json_encode(
    SSP::simple($_POST, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere, $groupBy)
);