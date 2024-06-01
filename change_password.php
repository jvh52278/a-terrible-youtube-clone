<?php
session_start();
if ($_SESSION["logged_in"] != true) {
    header("Location: ./index.php");
}
$password_success_message = "";
$error_old_password_does_not_match = "";
$error_new_password_does_not_match_comfirmation = "";
$error_new_password_is_blank = "";

try {
    $error_old_password_does_not_match = $_POST['error_old_password_does_not_match'];
}
catch (Exception $e) {
    $error_old_password_does_not_match = "";
}
try {
    $error_new_password_does_not_match_comfirmation = $_POST['error_new_password_does_not_match_comfirmation'];
}
catch (Exception $e) {
    $error_new_password_does_not_match_comfirmation = "";
}
try {
    $password_success_message = $_POST['password_success_message'];
}
catch (Exception $e) {
    $password_success_message = "";
}
try {
    $error_new_password_is_blank = $_POST['error_new_password_is_blank'];
}
catch (Exception $e) {
    $error_new_password_is_blank = "";
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
        <h2 class="password_change_success_message"><?php echo $password_success_message ?></h2>
        <form action="./change_password_processing.php" method="post" id="input_form">
            <div class="input_combo">
                <label for="old_password">enter your old password</label>
                <input type="password" name="old_password" id="old_password">
                <p class="input_error"><?php echo $error_old_password_does_not_match ?></p> <!-- input error goes here-->
            </div>
            <div class="input_combo">
                <label for="new_password">enter your new password</label>
                <input type="password" name="new_password" id="new_password">
                <p class="input_error"><?php echo $error_new_password_is_blank ?></p> <!-- input error goes here-->
            </div>
            <div class="input_combo">
                <label for="new_password_confirmation">re-enter your new password to confirm</label>
                <input type="password" name="new_password_confirmation" id="new_password_confirmation">
                <p class="input_error"><?php echo $error_new_password_does_not_match_comfirmation ?></p> <!-- input error goes here-->
            </div>
            <input type="submit" value="change password" class="submit_button">
        </form>
        <button class="button_link"><a href="./main.php">Return to main page</a></button>
        <button class="button_link"><a href="./account_management.php">Back</a></button>
    </div>
</body>
</html>