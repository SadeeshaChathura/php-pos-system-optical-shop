<?php
$table = 'tbl_expense';
$primaryKey = 'idtbl_expense';

$columns = array(
	array( 'db' => '`u`.`idtbl_expense`', 'dt' => 'idtbl_expense', 'field' => 'idtbl_expense' ),
	array( 'db' => '`u`.`expenseno`', 'dt' => 'expenseno', 'field' => 'expenseno' ),
	array( 'db' => '`u`.`expensedate`', 'dt' => 'expensedate', 'field' => 'expensedate' ),
	array( 'db' => '`ua`.`expensetypename`', 'dt' => 'expensetypename', 'field' => 'expensetypename' ),
	array( 'db' => '`u`.`amount`', 'dt' => 'amount', 'field' => 'amount' ),
	array( 'db' => '`ub`.`branch`', 'dt' => 'branch', 'field' => 'branch' ),
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

$joinQuery = "FROM `tbl_expense` AS `u`
	LEFT JOIN `tbl_expense_type` AS `ua` ON (`ua`.`idtbl_expense_type` = `u`.`tbl_expense_type_idtbl_expense_type`)
	LEFT JOIN `tbl_company_branch` AS `ub` ON (`ub`.`idtbl_company_branch` = `u`.`tbl_company_branch_idtbl_company_branch`)";

$extraWhere = "`u`.`status` IN (1, 2)";

echo json_encode(
	SSP::simple( $_POST, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere)
);