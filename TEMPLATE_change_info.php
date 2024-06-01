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
    <title>Change password</title>
    <link rel="stylesheet" href="./css/css_change_password.css">
</head>
<body>
    <div id="header">
        <h1>Change your password</h1>
    </div>
    <div id="form_container">
        <form action="" method="post" id="input_form">
            <div class="input_combo">
                <label for="test_input">test_input</label>
                <input type="text" name="test_input" id="test_input">
                <p class="input_error">test error</p> <!-- input error goes here-->
            </div>
            <input type="submit" value="submit_form" class="submit_button">
        </form>
    </div>
</body>
</html>