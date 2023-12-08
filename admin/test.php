<?php
    global $data, $result, $conn, $row;
    session_start();
    include "../connection_db.php";
    include "CRUD/read.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="stylesheet" type="text/css" href="../admin/stylesheets/content_style.css">
    <link rel="stylesheet" type="text/css" href="../stylesheets/header_footer_sidebar.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Management</title>
</head>
<body>
<div class="Container">
    <nav>
        <ul class='navbar'>
            <span class="logo-title">
                <li class="text-logo"><a><h1>SV STREAMING PLATFORM</h1></a></li>
            </span>
            <input type='checkbox' id='check'/>
            <span class="menu"><!--
                <li><a href="my_profile.php?id=<?= $_SESSION['username'] ?>"><i class="fa-regular fa-circle-user"></i> <?php echo $_SESSION['username']; ?></a></li>
                <li><a href="../logout.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a></li>
                <label for="check" class="close-menu"><i class="fas fa-times"></i></label>
            </span>-->
            <label for="check" class="open-menu"><i class="fas fa-bars"></i></label>
        </ul>
    </nav>
</div>
<div id="content-background">
    <?php
        if (isset($_GET['action']) && $_GET['action'] == 'add-user') {
            /*-----------------------------------------------CREATE------------------------------------------------- */
            ?>
            <div class="wrapper">
                <div class="sidebar">
                    <ul>
                        <li class=""><a href="accounts_view.php" onclick="return confirm('Are you sure you want to cancel?')"><i class="fas fa-user"></i>Account Management</a></li>
                        <li class="hover-link"><a href="#"><i class="fa-solid fa-user-pen"></i> Update Account</a></li>
                        <li class=""><a href="deleted_accounts.php" onclick="return confirm('Are you sure you want to cancel?')"></i> Deleted Account</a></li>
                        <li class=""><a href="archived_accounts.php" onclick="return confirm('Are you sure you want to cancel?')"><i class="fa-solid fa-user-slash"></i> Archived Account</a></li>
                    </ul>
                </div>
            </div>
            <div class="container">
                <form action="CRUD/create.php" method="post">
                    <h4 class="display-4 text-center">Create an Account</h4><hr><br>
                    <?php if (isset($_GET['error'])) { ?>
                        <div class="alert alert-danger" role="alert">
                            <?php echo $_GET['error']; ?>
                        </div>
                    <?php } ?>
                    <div class="form-group">
                        <label for="First Name">First Name</label>
                        <input type="text"
                               class="form-control"
                               id="fname"
                               name="fname"
                               value="<?php if(isset($_GET['fname']))
                                   echo($_GET['fname']); ?>"
                               placeholder="Enter first name" required>
                    </div>
                    <!-- ... (Other form fields for creating a new user) ... -->
                    <button type="submit" class="btn btn-success" name="create">Create</button><br><br>
                </form>
            </div><br><br><br>
            <?php
        } elseif (isset($_GET['action']) && $_GET['action'] == 'view') {
            if (isset($_GET['id']) && !empty($_GET['id'])) {
                $id = $_GET['id'];
                include "../connection_db.php";
                include "CRUD/view.php";
                global $row;
                ?>
                <div class="container">
                    <div class="box">
                        <br>
                        <h3>Profile Information: <?= $row['fname']; ?> <?= $row['lname']; ?></h3>
                        </br>
                        <table class="table table-striped" id="table">
                        <thead>
                            <tr>
                                <th scope="col">Username</th>
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
                    <a href="accounts_view.php" class="btn btn-view">Back to User List</a>
                </div>
                <?php
            }
        } elseif (isset($_GET['action']) && $_GET['action'] == 'update-user') {
            if (isset($_GET['id']) && !empty($_GET['id'])) {
                $id = $_GET['id'];
                include "../connection_db.php";
                include "CRUD/view.php";
                global $row;
                ?>
                <div class="container">
                    <form action="accounts_view.php?action=update-user&id=<?= $id ?>" method="post">
                        <h4 class="display-4 text-center">Update Profile</h4><hr><br>
                        <?php if (isset($_GET['error'])) { ?>
                            <div class="alert alert-danger" role="alert">
                                <?php echo $_GET['error']; ?>
                            </div>
                        <?php } ?>
                        <div class="form-group">
                            <label for="updatedFirstName">First Name</label>
                            <input type="text" class="form-control" id="updatedFirstName" name="updatedFirstName" value="<?= isset($row['fname']) ? $row['fname'] : ''?>" required>
                            <input type="text" class="form-control" id="fname" name="fname" value="<?= isset($row['fname']) ? $row['fname'] : '' ?>">
                        </div>
                        <div class="form-group">
                            <label for="updatedLastName">Last Name</label>
                            <input type="text" class="form-control" id="updatedLastName" name="updatedLastName" value="<?= $row['lname'] ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="updatedLastName">Email</label>
                            <input type="text" class="form-control" id="updatedEmail" name="updatedEmail" value="<?= $row['email'] ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="updatedLastName">Address</label>
                            <input type="text" class="form-control" id="updatedEmail" name="updatedEmail" value="<?= $row['address'] ?>" placeholder="Input Address">
                        </div>

                        <div class="form-group">
                            <label for="updatedContact">Contact Number</label>
                            <input type="text" class="form-control" id="updatedContact" name="updatedContact" value="<?= $row['contactNum'] ?>" placeholder="Input Number">
                        </div>

                        <hr>
                        <div class="container text-center">
                            <a href="accounts_view.php" class="btn btn-danger" onclick="return confirm('Are you sure you want to cancel?')">Cancel</a>
                        </div>
                        <input type="hidden" name="update" value="1">
                        <button type="submit" class="btn btn-success" name="update">Update</button><br><br>
                    </form>
                </div><br><br><br>
                <?php
            }
        }
    ?>
    <div class="wrapper">
        <div class="sidebar">
            <ul>
                <li class="hover-link"><a href="accounts_view.php"><i class="fas fa-user"></i>Account Management</a></li>
                <li><a href="accounts_view.php?action=add-user"><i class="fa-solid fa-user-plus"></i> Add New Account</a></li>
                <li class=""><a href="deleted_accounts.php"><i class="fa-solid fa-user-slash"></i> Deleted Account</a></li>
                <li class=""><a href="archived_accounts.php"><i class="fa-solid fa-user-slash"></i> Archived Account</a></li>
            </ul>
        </div>
    </div>
    <div class="container">
        <div class="box">
            <div class="document-content">
                <?php if (isset($_GET['success'])) { ?>
                    <div class="alert alert-success" role="alert">
                        <?php echo $_GET['success']; ?>
                    </div>
                <?php } ?>
                <?php if (isset($_GET['successful'])) { ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $_GET['successful']; ?>
                    </div>
                <?php } ?>
                <?php if (isset($_GET['error'])) { ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $_GET['error']; ?>
                    </div>
                <?php } ?>
                <?php
                $result = mysqli_query($conn, "SELECT * FROM user");

                if ($result === false) {
                    // Handle the error, you can print the error message or log it
                    echo "Error: " . mysqli_error($conn);
                } else {
                    // Check if there are rows in the result set
                    if (mysqli_num_rows($result) > 0) {

                        while ($row = mysqli_fetch_assoc($result)) {
                        }
                    } else {
                        // No rows found
                        echo "No rows found.";
                    }

                    // Free the result set
                    mysqli_free_result($result);
                }
                ?>
                <table class="table table-striped" id="table">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Username.</th>
                        <th scope="col">Last Name</th>
                        <th scope="col">First Name</th>
                        <th scope="col">Level</th>
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
                                <a href="accounts_view.php?action=view&id=<?= $row['userID'] ?>" class="btn btn-success"><i class="fa-solid fa-id-card"></i> View Profile</a>
                                <a href="accounts_view.php?action=update-user&id=<?= $row['userID'] ?>" class="btn btn-info"><i class="fa-solid fa-user-pen"></i> Update Info</a>
                                <a href="CRUD/delete.php?id=<?= $row['userID'] ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to deactivate this user?')"><i class="fa-solid fa-user-xmark"></i> Deactivate User</a>
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php include ('../header_sidebar_footer/footer.php') ?>
</body>
</html>
