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


global $conn;
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = validate($_GET['id']);

    // If the user is not an admin, proceed with the activation operation
    $sqlActivate = "UPDATE user SET status = 'Active' WHERE userID=?";
    $stmt = mysqli_prepare($conn, $sqlActivate);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $id);
        $resultActivate = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
    }

    if ($resultActivate) {
        header("Location: ../deactivated_users.php?success=Reactivated Successfully");
        exit();
    } else {
        header("Location: ../deactivated_users.php?error=" . mysqli_error($conn));
        exit();
    }
} else {
    header("Location: ../deactivated_users.php?error=Invalid user ID");
    exit();
}
?>
