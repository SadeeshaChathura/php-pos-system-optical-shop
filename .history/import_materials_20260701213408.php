<?php

// =========================
// DB CONNECTION
// =========================

$host = "localhost";
$user = "root";
$pass = "";
$db   = "calcitex_pos";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$conn->set_charset("utf8");

// =========================
// CSV FILE
// =========================

$csvFile = "materials.csv";

if (!file_exists($csvFile)) {
    die("CSV file not found.");
}

$file = fopen($csvFile, "r");

// Skip header row
fgetcsv($file);

$userID = 1;

$stmt = $conn->prepare("
INSERT INTO tbl_material_info
(
    materialinfocode,
    materialname,
    reorderlevel,
    comment,
    status,
    insertdatetime,
    updateuser,
    updatedatetime,
    tbl_user_idtbl_user,
    tbl_material_category_idtbl_material_category
)
VALUES
(
    ?, ?, ?, '',
    1,
    NOW(),
    ?,
    NOW(),
    ?,
    ?
)
");

$success = 0;
$failed = 0;

while (($row = fgetcsv($file, 1000, ",")) !== FALSE) {

    $materialName = trim($row[0]);
    $materialCode = trim($row[1]);
    $reorderLevel = (int)$row[2];
    $categoryID   = (int)$row[3];

    $stmt->bind_param(
        "ssiiii",
        $materialCode,
        $materialName,
        $reorderLevel,
        $userID,
        $userID,
        $categoryID
    );

    if ($stmt->execute()) {
        $success++;
    } else {
        $failed++;
    }
}

fclose($file);
$stmt->close();
$conn->close();

echo "<h2>Material Import Completed</h2>";
echo "Success: $success <br>";
echo "Failed: $failed <br>";

?>