<?php include 'header_sidebar_footer/header_NoOptions.html';?>
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="\WebtechFinals\stylesheets\index.css">

    <title>Singko Video Streaming Log In</title>

</head>
<body>


   
<div class="grid-container">
        <div class="grid-item">
            <div class="text-container">
                <h1>Video Streaming</h1>

                <div class ="login_container">
                <form action="login_users.php" method="post">
                <label for="username">Username:</label><br>
                <input type="text" id="username" name="username" required><br>
                <label for="password">Password:</label><br>
                <input type="password" id="password" name="password" required><br>

                </form>
            </div>
                <div class="log-button-container">
                <a href="login_users.php">Login</a>
                </div>
            </div>
        </div>
    </div> 
<?php include 'header_sidebar_footer/footer.html';?>
   
</body>
</html>
