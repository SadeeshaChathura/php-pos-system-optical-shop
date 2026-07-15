<?php

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * GRN Report - DataTables server-side processing via SSP customized class
 * Filters (POST): date_from, date_to, supplier_id, material_id
 */

// DB table to use (base table for this row-level listing)
$table = 'tbl_grndetail';

// Table's primary key
$primaryKey = 'idtbl_grndetail';

// Columns returned to DataTables. 'dt' is the JS-facing key, 'db' is the
// aliased column reference used in $joinQuery below.
$columns = array(
	array( 'db' => '`gd`.`idtbl_grndetail`', 'dt' => 'idtbl_grndetail', 'field' => 'idtbl_grndetail' ),
	array( 'db' => '`g`.`idtbl_grn`',        'dt' => 'idtbl_grn',       'field' => 'idtbl_grn' ),
	array( 'db' => '`g`.`grndate`',          'dt' => 'grndate',         'field' => 'grndate' ),
	array( 'db' => '`g`.`invoicenum`',       'dt' => 'invoicenum',      'field' => 'invoicenum' ),
	array( 'db' => '`g`.`batchno`',          'dt' => 'batchno',         'field' => 'batchno' ),
	array( 'db' => '`s`.`suppliername`',     'dt' => 'suppliername',    'field' => 'suppliername' ),
	array( 'db' => '`m`.`materialname`',     'dt' => 'materialname',    'field' => 'materialname' ),
	array( 'db' => '`gd`.`qty`',             'dt' => 'qty',             'field' => 'qty' ),
	array( 'db' => '`gd`.`unitprice`',       'dt' => 'unitprice',       'field' => 'unitprice' ),
	array( 'db' => '`gd`.`saleprice`',       'dt' => 'saleprice',       'field' => 'saleprice' ),
	array( 'db' => '`gd`.`total`',           'dt' => 'total',       'field' => 'total' ),
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

$joinQuery = "FROM `tbl_grndetail` AS `gd`
              INNER JOIN `tbl_grn` AS `g` ON `gd`.`tbl_grn_idtbl_grn` = `g`.`idtbl_grn`
              INNER JOIN `tbl_supplier` AS `s` ON `g`.`tbl_supplier_idtbl_supplier` = `s`.`idtbl_supplier`
              INNER JOIN `tbl_material_info` AS `m` ON `gd`.`tbl_material_info_idtbl_material_info` = `m`.`idtbl_material_info`";

// ---- Build extraWhere from filter fields (values are validated/cast before splicing) ----
$whereParts = array("`g`.`status` = 1");

if (!empty($_POST['date_from']) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $_POST['date_from'])) {
	$whereParts[] = "`g`.`grndate` >= '" . $_POST['date_from'] . "'";
}
if (!empty($_POST['date_to']) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $_POST['date_to'])) {
	$whereParts[] = "`g`.`grndate` <= '" . $_POST['date_to'] . "'";
}
if (!empty($_POST['supplier_id'])) {
	$whereParts[] = "`g`.`tbl_supplier_idtbl_supplier` = " . (int)$_POST['supplier_id'];
}
if (!empty($_POST['material_id'])) {
	$whereParts[] = "`gd`.`tbl_material_info_idtbl_material_info` = " . (int)$_POST['material_id'];
}

$extraWhere = implode(' AND ', $whereParts);

$result = SSP::simple( $_POST, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere );

// ---- Footer totals across ALL filtered rows, not just the current page ----
$sum_qty = 0;
$sum_total = 0;
$mysqli = new mysqli($db_host, $db_username, $db_password, $db_name);
if (!$mysqli->connect_error) {
	$sumSql = "SELECT COALESCE(SUM(`gd`.`qty`),0) AS sqty, COALESCE(SUM(`gd`.`total`),0) AS stotal
               {$joinQuery} WHERE {$extraWhere}";
	if ($res = $mysqli->query($sumSql)) {
		$row = $res->fetch_assoc();
		$sum_qty = (float)$row['sqty'];
		$sum_total = (float)$row['stotal'];
	}
	$mysqli->close();
}

$result['sum_qty'] = $sum_qty;
$result['sum_total'] = $sum_total;

echo json_encode($result);