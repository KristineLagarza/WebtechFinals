<?php
    include ('../admin/db.php')
    include (../header_sidebar_footer/header.html)
    include (../header_sidebar_footer/sidebar.html)
    include (../header_sidebar_footer/footer.html)

?>
if ($_SERVER[REQUEST_METHOD] == 'POST'){
    $username = $_POST['Username'];
    $password = $_POST['Password'];
    $query = "SELECT * FROM user WHERE Username = ? AND Password = ? AND userType = 'Admin'";
    $stmt = $db->prepare($query);
    $stmt-> bind_param("ss" $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0 ){
        header('Location: admin_dashboard.php')
        exit;
    }
    $query = "SELECT * FROM users WHERE username = ? AND password = ? AND userType = 'ContentManager'";
    $stmt = $db->prepare($query);
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
       
        header('Location: content_manager_dashboard.php');
        exit;
    }

}
