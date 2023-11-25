<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" type="image/png" href="img/SAMCIS Logo.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="stylesheets/header-and-footer2-stylesheet.css" />
    <link rel="stylesheet" href="stylesheets/index.css">
    <script src="./script.js" defer></script>
    <title>Singko Video Streaming Log In</title>

</head>
<body>


    <input type="checkbox" id="check">
    <label for="check" class="icons">
        <i class='bx bx-menu' id="menu-icon"></i>
        <i class='bx bx-x' id="close-icon"></i>
    </label>

   
</header>
<div class="grid-container">
        <div class="grid-item">
            <div class="text-container">
                <h1>Video Streaming</h1>
                <form action="login_users.php" method="post">
                <label for="username">Username:</label><br>
                <input type="text" id="username" name="username" required><br>
                <label for="password">Password:</label><br>
                <input type="password" id="password" name="password" required><br>

            </form>
                <div class="log-button-container">
                <a href="login_users.php">Login</a>
                </div>
            </div>
        </div>
    </div> 

   




<footer>
            <div id = "container-footer">
            <p> &copy; <?php echo date("Y"); ?> SLU | IT Department | SYNTX | All Rights Reserved.</p>
        </div>
    </footer>


</body>
</html>
