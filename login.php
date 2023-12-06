<?php
global $conn;
include("connection_db.php");
session_start();

if (isset($_POST['submit'])) {
    $Username = trim($_POST['username']);
    $Password = trim($_POST['password']);

    // Use prepared statements to prevent SQL injection
    $stmt = mysqli_prepare($conn, "SELECT * FROM user WHERE Username = ?");

    if ($stmt === false) {
        die("Error in prepare statement: " . mysqli_error($conn));
    }

    mysqli_stmt_bind_param($stmt, "s", $Username);
    $executeResult = mysqli_stmt_execute($stmt);

    if (!$executeResult) {
        // Error occurred during execution
        die("Error: " . mysqli_error($conn));
    } 

    $result = mysqli_stmt_get_result($stmt); // Fetch result from the statement

    if (!$result) {
        // Error retrieving result
        die("Result error: " . mysqli_error($conn));
    }

    if ($row = mysqli_fetch_assoc($result)) {
        // Assuming passwords are not hashed in the database
        if ($row['Password'] == $Password) {
            $_SESSION['Username'] = $Username;
            $_SESSION['type'] = $row['type']; // Store user type in session
            $_SESSION['status'] = $row['status'];

            if ($row['status'] == 'Inactive'){
                header("Location: index.php?id=$Username&error=Inactive Account");
                exit();
            }

            // Determine the user type and redirect accordingly
            switch ($row['type']) {
                case 'admin':
                    header("Location: ./admin/accounts_view.php");
                    break;
                case 'content_manager':
                    header("Location: ./contentmanager/index.ejs");
                    break;
                default:
                    header("Location: ./index.php?id=$Username&error=Invalid Credentials");
                    break;
            }

            exit();
        } else {
            header("Location: index.php?index.php?id=$Username&error=Invalid Credentials");
        }
    } else {
        header("Location: index.php?index.php?id=$Username&error=Invalid Credentials");
    }

    if (!$result) {
        // Error retrieving result
        die("Result error: " . mysqli_error($conn));
    }
}
?>
