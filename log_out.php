<?php 
//session_start();
session_start();
if ($_SESSION["logged_in"] != true) {
    header("Location: ./index.php");
}
$_SESSION["logged_in"] == false;
$_SESSION["logged_in_user_name"] = ""; // the username of the user that is logged in
$_SESSION["logged_in_uploader_id"] = -5; // the uploader id of the logged in user
header("Location: ./index.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>logout</title>
</head>
<body>
    <h1>You have logged out</h1>
</body>
</html>