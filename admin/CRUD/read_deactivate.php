<?php
global $conn;
include "../connection_db.php";

$sql = "(
    SELECT
        'content_manager' AS type,
        c.userID,
        u.username,
        u.status,
        c.fname,
        c.lname,
        c.email
    FROM content_manager c
    INNER JOIN user u ON c.userID = u.userID 
    WHERE u.status = 'Inactive'
)
UNION ALL
(
    SELECT
        'Admin' AS type,
        a.userID,
        u.username,
        u.status,
        a.fname,
        a.lname,
        a.email
    FROM admin a
    INNER JOIN user u ON a.userID = u.userID 
    WHERE u.status = 'Inactive'
)
ORDER BY lname"; 

$result = mysqli_query($conn, $sql);

$data = array();

if (!$result) {
    echo "Error: " . mysqli_error($conn);
} else {
    $num_rows = mysqli_num_rows($result);

    if ($num_rows > 0) {
        
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
    } else {
        echo "No records found.";
    }
}
?>


