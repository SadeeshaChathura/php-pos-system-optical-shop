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

$filename = "materials.csv"; // 👈 PUT YOUR CSV FILE HERE

if (($handle = fopen($filename, "r")) !== FALSE) {

    // skip header
    fgetcsv($handle);

    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        $materialname = trim($data[0]);
        $materialname = trim($data[0]);
        $materialcode = trim($data[1]);
        $reorderlevel = trim($data[2]);
        $categoryid   = trim($data[3]);

        // check material exists
        $check = "SELECT idtbl_material_info 
                  FROM tbl_material_info 
                  WHERE materialinfocode = '$materialcode'";

        $result = mysqli_query($conn, $check);

        if (mysqli_num_rows($result) == 0) {

            $sql = "INSERT INTO tbl_material_info
            (materialinfocode, materialname, reorderlevel, comment, status, insertdatetime, updateuser, updatedatetime, tbl_user_idtbl_user, tbl_material_category_idtbl_material_category)
            VALUES
            ('$materialcode', '$materialname', '$reorderlevel', '', '1', NOW(), '1', NOW(), '1', '$categoryid')";

            if (mysqli_query($conn, $sql)) {
                echo "Material added: $materialname <br>";
            } else {
                echo "Error: " . mysqli_error($conn) . "<br>";
            }

        } else {
            echo "Already exists: $materialname <br>";
        }
    }

    fclose($handle);

} else {
    echo "Error opening file<br>";
}

mysqli_close($conn);

?>