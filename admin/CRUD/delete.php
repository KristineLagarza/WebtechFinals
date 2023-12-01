<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ./.php");
    exit();
}

global $conn;
if (isset($_GET['id'])) {
    include "../../connection_db.php";
    function validate($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    $id = validate($_GET['id']);

    // Check if the user is an admin before attempting to delete
    $sqlCheckAdmin = "SELECT type FROM users WHERE userID=$id";
    $resultCheckAdmin = mysqli_query($conn, $sqlCheckAdmin);

    if ($resultCheckAdmin) {
        $row = mysqli_fetch_assoc($resultCheckAdmin);

        if ($row['type'] === 'Admin') {
            header("Location: ../accounts_view.php?id=$id&error=Admin users cannot be ARCHIVED!");
            exit();
        }
    }

    // If the user is not an admin, proceed with the archive operation
    $sqlArchive = "UPDATE users SET status = 'Inactive' WHERE userID=$id";
    $resultArchive = mysqli_query($conn, $sqlArchive);


    if ($resultArchive) {
        header("Location: ../accounts_view.php?id=$id&successful=Deactivated Successfully");
        exit();
    } else {
        header("Location: ../accounts_view.php?id=$id&error=Unknown Error Occurred");
        exit();
    }
} else {
    header("Location: ../accounts_view.php");
    exit();
}
?>
