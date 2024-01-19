<!------ Author/s: Allan Avila and Jemma Niduaza ------>
<!------ HTML containing the PHP for the Admin (Deactivated Users) ------>
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

<?php
global $conn;
include "../connection_db.php";

$sql = "(
    SELECT
        'content_manager' AS type,
        c.userID,
        u.username,
        u.status,
        c.fname,
        c.lname,
        c.email
    FROM content_manager c
    INNER JOIN user u ON c.userID = u.userID 
    WHERE u.status = 'Inactive'
)
UNION ALL
(
    SELECT
        'Admin' AS type,
        a.userID,
        u.username,
        u.status,
        a.fname,
        a.lname,
        a.email
    FROM admin a
    INNER JOIN user u ON a.userID = u.userID 
    WHERE u.status = 'Inactive'
)
ORDER BY lname"; 

$result = mysqli_query($conn, $sql);

$data = array();

if (!$result) {
    echo "Error: " . mysqli_error($conn);
} else {
    $num_rows = mysqli_num_rows($result);

    if ($num_rows > 0) {
        
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
    } else {
        echo "No records found.";
    }
}
?>

</body>
</html>

