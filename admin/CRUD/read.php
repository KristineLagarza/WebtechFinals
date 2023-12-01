<?php
global $conn;
include "../connection_db.php";

    // Combine all queries using UNION
    $sql = "SELECT
    'content_manager' AS type,
    t.userID,
    u.username,
    u.status,
    t.fname,
    t.lname,
    t.email
    FROM teacher t
    INNER JOIN users u ON t.userID = u.userID 
    WHERE u.status = 'Active'
    UNION ALL
    SELECT
    'Admin' AS type,
    a.userID,
    u.username,
    u.status,
    a.fname,
    a.lname,
    a.email
    FROM admin a
    INNER JOIN users u ON a.userID = u.userID WHERE u.status = 'Active' GROUP BY u.userID ORDER BY lname ";
    $result = mysqli_query($conn, $sql);

    $data = array(); // Initialize an array to store the data

    if (!$result) {
        // Query execution failed, handle the error
        echo "Error: " . mysqli_error($conn);
    } else {
        // Query executed successfully
        $num_rows = mysqli_num_rows($result);

        if ($num_rows > 0) {
            // Process and store the results in the data array
            while ($row = mysqli_fetch_assoc($result)) {
                $data[] = $row;
            }
        } else {
            echo "No records found.";
        }
}