<?php
    global $conn;
    session_start();
    if (!isset($_SESSION['username'])) {
        header("Location: ../index.php");
        exit();
    }
    //if user is not admin then destroy session
    if ($_SESSION['type'] !== 'Admin' && !$_SESSION['userID']) {
        session_destroy();
    }
    include "crud_for_accounts/read.php";
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Home</title>
        <link rel="icon" type="image/png" href="../img/SAMCIS Logo.png">
        <link rel="stylesheet" type="text/css" href="../adminside/stylesheets/content_style.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css" integrity="sha512-YWzhKL2whUzgiheMoBFwW8CKV4qpHQAEuvilg9FAn5VJUDwKZZxkJNuGM4XkWuk94WCrrwslk8yWNGmY1EduTA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <!-- Prevent caching -->
        <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
        <meta http-equiv="Pragma" content="no-cache">
        <meta http-equiv="Expires" content="0">
        <script src="https://kit.fontawesome.com/b99e675b6e.js"></script>
    </head>
    <body>
        <div id="content-background">
            <?php
            if (isset($_GET['id']) && !empty($_GET['id']) && isset($_SESSION['username'])) {
                $id = $_GET['id'];
                include "../connection_db.php";
                include 'crud_for_accounts/view_account.php';
                global $row, $result;
                ?>
            <?php }?>
            <div class="wrapper">
                <div class="sidebar">
                    <ul>
                        <p>Core</p><br>
                        <li class="hover-link"><a href="adminhome.php"><i class="fas fa-home"></i>Dashboard</a></li>
                        <li><a href="accounts_management.php"><i class="fas fa-user"></i>Accounts & User Management</a></li>
                        <br><p>Interface</p><br>
                        <li><a href="manage_all_projects.php"><i class="fa-solid fa-folder"></i> Project Management</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </body>
</html>
