<!------ Author/s: Allan Avila ------>
<!------ PHP for the Admin Login ------>
<?php
    global $data, $result, $conn, $row;
    include "../connection_db.php";
    include "CRUD/read.php";
    session_start();
    if (!isset($_SESSION['Username'])) {
        header("Location: ../index.php");
        exit();
    }
    if ($_SESSION['Type'] !== 'admin') {
        session_destroy();
    }
?>

<!------ Author/s: Allan Avila and Jemma Niduaza ------>
<!------ HTML for containing the PHP for the Admin ------>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <link rel="shortcut icon" type="image/x-icon" href="../images/favicon.png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css" integrity="sha512-YWzhKL2whUzgiheMoBFwW8CKV4qpHQAEuvilg9FAn5VJUDwKZZxkJNuGM4XkWuk94WCrrwslk8yWNGmY1EduTA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="../admin/stylesheets/admin.css">   
    <title>Account Management</title>
</head>
<body>

<!------ Author/s: Allan Avila ------>
<!------ Container and form for the adding/creating of a user ------>
<div id="content-background">
    <?php
        if (isset($_GET['action']) && $_GET['action'] == 'add-user') {
            ?>
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
                            value="<?php echo isset($_GET['fname']) ? htmlspecialchars(strip_tags($_GET['fname']), ENT_QUOTES, 'UTF-8') : ''; ?>"
                            placeholder="Enter first name" required>
                    </div>
                    <div class="form-group">
                        <label for="Last Name">Last Name</label>
                        <input type="text"
                            class="form-control"
                            id="lname"
                            name="lname"
                            value="<?php if(isset($_GET['lname'])) echo htmlspecialchars($_GET['lname'], ENT_QUOTES, 'UTF-8'); ?>"
                            placeholder="Enter last name" required>
                    </div>

                    <div class="form-group">
                        <label for="usertype">User Type</label><br>
                        <select name="type" class="form-box" required>
                        <option value="" <?php if(!isset($_GET['type']) || empty($_GET['type'])) echo 'selected'; ?>>Select User Type</option>
                        <option value="admin" <?php if(isset($_GET['type']) && $_GET['type'] === 'admin') echo 'selected'; ?>>Admin</option>
                        <option value="content_manager" <?php if(isset($_GET['type']) && $_GET['type'] === 'content_manager') echo 'selected'; ?>>Content Manager</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email"
                            class="form-control"
                            id="email"
                            name="email"
                            value="<?php if(isset($_GET['email'])) echo htmlspecialchars($_GET['email'], ENT_QUOTES, 'UTF-8'); ?>"
                            placeholder="Enter Email Address" required>
                    </div>
                    <div class="form-group">
                        <label for="Username">Username</label>
                        <input type="text"
                            class="form-control"
                            id="username"
                            name="username"
                            value="<?php if(isset($_GET['username'])) echo htmlspecialchars($_GET['username'], ENT_QUOTES, 'UTF-8'); ?>"
                            placeholder="Add Username" required>
                    </div>

                    <div class="form-group">
                    <label for="Password">Password</label>
                    <input type="password"
                        class="form-control"
                        id="name"
                        name="password"
                        value="<?php if(isset($_GET['password'])) echo htmlspecialchars($_GET['password'], ENT_QUOTES, 'UTF-8'); ?>"
                        placeholder="Create password"
                        required
                        autocomplete="off">
                    </div>
                    <div class="form-group">
                        <label for="address">Address</label>
                        <input type="text"
                            class="form-control"
                            id="address"
                            name="address"
                            value="<?php if(isset($_GET['address'])) echo htmlspecialchars($_GET['address'], ENT_QUOTES, 'UTF-8'); ?>"
                            placeholder="(Optional)">
                    </div>

                    <div class="form-group">
                        <label for="contactNum">Contact Number</label>
                        <input type="text"
                            class="form-control"
                            id="contactNum"
                            name="contactNum"
                            value="<?php if(isset($_GET['contactNum'])) echo htmlspecialchars($_GET['contactNum'], ENT_QUOTES, 'UTF-8'); ?>"
                            placeholder="Enter Contact Number">
                    </div>

                    <hr>

<!------ Author/s: Allan Avila ------>
<!------ Popup if the users wants to cancel the creation of a new user ------>
                    <div class="container text-center">
                        <a href="accounts_view.php" class="btn btn-danger" onclick="return confirm('Are you sure you want to cancel?')">Cancel</a>
                    </div>
                    <button type="submit" class="btn btn-success" name="create">Create</button><br><br>
                </form>
            </div><br><br><br>

<!------ Author/s: Allan Avila ------>
<!------ PHP, container and form for updating a user ------>
            <?php
         } elseif (isset($_GET['action']) && $_GET['action'] == 'update-user') {
            if (isset($_GET['id']) && !empty($_GET['id'])) {
                $id = $_GET['id'];
                include "../connection_db.php";
                include "CRUD/view.php";
                include "CRUD/update.php";
                global $row;
                ?>
                <div class="container">
                    <?php
                    if (isset($_GET['userID'])) {
                        include 'CRUD/update.php';
                    }?>
                    <form action="CRUD/update.php" method="post">
                        <h4 class="display-4 text-center">Update</h4><hr><br>
                        <?php if (isset($_GET['error'])) { ?>
                            <div class="alert alert-danger" role="alert">
                                <?php echo $_GET['error']; ?>
                            </div>
                        <?php } ?>
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" class="form-control" id="updatedUserName" name="updatedUserName" value="<?= isset($row['username']) ? htmlspecialchars($row['username'], ENT_QUOTES, 'UTF-8') : ''?>" readonly>
                        </div>
                        <div class="form-group">
                            <label for="fname">First name</label>
                            <input type="text" class="form-control" id="updatedFirstName" name="updatedFirstName" value="<?= isset($row['fname']) ? htmlspecialchars($row['fname'], ENT_QUOTES, 'UTF-8') : ''?>" required>

                        </div>
                        <div class="form-group">
                            <label for="lname">Last name</label>
                            <input type="text" class="form-control" id="lname" name="lname" value="<?= isset($row['lname']) ? htmlspecialchars($row['lname'], ENT_QUOTES, 'UTF-8') : '' ?>">
                        </div>
                        <div class="form-group">
                            <label for="type">User Type</label><br>
                            <input type="text" class="form-control" id="type" name="type" value="<?= isset($row['type']) ? htmlspecialchars($row['type'], ENT_QUOTES, 'UTF-8') : '' ?>" readonly>

                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?= isset($row['email']) ? htmlspecialchars($row['email'], ENT_QUOTES, 'UTF-8') : '' ?>" readonly>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="password" name="password" value="" placeholder="Enter Password">
                        </div>

                        <div class="form-group">
                            <label for="address">Address</label>
                            <input type="text" class="form-control" id="address" name="address" value="<?= isset($row['address']) ? htmlspecialchars($row['address'], ENT_QUOTES, 'UTF-8') : '' ?>" placeholder="Enter Address">
                        </div>

                        <div class="form-group">
                            <label for="contactNum">Contact Number</label>
                            <input type="text"
                                class="form-control"
                                id="contactNum"
                                name="contactNum"
                                value="<?= isset($row['contactNum']) ? htmlspecialchars($row['contactNum'], ENT_QUOTES, 'UTF-8') : '' ?>"
                                required
                                placeholder="Enter Contact Number">
                        </div>

                        <input type="text" name="userID" value="<?=$row['userID']?>" hidden>
                        <button href="../admin/accounts_view.php" class="btn btn-danger" onclick="return confirm('Are you sure you want to cancel update?')" name="cancel">Cancel</button></br></br>
                        <button type="submit" class="btn btn-primary" name="update">Update</button></br></br>
                    </form>
                </div><br><br><br>

<!------ Author/s: Allan Avila ------>
<!------ PHP, container and table for viewing a user ------>
                <?php
            }
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
        }  
        ?>

<!------ Author/s: Allan Avila and Jemma Niduaza ------>
<!------ HTML of the top navigation bar ------>
        <div class="topnav">
    <ul class='navbar'>
        <span class="logo-title">
            <li class="logo-image">
                <a href="#">
                    <img src="../images/slu-logo.png" alt="Logo Image">
                </a>
            </li>
        </span>
        <span class="menu">
            <li><a href="../logout.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a></li>
        </span>
    </ul>
</div>

<!------ Author/s: Allan Avila and Jemma Niduaza ------>
<!------ HTML and PHP of the side navigation bar ------>
<div class="wrapper">
                <div class="sidebar">
                    <ul>
                        <li class="hover-link"><a href="accounts_view.php"><i class="fas fa-user"></i>Accounts Management</a></li>
                        <li><a href="accounts_view.php?action=add-user"><i class="fa-solid fa-user-plus"></i> Add New User</a></li>
                        <li><a href="deactivated_users.php"><i class="fas-solid fa-user-plus"></i> Deactivated Users</a></li>
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
                        if (isset($_GET['username'])) {
    
                        $username = $_GET['username'];

                        $stmt = $conn->prepare("SELECT * FROM user WHERE username = ?");
    
                        if ($stmt) {
                        $stmt->bind_param("s", $username);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        if ($result === false) {
                                 // Handle the error, you can print the error message or log it
                             echo "Error: " . mysqli_error($conn);
                        } else {
                                // Check if there are rows in the result set
                                if (mysqli_num_rows($result) > 0) {

                                while ($row = mysqli_fetch_assoc($result)) {
                                    }
                        } else {
                            echo "No rows found.";
                            }

                            // Free the result set
                            mysqli_free_result($result);
                                }
                                include "CRUD/read.php";

                                $stmt->close();
                            } else {

                                echo "Error: " . mysqli_error($conn);
                            }
                        } else {

                            echo "Username is not set in the URL.";
                        }
                        ?>

<!------ Author/s: Allan Avila ------>
<!------ HTML and PHP of the table of the available users ------>
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
                                    <td><?= htmlspecialchars($row['username']) ?></td>
                                    <td><?= htmlspecialchars($row['lname']) ?></td>
                                    <td><?= htmlspecialchars($row['fname']) ?></td>
                                    <td><?= htmlspecialchars($row['type']) ?></td>
                                    <td><?= htmlspecialchars($row['status']) ?></td>
                                    <td>
                                <a href="accounts_view.php?action=view&id=<?= $row['userID'] ?>" class="btn btn-success"><i class="fa-solid fa-id-card"></i> View Profile</a>
                                <a href="accounts_view.php?action=update-user&id=<?= $row['userID'] ?>" class="btn btn-info"><i class="fa-solid fa-user-pen"></i> Update Info</a>
                                <a href="CRUD/delete.php?id=<?= $row['userID'] ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this user?')"><i class="fa-solid fa-user-xmark"></i> Delete User</a>
                                <?php if ($row['status'] === 'Active') { ?>
                                <a href="CRUD/deactivate.php?id=<?= $row['userID'] ?>" class="btn btn-warning" onclick="return confirm('Are you sure you want to deactivate this user?')">Deactivate User</a> <!-- Added Deactivate User button -->
                                <?php } ?>
                                </tr>
                                <?php } ?>
                                </tbody>
                            </table>
                    </div>
                </div>
                
            </div>
    </div><br><br>
    </div>
</body>

<!------ Author/s: Jemma Niduaza ------>
<!------ Footer of the Admin ------>
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
</html>
