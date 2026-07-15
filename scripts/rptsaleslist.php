<?php

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Sales Report - DataTables server-side source
 * Filters (POST): date_from, date_to, customer_id, product_id, groupby
 *   groupby = detail | day | month | year | product | customer
 *
 * 'detail' uses the SSP customized class (same pattern as your tbl_customer
 * script). The grouped modes need GROUP BY aggregation, which the SSP class
 * isn't built for, so those are handled with plain SQL below instead.
 */

require('config.php');

$groupBy = isset($_POST['groupby']) ? $_POST['groupby'] : 'detail';
$allowedGroups = array('detail', 'day', 'month', 'year', 'product', 'customer');
if (!in_array($groupBy, $allowedGroups, true)) {
	$groupBy = 'detail';
}

// ---- Shared filter validation (used by both detail and grouped modes) ----
$dateFrom   = (!empty($_POST['date_from']) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $_POST['date_from'])) ? $_POST['date_from'] : '';
$dateTo     = (!empty($_POST['date_to']) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $_POST['date_to'])) ? $_POST['date_to'] : '';
$customerId = !empty($_POST['customer_id']) ? (int)$_POST['customer_id'] : 0;
$productId  = !empty($_POST['product_id']) ? (int)$_POST['product_id'] : 0;

$joinQuery = "FROM `tbl_invoice_detail` AS `id`
              INNER JOIN `tbl_invoice` AS `i` ON `id`.`tbl_invoice_idtbl_invoice` = `i`.`idtbl_invoice`
              INNER JOIN `tbl_customer` AS `c` ON `i`.`tbl_customer_idtbl_customer` = `c`.`idtbl_customer`
              INNER JOIN `tbl_material_info` AS `m` ON `id`.`tbl_material_info_idtbl_material_info` = `m`.`idtbl_material_info`";

$whereParts = array("`i`.`status` = 1", "`id`.`returnstatus` = 0");
if ($dateFrom !== '')   { $whereParts[] = "`i`.`invdate` >= '{$dateFrom}'"; }
if ($dateTo !== '')     { $whereParts[] = "`i`.`invdate` <= '{$dateTo}'"; }
if ($customerId > 0)    { $whereParts[] = "`i`.`tbl_customer_idtbl_customer` = {$customerId}"; }
if ($productId > 0)     { $whereParts[] = "`id`.`tbl_material_info_idtbl_material_info` = {$productId}"; }
$extraWhere = implode(' AND ', $whereParts);

// =========================================================
// DETAIL MODE - via SSP customized class
// =========================================================
if ($groupBy === 'detail') {

	$table = 'tbl_invoice_detail';
	$primaryKey = 'idtbl_invoice_detail';

	$columns = array(
		array( 'db' => '`id`.`idtbl_invoice_detail`', 'dt' => 'idtbl_invoice_detail', 'field' => 'idtbl_invoice_detail' ),
		array( 'db' => '`i`.`invdate`',                'dt' => 'invdate',              'field' => 'invdate' ),
		array( 'db' => '`i`.`idtbl_invoice`',          'dt' => 'idtbl_invoice',            'field' => 'idtbl_invoice' ),
		array( 'db' => '`c`.`name`',                   'dt' => 'name',         'field' => 'name' ),
		array( 'db' => '`m`.`materialname`',           'dt' => 'materialname',         'field' => 'materialname' ),
		array( 'db' => '`id`.`qty`',                   'dt' => 'qty',                  'field' => 'qty' ),
		array( 'db' => '`id`.`saleprice`',             'dt' => 'saleprice',            'field' => 'saleprice' ),
		array( 'db' => '`id`.`discount`',              'dt' => 'discount',             'field' => 'discount' ),
		array( 'db' => '`id`.`total`',                 'dt' => 'total',            'field' => 'total' ),
	);

	$sql_details = array(
		'user' => $db_username,
		'pass' => $db_password,
		'db'   => $db_name,
		'host' => $db_host
	);

	require('ssp.customized.class.php');

	$result = SSP::simple( $_POST, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere );

	$sum_qty = 0; $sum_total = 0;
	$mysqli = new mysqli($db_host, $db_username, $db_password, $db_name);
	if (!$mysqli->connect_error) {
		$sumSql = "SELECT COALESCE(SUM(`id`.`qty`),0) AS sqty, COALESCE(SUM(`id`.`total`),0) AS stotal
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
	exit;
}

// =========================================================
// GROUPED MODES - day / month / year / product / customer
// Plain SQL (SSP's simple() doesn't support GROUP BY aggregation)
// =========================================================
$draw   = isset($_POST['draw']) ? (int)$_POST['draw'] : 1;
$start  = isset($_POST['start']) ? (int)$_POST['start'] : 0;
$length = isset($_POST['length']) ? (int)$_POST['length'] : 10;

switch ($groupBy) {
	case 'day':
		$periodExpr = "DATE(`i`.`invdate`)";
		$groupExpr  = "DATE(`i`.`invdate`)";
		break;
	case 'month':
		$periodExpr = "DATE_FORMAT(`i`.`invdate`, '%Y-%m')";
		$groupExpr  = "DATE_FORMAT(`i`.`invdate`, '%Y-%m')";
		break;
	case 'year':
		$periodExpr = "YEAR(`i`.`invdate`)";
		$groupExpr  = "YEAR(`i`.`invdate`)";
		break;
	case 'product':
		$periodExpr = "`m`.`materialname`";
		$groupExpr  = "`id`.`tbl_material_info_idtbl_material_info`, `m`.`materialname`";
		break;
	case 'customer':
		$periodExpr = "`c`.`name`";
		$groupExpr  = "`i`.`tbl_customer_idtbl_customer`, `c`.`name`";
		break;
}

$selectExtra = ($groupBy === 'product')
	? "`m`.`materialname`,"
	: (($groupBy === 'customer') ? "`c`.`name` AS `customername`," : "{$periodExpr} AS period,");

$columns = array('period', 'invoicecount', 'totalqty', 'totalsales');
$orderCol = isset($_POST['order'][0]['column']) ? (int)$_POST['order'][0]['column'] : 0;
$orderDir = (isset($_POST['order'][0]['dir']) && strtolower($_POST['order'][0]['dir']) === 'asc') ? 'ASC' : 'DESC';
$orderBy  = isset($columns[$orderCol]) ? $columns[$orderCol] : 'period';

$groupedSql = "SELECT {$selectExtra}
                      COUNT(DISTINCT `i`.`idtbl_invoice`) AS invoicecount,
                      COALESCE(SUM(`id`.`qty`),0) AS totalqty,
                      COALESCE(SUM(`id`.`total`),0) AS totalsales
               {$joinQuery}
               WHERE {$extraWhere}
               GROUP BY {$groupExpr}";

$mysqli = new mysqli($db_host, $db_username, $db_password, $db_name);
if ($mysqli->connect_error) {
	http_response_code(500);
	echo json_encode(['error' => 'Database connection failed: ' . $mysqli->connect_error]);
	exit;
}

$recordsFiltered = 0;
if ($res = $mysqli->query("SELECT COUNT(*) AS cnt FROM ({$groupedSql}) t")) {
	$recordsFiltered = (int)$res->fetch_assoc()['cnt'];
}
$recordsTotal = $recordsFiltered;

$sum_qty = 0; $sum_total = 0;
if ($res = $mysqli->query("SELECT COALESCE(SUM(`id`.`qty`),0) AS sqty, COALESCE(SUM(`id`.`total`),0) AS stotal {$joinQuery} WHERE {$extraWhere}")) {
	$row = $res->fetch_assoc();
	$sum_qty = (float)$row['sqty'];
	$sum_total = (float)$row['stotal'];
}

$pagedSql = "{$groupedSql} ORDER BY `{$orderBy}` {$orderDir} LIMIT {$start}, {$length}";
$data = array();
if ($res = $mysqli->query($pagedSql)) {
	while ($row = $res->fetch_assoc()) { $data[] = $row; }
}
$mysqli->close();

echo json_encode(array(
	'draw' => $draw,
	'recordsTotal' => $recordsTotal,
	'recordsFiltered' => $recordsFiltered,
	'sum_qty' => $sum_qty,
	'sum_total' => $sum_total,
	'data' => $data
));