<?php
// Author: Allan Avila & Marc Marron
global $conn, $result;
include "../connection_db.php"; 

session_start(); 

if (isset($_GET['id'])) {
    if (!isset($_SESSION['username'])) {
        header("Location: ../login_users.php");
        exit();
    }

    $username = $_SESSION['username'];

    $sql = "
    SELECT
        'Admin' AS type,
        a.userID,
        u.username,
        a.fname,
        a.lname,
        a.email,
        a.address,
        a.contactNum
    FROM admin a
    INNER JOIN user u ON a.userID = u.userID
    WHERE u.username = '$username'"; 

    $result = mysqli_query($conn, $sql);

    $data = array(); 

    if (!$result) {
        die("Error: " . mysqli_error($conn));
    }

    // Check if the user exists
    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        // You can access user information like $row['userID'], $row['adID'], etc.
    } else {
        // User not found, handle it as needed
        echo "User not found.";
        exit();
    }
}
?>
