<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
global $conn;
/*Author: Marc Marron*/ 
function validate($data) {
    $data = trim($data);
    $data = stripslashes($data);

    if (!mb_detect_encoding($data, 'UTF-8', true)) {
        $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    }

    return $data;
}

if (isset($_GET['id'])) {
    $id = validate($_GET['id']);
} elseif (isset($_POST['userID'])) {
    $id = validate($_POST['userID']);
} else {
    echo "Error: No user ID provided.";
    exit();
}

if (isset($_POST['update'])) {
    include "../connection_db.php";
    include "read.php";

    $username = validate($_POST['updatedUserName']);
    $fName = validate($_POST['updatedFirstName']);
    $lName = validate($_POST['lname']);
    $usertype = validate($_POST['type']);
    $email = validate($_POST['email']);
    $password = validate($_POST['password']);
    $id = validate($_POST['userID']);
    $address = validate($_POST['address']);
    $contactNum = !empty($_POST['contactNum']) ? validate($_POST['contactNum']) : null;


    $sqlFetch = "SELECT * FROM user WHERE userID=$id";
    $resultFetch = mysqli_query($conn, $sqlFetch);

    if (!$resultFetch) {
        echo "Error fetching current data: " . mysqli_error($conn);
        header("Location: ../accounts_view.php?action=update-user&id=$id&error=Error fetching current data");
        exit();
    }

    $currentData = mysqli_fetch_assoc($resultFetch);

    if (
        $fName == $currentData['fName'] &&
        $lName == $currentData['lName'] &&
        $password == $currentData['password'] &&
        $address == $currentData['address'] &&
        $contactNum == $currentData['contactNum']
    ) {
        echo "No changes detected.";
        header("Location: ../accounts_view.php?action=update-user&id=$id&error=No changes made");
        exit();
    }


    $result = mysqli_query($conn, $sql);

    if ($result) {  
        $sqluser = "UPDATE user SET
                username = '$username',
                password = '$password',
                contactNum = '$contactNum'
                WHERE UserID=$id";

        $sqlContentManager = "UPDATE content_manager SET
                    fName = '$fName',
                    lName = '$lName', 
                    address = '$address',
                    contactNum = '$contactNum'
                    WHERE UserID=$id";
        $resultContentManager = mysqli_query($conn, $sqlContentManager);

        $sqlAdmin = "UPDATE admin SET
                fName = '$fName',
                lName = '$lName',
                address = '$address',
                contactNum = '$contactNum'
                WHERE UserID=$id";
        $resultAdmin = mysqli_query($conn, $sqlAdmin);

        if ($resultAdmin && $resultContentManager) {
            header("Location: ../accounts_view.php?id=$id&success=Successfully Updated");
            exit();
        } else {
            $errorMessage = "Update query failed for admin or content_manager table: " . mysqli_error($conn);
            echo $errorMessage;
            header("Location: ../accounts_view.php?action=update-user&id=$id&error=$errorMessage");
            exit();
        }
    } else {
        $errorMessage = "Update query failed for user table: " . mysqli_error($conn);
        echo $errorMessage;
        header("Location: ../accounts_view.php?action=update-user&id=$id&error=$errorMessage");
        exit();
    }
}
?>
