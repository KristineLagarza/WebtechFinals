<?php
    global $conn;
    include("connection_db.php");
    session_start();

    if (isset($_POST['submit'])) {
        $Username = trim($_POST['username']);
        $Password = trim($_POST['password']);


        // Use prepared statements to prevent SQL injection
        $stmt = mysqli_prepare($conn, "SELECT * FROM user WHERE username = ? AND password = SHA1(?)");
        echo "SQL Query: SELECT * FROM user WHERE username = '$Username' AND password = '$Password'";

        if ($stmt === false) {
            die("Error in prepare statement: " . mysqli_error($conn));
        }

        mysqli_stmt_bind_param($stmt, "ss", $Username, $Password);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($result)) {
            $_SESSION['username'] = $Username;
            $_SESSION['type'] = $row['type']; // Store user type in session
            $_SESSION['status'] = $row['status'];

            if ($row['status'] == 'Inactive'){
                header("Location: index.php?id=$Username&error=Invalid Credentials");
                exit();
            }
            // Determine the user type and redirect accordingly
            switch ($row['type']) {
                case 'admin':
                    header("Location: ./admin/accounts_view.php");
                    break;
                case 'content_manager':
                    header("Location: ./contentmanager/contentmanager.php");
                    break;
                default:
                    header("Location: ./index.php?id=$Username&error=Invalid Credentials");
                    break;
            }
            if ($stmt === false) {
                die("Error in prepare statement: " . mysqli_error($conn));
            }

            exit();
        } else {
            header("Location: index.php?index.php?id=$Username&error=Invalid Credentials");
        }
        exit();
    }
    if (!$result) {
        die("Error: " . mysqli_error($conn));
    }
    
?>