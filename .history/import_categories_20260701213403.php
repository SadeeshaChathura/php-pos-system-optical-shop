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
// CATEGORY DATA (FROM YOUR LIST)
// =========================

$userID = 1;

$categories = [
    1 => "Frames",
    2 => "Lenses",
    3 => "SunGlasses",
    4 => "Reading Glass",
    5 => "Nose Pad",
    6 => "Nuts",
    7 => "Boxes",
    8 => "Cleaning Liquid",
    9 => "Contact Lenses",
    10 => "Frame Cleaning Matching",
    11 => "Channeling",
    12 => "Repairing",
    13 => "Ear Tip",
    14 => "Temple Chain",
    15 => "Ear Machine",
    16 => "Service"
];

// =========================
// INSERT QUERY
// =========================

$check = $conn->prepare("SELECT idtbl_material_category FROM tbl_material_category WHERE categorycode = ?");
$insert = $conn->prepare("
    INSERT INTO tbl_material_category
    (categoryname, categorycode, status, insertdatetime, updateuser, updatedatetime, tbl_user_idtbl_user)
    VALUES (?, ?, 1, NOW(), ?, NOW(), ?)
");

$success = 0;
$skipped = 0;

foreach ($categories as $code => $name) {

    $check->bind_param("s", $code);
    $check->execute();
    $check->store_result();

    if ($check->num_rows == 0) {

        $insert->bind_param("siii", $name, $code, $userID, $userID);
        $insert->execute();
        $success++;

    } else {
        $skipped++;
    }
}

$check->close();
$insert->close();
$conn->close();

echo "<h2>Category Import Completed</h2>";
echo "Inserted: $success <br>";
echo "Skipped (already exists): $skipped <br>";

?>