<?php
include("connection_db.php");
session_start();

if (isset($_POST['username'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $st = $conn->prepare("SELECT * FROM user WHERE username=? and password=?");
    $st->bind_param('ss', $username, $password);
    $st->execute();
    $result = $st->get_result();

    if ($result->num_rows != 0) {
        $user = $result->fetch_assoc();

        if ($user['Type'] == 'admin') {
            $_SESSION['Username'] = $username;
            $_SESSION['Type'] = 'admin';
            header('Location: admin/accounts_view.php');
            exit(); 
        } elseif ($user['Type'] == 'content_manager') {
            $_SESSION['Username'] = $username;
            $_SESSION['Type'] = 'content_manager';
            header('Location: contentmanager/views/dashboard.ejs');
            exit(); 
        }
    }

    $st->close();

    header("Location: index.php?index.php?id=$username&error=Invalid Credentials");
}
?>
