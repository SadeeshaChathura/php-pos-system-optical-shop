<?php

/* Select2 ajax source - Suppliers (GRN Report filter) */

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
	$sql = "SELECT idtbl_supplier, suppliername, suppliercode FROM tbl_supplier
            WHERE status = 1 AND (suppliername LIKE '%{$t}%' OR suppliercode LIKE '%{$t}%')
            ORDER BY suppliername ASC LIMIT 20";
} else {
	$sql = "SELECT idtbl_supplier, suppliername, suppliercode FROM tbl_supplier
            WHERE status = 1 ORDER BY suppliername ASC LIMIT 20";
}

if ($res = $mysqli->query($sql)) {
	while ($row = $res->fetch_assoc()) {
		$results[] = array(
			'id' => $row['idtbl_supplier'],
			'text' => $row['suppliername'] . ' (' . $row['suppliercode'] . ')'
		);
	}
}
$mysqli->close();

echo json_encode(array('results' => $results));