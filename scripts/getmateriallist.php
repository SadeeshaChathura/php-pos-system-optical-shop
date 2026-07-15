<?php

/* Select2 ajax source - Materials / Products (shared by GRN and Sales report filters) */

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
	$sql = "SELECT idtbl_material_info, materialname, materialinfocode FROM tbl_material_info
            WHERE status = 1 AND (materialname LIKE '%{$t}%' OR materialinfocode LIKE '%{$t}%')
            ORDER BY materialname ASC LIMIT 20";
} else {
	$sql = "SELECT idtbl_material_info, materialname, materialinfocode FROM tbl_material_info
            WHERE status = 1 ORDER BY materialname ASC LIMIT 20";
}

if ($res = $mysqli->query($sql)) {
	while ($row = $res->fetch_assoc()) {
		$results[] = array(
			'id' => $row['idtbl_material_info'],
			'text' => $row['materialname'] . ' (' . $row['materialinfocode'] . ')'
		);
	}
}
$mysqli->close();

echo json_encode(array('results' => $results));