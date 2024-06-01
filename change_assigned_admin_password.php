<?php
session_start();
if ($_SESSION["logged_in"] != true) {
    header("Location: ./index.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>change assigned admin password</title>
    <link rel="stylesheet" href="./css/css_change_assigned_admin_password.css">
</head>
<body>
    <div id="header_section">
        <h1>Change assigned admin password</h1>
    </div>
    <a id="placeholder_link" href="./account_management.php">placeholder back button</a>
</body>
</html>