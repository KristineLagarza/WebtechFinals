<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
global $conn;

function validate($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if (isset($_GET['id'])) {
    include "../connection_db.php";
    $id = validate($_GET['id']); // Validate and set $id
} else {
    // Handle the case where $_GET['id'] is not set
    echo "Error: No user ID provided.";
    exit();
}

if (isset($_POST['userID'])) {
    include "../connection_db.php";
    include "read.php";

    $id = validate($_GET['id']);

    
    $sql = "
        SELECT
            u.type,
            u.userID,
            u.username,
            u.status,
            COALESCE(a.fname, c.fname) AS fname,
            COALESCE(a.lname, c.lname) AS lname,
            COALESCE(a.email, c.email) AS email,
            COALESCE(a.address, c.address) AS address,
            COALESCE(a.contactNum, c.contactNum) AS contactNum
        FROM user u
        LEFT JOIN admin a ON u.userID = a.userID
        LEFT JOIN content_manager c ON u.userID = c.userID
        WHERE u.userID = $id
    ";

    $result = mysqli_query($conn, $sql);

    if ($result) {
        // Check if any rows were affected
        $rowsAffected = mysqli_affected_rows($conn);

        if ($rowsAffected > 0) {
            header("Location: ./accounts_view.php?id=$id&success=Successfully Updated");
            exit();
        } else {
            header("Location: ./accounts_view.php?action=update-user&id=$id&error=No changes made");
            exit();
        }
        
        } else {
            // Print the error details for debugging
            echo "Error: " . mysqli_error($conn);
            exit();
        }
    } elseif (isset($_POST['update'])) {

    $fname = validate($_POST['fname']);
    $lname = validate($_POST['lname']);
    $usertype = validate($_POST['type']);
    $email = validate($_POST['email']);
    $password = isset($_POST['password']) ? validate($_POST['password']) : null;
    $id = validate($_POST['userID']);
    $address = isset($_POST['address']) ? validate($_POST['address']) : null;
    $contactNum = isset($_POST['contactNum']) ? validate($_POST['contactNum']) : null;

    // Check if at least one field is filled (other than required fields)
    if (empty($fname) && empty($lname) && empty($email) && empty($password) && empty($address) && empty($contactNum)) {
        header("Location: update.php?id=$id&error=No fields to update");
        exit();
    }

    // Check the user's type and update the corresponding table
    switch ($usertype) {
        case 'content_manager':
            $sql = "UPDATE content_manager SET";

            if (!empty($fname)) {
                $sql .= " fname='$fname',";
            }

            if (!empty($lname)) {
                $sql .= " lname='$lname',";
            }

            if (!empty($email)) {
                $sql .= " email='$email',";
            }

            if (!empty($address)) {
                $sql .= " address='$address',";
            }

            if (!empty($contactNum)) {
                $sql .= " contactNum='$contactNum',";
            }

            // Remove trailing comma
            $sql = rtrim($sql, ',');

            $sql .= " WHERE userID=$id";
            break;

        case 'admin':
            $sql = "UPDATE admin SET";

            if (!empty($fname)) {
                $sql .= " fname='$fname',";
            }

            if (!empty($lname)) {
                $sql .= " lname='$lname',";
            }

            if (!empty($email)) {
                $sql .= " email='$email',";
            }

            if (!empty($address)) {
                $sql .= " address='$address',";
            }

            if (!empty($contactNum)) {
                $sql .= " contactNum='$contactNum',";
            }

            // Remove trailing comma
            $sql = rtrim($sql, ',');

            $sql .= " WHERE userID=$id";
            break;

        default:
            header("Location: ./accounts_view.php?action=update-user&id=$id&error=Invalid user type");
            exit();
    }

    // Update the password in the 'users' table if a new password is provided
    if (!empty($password)) {
        $sqlUserUpdate = "UPDATE user SET password='$password' WHERE userID=$id";
        $resultUserUpdate = mysqli_query($conn, $sqlUserUpdate);

        if (!$resultUserUpdate) {
            header("Location: ./accounts_management.php?action=update-user&id=$id&error=Password update failed");
            exit();
        }
    }

    // Execute the SQL statement
    $result = mysqli_query($conn, $sql);

    if ($result) {
        header("Location: ./accounts_view.php?id=$id&success=Successfully Updated");
    } else {
        header("Location: ./accounts_view.php?action=update-user&id=$id&error=Unknown Error Occurred");
    }
} else {
    global $row;
}
?>
