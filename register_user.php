<?php
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/
$error_username = "if you see this there is an explainable error";
$error_password = "if you see this there is an explainable error";
$error_admin_password = "if you see this there is an explainable error";
$last_username = "if you see this there is an explainable error";
$last_password = "if you see this there is an explainable error";
try {
    $last_username = $_POST["old_value_username"];
    $last_password = $_POST["old_value_password"];
}
catch (Exception $e) {
    $last_username = "";
    $last_password = "";
}
/*
if ($_POST) {
    $error_username = $_POST["username_error"];
}
*/
try {
    $error_password = $_POST["password_error"];
}
catch (Exception $e) {
    $error_password = $last_username;
}
try {
    $error_username = $_POST["username_error"];
}
catch (Exception $e) {
    $error_username = $last_password;
}
try {
    $error_admin_password = $_POST["admin_password_error"];
}
catch (Exception $e) {
    $error_admin_password = "";
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>create new user</title>
    <link rel="stylesheet" href="./css/css_register_user.css">
</head>
<body>
    <div id="header">
        <h1>Create a new user</h1>
    </div>
    <div id="middle_content">
        <form id="register_form" action="./register_user_processing.php" method="post">
            <div class="user_input">
                <label for="username">username</label>
                <input type="text" name="username" id="username" value="<?php echo $last_username ?>">
                <p class="error"><?php echo $error_username ?></p>
            </div>
            <div class="user_input">
                <label for="password">password</label>
                <input type="password" name="password" id="password" value="<?php echo $last_password ?>">
                <p class="error"><?php echo $error_password ?></p>
            </div>
            <div class="user_input">
                <label for="admin_password">admin password - required for user creation</label>
                <input type="password" name="admin_password" id="admin_password">
                <p class="error"><?php echo $error_admin_password ?></p>
            </div>
            <div id="submit_section">
                <input id="submit_button" type="submit" value="create user">
            </div>
        </form>
        <a href="./index.php">return to login</a>
    </div>
    <div id="footer"></div>
</body>
</html>