<?php

/*
 * DataTables example server-side processing script.
 *
 * Please note that this script is intentionally extremely simply to show how
 * server-side processing can be implemented, and probably shouldn't be used as
 * the basis for a large complex system. It is suitable for simple use cases as
 * for learning.
 *
 * See http://datatables.net/usage/server-side for full details on the server-
 * side processing requirements of DataTables.
 *
 * @license MIT - http://datatables.net/license_mit
 */

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Easy set variables
 */

// DB table to use
$table = 'tbl_invoice';

// Table's primary key
$primaryKey = 'idtbl_invoice';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(
	array( 'db' => '`u`.`idtbl_invoice`', 'dt' => 'idtbl_invoice', 'field' => 'idtbl_invoice' ),
    array( 'db' => '`u`.`tbl_customer_idtbl_customer`', 'dt' => 'tbl_customer_idtbl_customer', 'field' => 'tbl_customer_idtbl_customer' ),
	array( 'db' => '`u`.`invdate`', 'dt' => 'invdate', 'field' => 'invdate' ),
    array( 'db' => '`u`.`invtype`', 'dt' => 'invtype', 'field' => 'invtype' ),
	array( 'db' => '`ud`.`name`', 'dt' => 'name', 'field' => 'name' ),
	array( 'db' => '`ub`.`materialname`', 'dt' => 'materialname', 'field' => 'materialname' ),
    array( 'db' => '`ub`.`materialinfocode`', 'dt' => 'materialinfocode', 'field' => 'materialinfocode' ),
	array( 'db' => '`ua`.`qty`', 'dt' => 'qty', 'field' => 'qty' ),
	array( 'db' => '`u`.`grosstotal`', 'dt' => 'grosstotal', 'field' => 'grosstotal' ),
    array( 'db' => '`u`.`discount`', 'dt' => 'discount', 'field' => 'discount' ),
    array( 'db' => '`u`.`nettotal`', 'dt' => 'nettotal', 'field' => 'nettotal' ),
    array( 'db' => '`ua`.`total`', 'dt' => 'total', 'field' => 'total' ),
    array( 'db' => '`u`.`status`', 'dt' => 'status', 'field' => 'status' )
);

// SQL server connection information
require('config.php');
$sql_details = array(
	'user' => $db_username,
	'pass' => $db_password,
	'db'   => $db_name,
	'host' => $db_host
);

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * If you just want to use the basic configuration for DataTables with PHP
 * server-side, there is no need to edit below this line.
 */

// require( 'ssp.class.php' );
require('ssp.customized.class.php' );

$joinQuery = "FROM `tbl_invoice` AS `u`
LEFT JOIN `tbl_invoice_detail` AS `ua` ON (`ua`.`tbl_invoice_idtbl_invoice` = `u`.`idtbl_invoice`)
LEFT JOIN `tbl_material_info` AS `ub` ON (`ub`.`idtbl_material_info` = `ua`.`tbl_material_info_idtbl_material_info`)
LEFT JOIN `tbl_customer` AS `ud` ON (`ud`.`idtbl_customer` = `u`.`tbl_customer_idtbl_customer`)";

$extraWhere = "`u`.`status` IN (1,2)";

$groupBy = "`u`.`idtbl_invoice`";

echo json_encode(
    SSP::simple($_POST, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere, $groupBy)
);
