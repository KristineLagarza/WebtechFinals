<?php
include("connection_db.php");
session_start();

if (isset($_POST['username'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $st = $conn->prepare("SELECT * FROM user WHERE Username=? and Password=?");
    $st->bind_param('ss', $username, $password);
    $st->execute();
    $result = $st->get_result();

    if ($result->num_rows != 0) {
        $user = $result->fetch_assoc();

        if ($user['Type'] == 'admin') {
            $_SESSION['Username'] = $username;
            $_SESSION['Type'] = 'admin';
            header('Location: admin/admin.php');
            exit(); 
        } elseif ($user['Type'] == 'content_manager') {
            $_SESSION['Username'] = $username;
            $_SESSION['Type'] = 'content_manager';
            header('Location: contentmanager/views/index.ejs');
            exit(); 
        }
    }

    $st->close();

    header("Location: index.php?index.php?id=$Username&error=Invalid Credentials");
}
?>
