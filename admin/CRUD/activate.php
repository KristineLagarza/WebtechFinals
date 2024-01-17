<?php
session_start();
include "../connection_db.php";

// Define the validate function
function validate($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if (!isset($_SESSION['username'])) {
    header("Location: ./index.php");
    exit();
}

global $conn;
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = validate($_GET['id']);

    // If the user is not an admin, proceed with the archive operation
    $sqlArchive = "UPDATE user SET status = 'Active' WHERE userID=?";
    $stmt = mysqli_prepare($conn, $sqlArchive);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }

    if ($resultArchive) {
        header("Location: ../deactivated_users.php?id=$id&success=Reactivated Successfully");
        exit();
    } else {
        header("Location: ../deactivated_users.php?id=$id&error=" . mysqli_error($conn));
        exit();
    }
}
?>
