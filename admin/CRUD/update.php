<?php
    global $conn;
    if (isset($_GET['id'])) {
        include "../connection_db.php";
        include "read.php";

        function validate($data){
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }

        $id = validate($_GET['id']);

        $sql = "SELECT 'Student' AS type, s.userID, u.username, s.fname, s.lname, s.email, s.address, s.contactNum FROM student s INNER JOIN users u ON s.userID = u.userID WHERE u.userID = $id
            UNION ALL
            SELECT 'Faculty' AS type, t.userID, u.username, t.fname, t.lname, t.email, t.address, t.contactNum FROM teacher t INNER JOIN users u ON t.userID = u.userID WHERE u.userID = $id
            UNION ALL
            SELECT 'Admin' AS type, a.userID, u.username, a.fname, a.lname, a.email, a.address, a.contactNum FROM admin a INNER JOIN users u ON a.userID = u.userID WHERE u.userID = $id";

        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
        } else {
            header("Location: accounts_management.php");
        }
    } else if (isset($_POST['update'])) {
        include "../connection_db.php";
        function validate($data)
        {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }
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
            header("Location: update_users.php?id=$id&error=No fields to update");
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

            case 'Admin':
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
                header("Location: ../accounts_view.php?action=update-user&id=$id&error=Invalid user type");
                exit();
        }

        // Update the password in the 'users' table if a new password is provided
        if (!empty($password)) {
            $sqlUserUpdate = "UPDATE users SET password='$password' WHERE userID=$id";
            $resultUserUpdate = mysqli_query($conn, $sqlUserUpdate);

            if (!$resultUserUpdate) {
                header("Location: ../accounts_management.php?action=update-user&id=$id&error=Password update failed");
                exit();
            }
        }

        // Execute the SQL statement
        $result = mysqli_query($conn, $sql);

        if ($result) {
            header("Location: ../accounts_view.php?id=$id&success=Successfully Updated");
        } else {
            header("Location: ../accounts_view.php?action=update-user&id=$id&error=Unknown Error Occurred");
        }
    } else {
        header("Location: ../accounts_view.php");
    }
?>
