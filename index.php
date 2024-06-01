<?php
session_start();
$_SESSION["logged_in"] = false;
$_SESSION["logged_in_user_name"] = ""; // the username of the user that is logged in
$_SESSION["logged_in_uploader_id"] = -5; // the uploader id of the logged in user
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="./css/css_login.css">
</head>
<body>
    <div id="header">
        <h1>login</h1>
    </div>
    <div id="middle_content">
        <form id="login_form" action="./login_processing.php" method="post">
            <div class="user_input">
                <label for="username">username</label>
                <input type="text" name="username" id="username">
            </div>
            <div class="user_input">
                <label for="password">password</label>
                <input type="password" name="password" id="password">
            </div>
            <div id="submit_section">
                <input id="submit_button" type="submit" value="login">
            </div>
        </form>
        <a href="./register_user.php">register a new account</a>
    </div>
    <div id="footer"></div>
</body>
</html>