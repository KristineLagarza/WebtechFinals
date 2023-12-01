<?php
    global $data, $result, $conn, $row;
    session_start();
    if (!isset($_SESSION['username'])) {
        header("Location: ../login_users.php");
        exit();
    }
    //if user is not admin then destroy session
    if ($_SESSION['type'] !== 'Admin') {
        session_destroy();
    }
    include "../db.php";
    include "crud_for_accounts/read.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Management</title>
    <link rel="icon" type="image/png" href="../img/SAMCIS Logo.png">
    <link rel="stylesheet" type="text/css" href="../adminside/stylesheets/content_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css" integrity="sha512-YWzhKL2whUzgiheMoBFwW8CKV4qpHQAEuvilg9FAn5VJUDwKZZxkJNuGM4XkWuk94WCrrwslk8yWNGmY1EduTA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>
<body>
    <div id="content-background">
    <?php
        if (isset($_GET['action']) && $_GET['action'] == 'add-user') {
            ?>
            <div class="wrapper">
                <div class="sidebar">
                    <ul>
                        <p>Core</p>
                        <li><a href="adminhome.php" onclick="return confirm('Are you sure you want to cancel?')"><i class="fas fa-home"></i>Dashboard</a></li>
                        <li class=""><a href="accounts_management.php" onclick="return confirm('Are you sure you want to cancel?')"><i class="fas fa-user"></i>Accounts & User Management</a></li>
                        <li class="hover-link"><a href="accounts_management.php?action=add-user"><i class="fa-solid fa-user-plus"></i> Add New User</a></li>
                        <li class=""><a href="archived_accounts.php" onclick="return confirm('Are you sure you want to cancel?')"><i class="fa-solid fa-user-slash"></i> Archived Users</a></li>
                    </ul>
                </div>
            </div>
            <header>
                <nav>
                    <ul class='nav-bar'>
                        <li class='logo'><a href='adminhome.php'><img src='./images/SAMCIS Logo.png' alt='logo' /></a></li>
                        <span class="logo-title">
                            <li class="text-logo"><a><h1>SAINT LOUIS UNIVERSITY</h1></a></li>
                            <li class="text-logo2"><a>Online Thesis and Capstone Catalog</a></li>
                        </span>
                        <input type='checkbox' id='check'/>
                        <span class="menu">
                            <li><a href="my_profile.php?id=<?= $_SESSION['username'] ?>"><i class="fa-regular fa-circle-user"></i> <?php echo $_SESSION['username']; ?></a></li>
                            <li><a href="../logout.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a></li>
                            <label for="check" class="close-menu"><i class="fas fa-times"></i></label>
                        </span>
                        <label for="check" class="open-menu"><i class="fas fa-bars"></i></label>
                    </ul>
                </nav>
            </header>
            <div class="container">
                <form action="crud_for_accounts/create.php" method="post">
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
                    <div class="form-group">
                        <label for="Last Name">Last Name</label>
                        <input type="text"
                               class="form-control"
                               id="lname"
                               name="lname"
                               value="<?php if(isset($_GET['lname']))
                                   echo($_GET['lname']); ?>"
                               placeholder="Enter last name" required>
                    </div>
                    <div class="form-group">
                        <label for="usertype">User Type</label></br>
                        <select name="type" class="form-box" required>
                            <option value="">Select User Type</option>
                            <option value="Admin">Admin</option>
                            <option value="Faculty">Faculty</option>
                            <option value="Student">Student</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email"
                               class="form-control"
                               id="email"
                               name="email"
                               value="<?php if(isset($_GET['email']))
                                   echo($_GET['email']); ?>"
                               placeholder="Enter SLU email" required>
                    </div>
                    <div class="form-group">
                        <label for="Username">User ID</label>
                        <input type="text"
                               class="form-control"
                               id="username"
                               name="username"
                               value="<?php if(isset($_GET['username']))
                                   echo($_GET['username']); ?>"
                               placeholder="Add SLU ID no." required>
                    </div>
                    <div class="form-group">
                        <label for="Password">Password</label>
                        <input type="password"
                               class="form-control"
                               id="name"
                               name="password"
                               value="<?php if(isset($_GET['password'])) echo($_GET['password']); ?>"
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
                               value="<?php if(isset($_GET['address']))
                                   echo($_GET['address']); ?>"
                               placeholder="(Optional)">
                    </div>
                    <div class="form-group">
                        <label for="contactNum">Contact Number</label>
                        <input type="text"
                               class="form-control"
                               id="contactNum"
                               name="contactNum"
                               value="<?php if(isset($_GET['contactNum']))
                                   echo($_GET['contactNum']); ?>"
                               placeholder="(Optional)">
                    </div>
                    <hr>
                    <div class="container text-center">
                        <a href="accounts_management.php" class="btn btn-danger" onclick="return confirm('Are you sure you want to cancel?')">Cancel</a>
                    </div>
                    <button type="submit" class="btn btn-success" name="create">Create</button><br><br>
                </form>
            </div><br><br><br>
            <?php
        } elseif (isset($_GET['action']) && $_GET['action'] == 'update-user') {
            if (isset($_GET['id']) && !empty($_GET['id'])) {
                $id = $_GET['id'];
                include "../connection_db.php";
                include 'crud_for_accounts/update.php';
                global $row;
                ?>
                <div class="wrapper">
                    <div class="sidebar">
                        <ul>
                            <p>Core</p>
                            <li><a href="adminhome.php" onclick="return confirm('Are you sure you want to cancel?')"><i class="fas fa-home"></i>Dashboard</a></li>
                            <li class=""><a href="accounts_management.php" onclick="return confirm('Are you sure you want to cancel?')"><i class="fas fa-user"></i>Accounts & User Management</a></li>
                            <li class="hover-link"><a href="#"><i class="fa-solid fa-user-pen"></i> Update User </a></li>
                            <li class=""><a href="archived_accounts.php" onclick="return confirm('Are you sure you want to cancel?')"><i class="fa-solid fa-user-slash"></i> Archived Users</a></li>
                        </ul>
                    </div>
                </div>
                <header>
                    <nav>
                        <ul class='nav-bar'>
                            <li class='logo'><a href='adminhome.php'><img src='./images/SAMCIS Logo.png' alt='logo' /></a></li>
                            <span class="logo-title">
                                <li class="text-logo"><a><h1>SAINT LOUIS UNIVERSITY</h1></a></li>
                                <li class="text-logo2"><a>Online Thesis and Capstone Catalog</a></li>
                            </span>
                            <input type='checkbox' id='check'/>
                            <span class="menu">
                                <li><a href="my_profile.php?id=<?= $_SESSION['username'] ?>"><i class="fa-regular fa-circle-user"></i> <?php echo $_SESSION['username']; ?></a></li>
                                <li><a href="../logout.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a></li>
                                <label for="check" class="close-menu"><i class="fas fa-times"></i></label>
                            </span>
                            <label for="check" class="open-menu"><i class="fas fa-bars"></i></label>
                        </ul>
                    </nav>
                </header>
                <div class="container">
                    <?php
                    if (isset($_GET['userID'])) {
                        include 'crud_for_accounts/update.php';
                    }?>
                    <form action="crud_for_accounts/update.php" method="post">
                        <h4 class="display-4 text-center">Update</h4><hr><br>
                        <?php if (isset($_GET['error'])) { ?>
                            <div class="alert alert-danger" role="alert">
                                <?php echo $_GET['error']; ?>
                            </div>
                        <?php } ?>
                        <div class="form-group">
                            <label for="fname">First name</label>
                            <input type="text" class="form-control" id="fname" name="fname" value="<?= isset($row['fname']) ? $row['fname'] : '' ?>">
                        </div>
                        <div class="form-group">
                            <label for="lname">Last name</label>
                            <input type="text" class="form-control" id="lname" name="lname" value="<?= isset($row['lname']) ? $row['lname'] : '' ?>">
                        </div>
                        <div class="form-group">
                            <label for="type">User Type</label><br>
                            <input type="text" class="form-control" id="type" name="type" value="<?= isset($row['type']) ? $row['type'] : '' ?>" readonly>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?= isset($row['email']) ? $row['email'] : '' ?>">
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="password" name="password" value="<?= isset($row['']) ? $row[''] : '' ?>">
                        </div>
                        <div class="form-group">
                            <label for="address">Address</label>
                            <input type="text" class="form-control" id="address" name="address" value="<?= isset($row['address']) ? $row['address'] : '' ?>">
                        </div>
                        <div class="form-group">
                            <label for="contactNum">Contact Number</label>
                            <input type="text" class="form-control" id="contactNum" name="contactNum" value="<?= isset($row['contactNum']) ? $row['contactNum'] : '' ?>">
                        </div>
                        <input type="text" name="userID" value="<?=$row['userID']?>" hidden >

                        <button href="../accounts_management.php" class="btn btn-danger" onclick="return confirm('Are you sure you want to cancel update?')" name="cancel">Cancel</button></br></br>
                        <button type="submit" class="btn btn-primary" name="update">Update</button></br></br>
                    </form>
                </div><br><br><br>
                <?php
            }
        } elseif (isset($_GET['action']) && $_GET['action'] == 'view') {
            if (isset($_GET['id']) && !empty($_GET['id'])) {
                $id = $_GET['id'];
                include "../connection_db.php";
                include "crud_for_accounts/view.php";
                global $row;
                ?>
                <div class="wrapper">
                    <div class="sidebar">
                        <ul>
                            <p>Core</p>
                            <li><a href="adminhome.php"><i class="fas fa-home"></i>Dashboard</a></li>
                            <li class="hover-link"><a href="accounts_management.php"><i class="fas fa-user"></i>Accounts & User Management</a></li>
                            <li><a href="accounts_management.php?action=add-user"><i class="fa-solid fa-user-plus"></i> Add New User</a></li>
                            <li class=""><a href="archived_accounts.php"><i class="fa-solid fa-user-slash"></i> Archived Users</a></li>
                        </ul>
                    </div>
                </div>
                <header>
                    <nav>
                        <ul class='nav-bar'>
                            <li class='logo'><a href='adminhome.php'><img src='./images/SAMCIS Logo.png' alt='logo' /></a></li>
                            <span class="logo-title">
                                <li class="text-logo"><a><h1>SAINT LOUIS UNIVERSITY</h1></a></li>
                                <li class="text-logo2"><a>Online Thesis and Capstone Catalog</a></li>
                            </span>
                            <input type='checkbox' id='check'/>
                            <span class="menu">
                                <li><a href="my_profile.php?id=<?= $_SESSION['username'] ?>"><i class="fa-regular fa-circle-user"></i> <?php echo $_SESSION['username']; ?></a></li>
                                <li><a href="../logout.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a></li>
                                <label for="check" class="close-menu"><i class="fas fa-times"></i></label>
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
                    <a href="accounts_management.php" class="btn btn-view">Back to User List</a>
                </div>
                <?php
            }
        }  else {
        ?>
            <div class="wrapper">
                <div class="sidebar">
                    <ul>
                        <p>Core</p>
                        <li><a href="adminhome.php"><i class="fas fa-home"></i>Dashboard</a></li>
                        <li class="hover-link"><a href="accounts_management.php"><i class="fas fa-user"></i>Accounts & User Management</a></li>
                        <li><a href="accounts_management.php?action=add-user"><i class="fa-solid fa-user-plus"></i> Add New User</a></li>
                        <li class=""><a href="archived_accounts.php"><i class="fa-solid fa-user-slash"></i> Archived Users</a></li>
                    </ul>
                </div>
            </div>
            <header>
                <nav>
                    <ul class='nav-bar'>
                        <li class='logo'><a href='adminhome.php'><img src='./images/SAMCIS Logo.png' alt='logo' /></a></li>
                        <span class="logo-title">
                            <li class="text-logo"><a><h1>SAINT LOUIS UNIVERSITY</h1></a></li>
                            <li class="text-logo2"><a>Online Thesis and Capstone Catalog</a></li>
                    </span>
                        <input type='checkbox' id='check'/>
                        <span class="menu">
                            <li><a href="my_profile.php?id=<?= $_SESSION['username'] ?>"><i class="fa-regular fa-circle-user"></i> <?php echo $_SESSION['username']; ?></a></li>
                            <li><a href="../logout.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a></li>
                            <label for="check" class="close-menu"><i class="fas fa-times"></i></label>
                        </span>
                        <label for="check" class="open-menu"><i class="fas fa-bars"></i></label>
                    </ul>
                </nav>
            </header>
            <div class="container">
                <div class="box">
                    <div class="document-content">
                        <div class="link-right">
                            <a href="accounts_management.php?action=add-user" class="btn btn-primary"><i class="fa-solid fa-user-plus"></i> Add New User</a>
                        </div>
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
                        <?php if (mysqli_num_rows($result)) { ?>
                            <table class="table table-striped" id="table">
                                <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">ID No.</th>
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
                                        <td><?= $row['status']?></td>
                                        <td>
                                            <a href="accounts_management.php?action=view&id=<?= $row['userID'] ?>" class="btn btn-success"><i class="fa-solid fa-id-card"></i> View Profile</a>
                                            <a href="accounts_management.php?action=update-user&id=<?= $row['userID'] ?>" class="btn btn-info"><i class="fa-solid fa-user-pen"></i> Update Info</a>
                                            <a href="crud_for_accounts/delete.php?id=<?= $row['userID'] ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to deactivate this user?')"><i class="fa-solid fa-user-xmark"></i> Deactivate User</a>
                                        </td>
                                    </tr>
                                <?php } ?>
                                </tbody>
                            </table>
                        <?php } ?>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div><br><br><br>
    <footer>
        <div class="container">
            <p> &copy; <?php echo date("Y"); ?> SLU | IT Department | SYNTX | All Rights Reserved.</p>  
        </div>
    </footer>
    </div>
</body>
</html>