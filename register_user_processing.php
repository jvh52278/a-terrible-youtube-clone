<!DOCTYPE html>
<html>
<head>
</head>
<body>
<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$user_name_sent = $_POST["username"];
$password_sent = $_POST["password"];
$admin_password_send = $_POST["admin_password"];
$admin_password_send = hash('sha256', $admin_password_send);

// check if the username is blank
$is_username_blank = false;
if ($user_name_sent == "") {
    $is_username_blank = true;
}
// check if the password is blank
$is_password_blank = false;
if ($password_sent == "") {
    $is_password_blank = true;
}

// check if the username entered already exists
include "mysql_access_functions.php";

// check if the admin password is correct
$is_correct_admin_password = false;
    // retrieve the list of admin passwords from the database
$sql_query = "SELECT * FROM admin_Passwords";
$test_admin_passwords = run_sql_query_return_all_results($sql_query,$sql_server,$database_user,$database_user_password,$database_name);
if (count($test_admin_passwords) > 0) {
    foreach ($test_admin_passwords as $items) {
        if ($items["admin_password"] == $admin_password_send) {
            $is_correct_admin_password = true;
        }
    }
}


$user_name_sent_to_test = hash('sha256', $user_name_sent);

$sql_query = "SELECT * FROM user_Uploader_Info WHERE (uploader_user_name = '$user_name_sent_to_test')";
//$sql_query = "SELECT * FROM user_Uploader_Info WHERE uploader_user_name = '$user_name_sent'";

$test_record = run_sql_query_return_all_results($sql_query,$sql_server,$database_user,$database_user_password,$database_name);

// check if the user already exists -- if there is more than 1 record
$is_already_exist = false;
if (count($test_record) > 0 ) {
    $is_already_exist = true;
}

$good_new_user = 0; // if this is 1, the username and password entered can be entered

// if there there 0 record matches for the user name, create the the new username
if (count($test_record) == 0 && $is_username_blank == false && $is_password_blank == false && $is_correct_admin_password == true) {
    $good_new_user = 1;
}

if ($good_new_user == 1) {
    // hash the username and password
    $password_sent = hash('sha256', $password_sent);
    $user_name_sent = hash('sha256', $user_name_sent);
    // get the last uploader ID from the database
    $sql_query = "SELECT uploader_Id FROM user_Uploader_Info";
    $test_id_list = run_sql_query_return_all_results($sql_query,$sql_server,$database_user,$database_user_password,$database_name);
    $number_of_records = count($test_id_list);
    //echo "|$number_of_records| records have been retrieved";
    // if no records were retrieved -- insert record with the id of 1
    if ($number_of_records == 0) {
        $id_to_insert = 1;
        $sql_query = "INSERT INTO user_Uploader_Info (uploader_Id, uploader_user_name, password) VALUES ($id_to_insert, '$user_name_sent', '$password_sent')";
        
        run_sql_query_not_retriving_records($sql_query,$sql_server,$database_user,$database_user_password,$database_name);
    }
    
    // if at least 1 record was retrieved
    if ($number_of_records > 0) {
        $last_recorded_id = $test_id_list[count($test_id_list) - 1]["uploader_Id"];
        $next_id_to_record = $last_recorded_id + 1;
        $sql_query = "INSERT INTO user_Uploader_Info (uploader_Id, uploader_user_name, password) VALUES ($next_id_to_record, '$user_name_sent', '$password_sent')";
        run_sql_query_not_retriving_records($sql_query,$sql_server,$database_user,$database_user_password,$database_name);
    }

    //header("refresh:5;url=./register_is_good.php");
    header("Location: ./register_is_good.php"); // redirect to success page
}
?> 
<form id="if_auto_submit" action="./register_user.php" method="post">
    <input type="text" name="old_value_username" id="old_value_username" value="<?php echo $user_name_sent ?>">
    <input type="text" name="old_value_password" id="old_value_password" value="<?php echo $password_sent ?>">
    <?php
    if ($is_password_blank == true) {
        echo '<input type="text" name="password_error" id="password_error" value="password cannot be blank">';
    }
    if ($is_already_exist == true) {
        echo '<input type="text" name="username_error" id="username_error" value="user name already exists">';
    }
    if ($is_username_blank == true) {
        echo '<input type="text" name="username_error" id="username_error" value="user name cannot be blank">';
        
    }
    if ($is_correct_admin_password == false) {
        echo '<input type="text" name="admin_password_error" id="admin_password_error" value="incorrect admin password">';
    }
    if ($good_new_user != 1) {
        echo '<script>document.getElementById("if_auto_submit").submit();</script>';
    }
    ?>
</form>


</body>
</html>