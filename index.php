<?php
include 'header_sidebar_footer/header.html';
include 'header_sidebar_footer/sidebar.html';
include 'header_sidebar_footer/footer.html';
?>
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
   

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

   
</body>
</html>
