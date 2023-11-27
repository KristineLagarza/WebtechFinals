<div class  = "login-container">
<h1>Admin Login</h1>
<?php if (isset($errormessage)){?>
    <p class ="error-message"><?php echo $errormessage; ?></p>
    <?PHP } ?>

<form method="post" action ="">
    <label for="username">Username</label>
    <input type ="text" id="username" name="username" required >
    <label for ="password" id="password" name="password" required>
    <button type ="submit">Login</button>
</form>
</div>