<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "calcitex_pos";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// =========================
// CSV FILE LOCATION
// =========================

$filename = "category.csv"; // 👈 PUT YOUR CSV FILE HERE (same folder)

if (($handle = fopen($filename, "r")) !== FALSE) {

    // skip header
    fgetcsv($handle);

    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {

        $id = trim($data[0]);
        $categoryname = trim($data[1]);

        // check exists
        $check = "SELECT idtbl_material_category 
                  FROM tbl_material_category 
                  WHERE categoryname = '$categoryname'";

        $result = mysqli_query($conn, $check);

        if (mysqli_num_rows($result) == 0) {

            $sql = "INSERT INTO tbl_material_category
                    (categoryname, categorycode, status, insertdatetime, updateuser, updatedatetime, tbl_user_idtbl_user)
                    VALUES
                    ('$categoryname', '$categorycode', '1', NOW(), '1', NOW(), '1')";

            if (mysqli_query($conn, $sql)) {
                echo "Category added: $categoryname <br>";
            } else {
                echo "Error: " . mysqli_error($conn) . "<br>";
            }

        } else {
            echo "Already exists: $categoryname <br>";
        }
    }

    fclose($handle);

} else {
    echo "Error opening file<br>";
}

mysqli_close($conn);

?>