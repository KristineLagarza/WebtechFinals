<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="stylesheets/index.css" rel="stylesheet" id="">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</head>
<!------ Include the above in your HEAD tag ---------->

<body>
<div class="wrapper fadeInDown">
  <div id="formContent">
    <!-- Tabs Titles -->

    <!-- Icon -->
    <div class="fadeIn first">
      <img src="images/user.png" id="icon" alt="User Icon" />
    </div>

    <!-- Login Form -->
    <form method = "post">
      <input type="text" id="login" class="fadeIn second" name="login" placeholder="username">
      <input type="text" id="password" class="fadeIn third" name="password" placeholder="password">
      <input type="submit" class="fadeIn fourth" value="Log In">
    </form>

   
   
  </div>
</div>

</body>
</html>
<?php
session_start();

// Include the database connection
include_once 'db_connect.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $username = $_POST["username"];
    $password = $_POST["password"];

   
    $sql = "SELECT * FROM user WHERE Username = '$username' AND Password = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
 
      $user = $result->fetch_assoc();
      $_SESSION['username'] = $username;
      $_SESSION['UserType'] = $user['UserType'];

   
      if ($user['user_type'] == 'admin') {
          header("location: admin.php");
      } elseif ($user['user_type'] == 'content_manager') {
          header("location: contentmanager.php");
      } else {
          header("location: index.php");
      }

      exit();
  } else {

      header("location: index.php?error=1");
      exit();
  }
} else {

  header("location: index.php");
  exit();
}
?>