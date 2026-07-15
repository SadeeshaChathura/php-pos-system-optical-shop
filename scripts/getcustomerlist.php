<?php

/* Select2 ajax source - Customers (Sales Report filter) */

require('config.php');

header('Content-Type: application/json');

$mysqli = new mysqli($db_host, $db_username, $db_password, $db_name);
if ($mysqli->connect_error) {
	http_response_code(500);
	echo json_encode(['error' => 'Database connection failed: ' . $mysqli->connect_error]);
	exit;
}

$term = isset($_POST['term']) ? trim($_POST['term']) : '';
$results = array();

if ($term !== '') {
	$t = $mysqli->real_escape_string($term);
	$sql = "SELECT idtbl_customer, name, customercode FROM tbl_customer
            WHERE status = 1 AND (name LIKE '%{$t}%' OR customercode LIKE '%{$t}%')
            ORDER BY name ASC LIMIT 20";
} else {
	$sql = "SELECT idtbl_customer, name, customercode FROM tbl_customer
            WHERE status = 1 ORDER BY name ASC LIMIT 20";
}

if ($res = $mysqli->query($sql)) {
	while ($row = $res->fetch_assoc()) {
		$results[] = array(
			'id' => $row['idtbl_customer'],
			'text' => $row['name'] . ' (' . $row['customercode'] . ')'
		);
	}
}
$mysqli->close();

echo json_encode(array('results' => $results));