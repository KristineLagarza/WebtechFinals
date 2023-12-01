<?php
    session_start();
    include("connection_db.php");

    /* Validation */
    if (isset($_SESSION['username'])) {
        $type = $_SESSION['type'];
        if ($type == 'Student') {
            header("Location: ./admin/accounts_view.php");
            exit();
        } elseif ($type == 'ContentManager') {
            header("Location: ./contentmanager/contentmanager.php");
            exit();
        }
    }

    if (isset($_POST['submit'])) {
        $Username = $_POST['username'];
        $Password = $_POST['password'];

        // Use prepared statements to prevent SQL injection
        $sql = "SELECT * FROM user WHERE Username = ? AND Password = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $Username, $Password);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($result)) {
            $_SESSION['username'] = $row['Username'];
            $_SESSION['type'] = $row['type'];

            switch ($row['type']) {
                case 'Admin':
                    header("Location: ./admin/accounts_view.php");
                    exit();
                case 'ContentManager':
                    header("Location: ./contentmanager/contentmanager.php");
                    exit();
                default:
                    // Handle unknown user type
                    break;
            }
        } else {
            header("Location: index.php?id=$Username&error=Invalid Credentials");
        }
    }
?>


<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
    <?php include ('./header_sidebar_footer/header_NoOptions.html') ?>
        </header>
        <div class="notification">
            <?php if (isset($_GET['error'])) { ?>
                <div id="alert-danger" role="alert">
                    <?php echo $_GET['error']; ?>
                </div>
            <?php } ?>
        </div>

        <div class="containerlogin">
            <div class="logreg-box">
                <div class="form-box login">
                    <form class="login-form" action="login.php" method="POST">
                        <h2>Sign In</h2>

                        <div class="input-box">
                            <span class="icon"><i class='bx bxs-id-card'></i></span>
                            <input type="text" name="username" required>
                            <label>ID Number</label>
                        </div>

                        <div class="input-box">
                            <span class="icon"><i class='bx bxs-lock' ></i></span>
                            <input type="password" name="password" required>
                            <label>Password</label>
                        </div>

                        <input type="submit" class="btn" value="Sign In" name="submit">
                    </form>
                </div>

            </div>
        </div>
        <?php include ('./header_sidebar_footer/footer.html') ?>
    </body>
</html>

