<?php
    global $data, $result, $conn, $row;
    include "../connection_db.php";
    include "CRUD/read_deactivate.php";
    session_start();
    if (!isset($_SESSION['Username'])) {
        header("Location: ../index.php");
        exit();
    }
    if ($_SESSION['Type'] !== 'admin') {
        session_destroy();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <link rel="shortcut icon" type="image/x-icon" href="../images/favicon.png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css" integrity="sha512-YWzhKL2whUzgiheMoBFwW8CKV4qpHQAEuvilg9FAn5VJUDwKZZxkJNuGM4XkWuk94WCrrwslk8yWNGmY1EduTA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="../admin/stylesheets/admin.css">   
    <title>Deactivated Users</title>
</head>
<body>
    <div id="content-background">
        <?php
        if (isset($_GET['action']) && $_GET['action'] == 'view') {
            include "CRUD/view.php";
            global $row;
            ?>
            <div class="wrapper">
            <div class="sidebar">
                    <ul>
                        <li class="hover-link"><a href="accounts_view.php"><i class="fas fa-user"></i>Accounts Management</a></li>
                        <li><a href="accounts_view.php?action=add-user"><i class="fa-solid fa-user-plus"></i> Add New User</a></li>
                        <li><a href="accounts_view.php?deactivated_users.php"><i class="fas-solid fa-user-plus"></i> Deactivated Users</a></li>
                    </ul>
                </div>
            </div>
            </div>
            <header>
                <nav>
                <ul class='navbar'>
                    <span class="logo-title">
                    <li class="logo-image">
                    <a href="#">
                    <img src="../images/slu-logo.png" alt="Logo Image">
                    </a>
                </li>
                </span>
                        <input type='checkbox' id='check'/>
                        <span class="menu">
                        <li><a href="../logout.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a></li>
                        </span>
                        <label for="check" class="open-menu"><i class="fas fa-bars"></i></label>
                    </ul>
                </nav>
            </header>
            <div class="container">
                <div class="box">
                    <br>
                    <h3>Profile Information: <?= $row['fname']; ?> <?= $row['lname']; ?></h3>
                    </br>
                    <table class="table table-striped" id="table">
                        <thead>
                        <tr>
                            <th scope="col">ID No.</th>
                            <th scope="col">Last Name</th>
                            <th scope="col">First Name</th>
                            <th scope="col">Role</th>
                            <th scope="col">Email</th>
                            <th scope="col">Address</th>
                            <th scope="col">Contact #</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td><?= $row['username'] ?></td>
                            <td><?= $row['lname'] ?></td>
                            <td><?= $row['fname'] ?></td>
                            <td><?= $row['type'] ?></td>
                            <td><?= $row['email'] ?></td>
                            <td><?= $row['address'] ?></td>
                            <td><?= $row['contactNum'] ?></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div><br>
            <div class="container">
                <a href="deactivated_users.php" class="btn btn-view">Back to User List</a>
            </div>
            <?php
        } else {
                ?>
        <div id="content-background">
                <div class="wrapper">
                    <div class="sidebar">
                        <ul>
                        <li class="hover-link"><a href="accounts_view.php"><i class="fas fa-user"></i>Accounts Management</a></li>
                        <li><a href="accounts_view.php?action=add-user"><i class="fa-solid fa-user-plus"></i> Add New User</a></li>
                        <li><a href="accounts_view.php?deactivated_users.php"><i class="fas-solid fa-user-plus"></i> Deactivated Users</a></li>
                        </ul>
                    </div>
                </div>
                <header>
                    <nav>
                    <ul class='navbar'>
                    <span class="logo-title">
                        <li class="logo-image">
                    <a href="#">
                    <img src="../images/slu-logo.png" alt="Logo Image">
                        </a>
                    </li>
                </span>
                            <input type='checkbox' id='check'/>
                            <span class="menu">
                            <li><a href="../logout.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a></li>
                                </span>
                            <label for="check" class="open-menu"><i class="fas fa-bars"></i></label>
                        </ul>
                    </nav>
                </header>
                <div class="container">
                    <div class="box">
                        <div class="document-content">
                            <br><h1>Deactivated Accounts</h1><br>
                            <?php if (isset($_GET['success'])) { ?>
                                <div class="alert alert-success" role="alert">
                                    <?php echo $_GET['success']; ?>
                                </div>
                            <?php } ?>
                            <?php if (mysqli_num_rows($result)) { ?>
                                <table class="table table-striped" id="table">
                                    <thead>
                                    <tr>
                                        <th scope="col">Number</th>
                                        <th scope="col">Username</th>
                                        <th scope="col">Last Name</th>
                                        <th scope="col">First Name</th>
                                        <th scope="col">Level</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $i = 0;
                                    foreach ($data as $row) {
                                        $i++;
                                        ?>
                                        <tr>
                                            <th scope="row"><?= $i ?></th>
                                            <td><?= $row['username'] ?></td>
                                            <td><?= $row['lname'] ?></td>
                                            <td><?= $row['fname'] ?></td>
                                            <td><?= $row['type'] ?></td>
                                            <td><?= $row['status'] ?></td>
                                            <td>
                                                <a href="deactivated_users.php?action=view&id=<?= $row['userID'] ?>" class="btn btn-info"><i class="fa-solid fa-id-card"></i> View Profile</a>
                                                <a href="CRUD/activate.php?id=<?= $row['userID'] ?>" class="btn btn-success" onclick="return confirm('Are you sure you want to reactivate this user?')"><i class="fa-solid fa-user-plus"></i> Reactivate User</a>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                    </tbody>
                                </table>
                            <?php } ?>
                        </div>
                    </div>
                </div>
        </div><br><br><br>
        <?php } ?>
        <footer class ="footer">
        <div class="footer-image">
            <img src="../images/slu-logo.png" alt="Singko">
        </div>
        <h5>&copy;  2023 Singko. All Rights Reserved</h5>
        <div class="footer-right">
          <h6>Team Singko - 9481AB - IT312/312L</h6>
          <h6>AY 2023-2024</h6>
          <h6>IT Department</h6>
          <h6>School of Accountancy, Management, Computing and Information Studies</h6>
          <h6>Saint Louis University</h6>
        </div>
    </footer>
    </div>
</body>
</html>