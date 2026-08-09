<?php
$table = 'tbl_expense_type';
$primaryKey = 'idtbl_expense_type';

$columns = array(
	array( 'db' => '`u`.`idtbl_expense_type`', 'dt' => 'idtbl_expense_type', 'field' => 'idtbl_expense_type' ),
	array( 'db' => '`u`.`expensetypename`', 'dt' => 'expensetypename', 'field' => 'expensetypename' ),
	array( 'db' => '`u`.`expensetypecode`', 'dt' => 'expensetypecode', 'field' => 'expensetypecode' ),
	array( 'db' => '`u`.`status`', 'dt' => 'status', 'field' => 'status' )
);

require('config.php');
$sql_details = array(
	'user' => $db_username,
	'pass' => $db_password,
	'db'   => $db_name,
	'host' => $db_host
);

require('ssp.customized.class.php' );

$joinQuery = "FROM `tbl_expense_type` AS `u`";
$extraWhere = "`u`.`status` IN (1, 2)";

echo json_encode(
	SSP::simple( $_POST, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere)
);