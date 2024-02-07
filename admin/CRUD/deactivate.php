<?php
// CRUD/deactivate.php
include "../connection_db.php";

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $userID = $_GET['id'];
    // Update status to 'Inactive' in the user table
    $updateStatusQuery = "UPDATE user SET status = 'Inactive' WHERE userID = ?";
    $stmt = $conn->prepare($updateStatusQuery);
    $stmt->bind_param("i", $userID);

    if ($stmt->execute()) {
        // // Insert the user information into the deactivated table
        // $insertDeactivated = $conn->prepare("INSERT INTO deactivated (UserID, username, password, Type, status, fname, lname, email, address, contactNum) SELECT UserID, username, password, Type, status, fname, lname, email, address, contactNum FROM user WHERE userID = ?");
        // $insertDeactivated->bind_param("i", $userID);
        // $insertDeactivated->execute();

        header("Location: ../accounts_view.php");
        exit();
    } else {
        header("Location: ../accounts_view.php?error=Invalid user ID");
        exit();
    }
} else {
    header("Location: ./admin/accounts_view.php?error=Invalid user ID");
    exit();
}
?>
