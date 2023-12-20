<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
global $conn;
/*Author: Allan Avila*/
function validate($data) {
    $data = trim($data);
    $data = stripslashes($data);

    if (!mb_detect_encoding($data, 'UTF-8', true)) {
        $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    }

    return $data;
}

if (isset($_POST['create'])) {
    include "../connection_db.php";
    function sanitizeInput($data)
    {
        global $conn;
        if (is_array($data)) {
            return array_map('sanitizeInput', $data);
        } else {
            return mysqli_real_escape_string($conn, strip_tags(trim($data)));
        }
    }
    $fname = validate($_POST['fname']);
    $lname = validate($_POST['lname']);
    $usertype = validate($_POST['type']);
    $email = validate($_POST['email']);
    $username = validate($_POST['username']);
    $password = validate($_POST['password']);
    $address = isset($_POST['address']) ? validate($_POST['address']) : null;
    $contactNum = !empty($_POST['contactNum']) ? validate($_POST['contactNum']) : null;

    $user_data = 'fname=' . $fname . '&lname=' . $lname . '&type=' . $usertype . '&email=' . $email . '&username=' . $username . '';

    if (empty($fname) || empty($lname) || empty($email) || empty($username) || empty($password) || empty($usertype)) {
        header("Location: accounts_view.php?action=add-user?error=All required fields must be filled&$user_data");
        exit(); 
    }

    $sql_check_username = "SELECT COUNT(*) FROM user WHERE username = '$username'";
    $result_check_username = mysqli_query($conn, $sql_check_username);

    if ($result_check_username) {
        $row = mysqli_fetch_array($result_check_username);
        $username_count = $row[0];

        if ($username_count > 0) {
        
            header("Location: ../accounts_view.php?action=add-user?error=Username already exists&$user_data");
            exit();
        }
    }
    $account_status = 'Active';
    $sql_user = "INSERT INTO user (username, password, type, status) 
                  VALUES ('$username', '$password', '$usertype', '$account_status')";
    $result_user = mysqli_query($conn, $sql_user);

    if (!$result_user) {
        $error_message = mysqli_error($conn);
        header("Location: ../accounts_view.php?action=add-user&error=User creation failed: $error_message&$user_data");
        exit();
    }
    
    $userID = mysqli_insert_id($conn);

        switch ($usertype) {
                case 'content_manager':
                    $sql_content_manager = "INSERT INTO content_manager (userID, fname, lname, email";

                    if ($address !== null) {
                        $sql_content_manager .= ", address";
                    }
            
                    if ($contactNum !== null) {
                        $sql_content_manager .= ", contactNum";
                    }
            
                    $sql_content_manager .= ") VALUES ('$userID', '$fname', '$lname', '$email'";
            
                    if ($address !== null) {
                        $sql_content_manager .= ", '$address'";
                    }
            
                    if ($contactNum !== null) {
                        $sql_content_manager .= ", '$contactNum'";
                    }
            
                    $sql_content_manager .= ")";
            
                    $result_content_manager = mysqli_query($conn, $sql_content_manager);
                    break;

                    case 'admin':
                         $sql_admin = "INSERT INTO admin (userID, fname, lname, email";
        
                        if ($address !== null) {
                            $sql_admin .= ", address";
                        }
        
                        if ($contactNum !== null) {
                            $sql_admin .= ", contactNum";
                        }
        
                        $sql_admin .= ") VALUES ('$userID', '$fname', '$lname', '$email'";
        
                        if ($address !== null) {
                            $sql_admin .= ", '$address'";
                        }
        
                        if ($contactNum !== null) {
                            $sql_admin .= ", '$contactNum'";
                        }
        
                        $sql_admin .= ")";
        
                        $result_admin = mysqli_query($conn, $sql_admin);
                    
                        if (!$result_admin) {
                            $error_message = mysqli_error($conn);
                            header("Location: ../accounts_view.php?action=add-user&error=admin creation failed: $error_message&$user_data");
                            exit();
                        }
                        break;
                
                    default:
                        header("Location: ../accounts_view.php?action=add-user&error=Invalid user type&$user_data");
                        exit();
                }
            
                if (!$result) {
                $error_message = mysqli_error($conn);
                header("Location: ../accounts_view.php?action=add-user?error=$error_message&$user_data");
                exit();
            }
    } else {
        $error_message = mysqli_error($conn);
        header("Location: ../accounts_view.php?action=add-user?error=$error_message&$user_data");
        exit();
    }

?>
