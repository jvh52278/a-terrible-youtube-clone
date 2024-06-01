<?php
session_start();
$_SESSION["logged_in"] = false;
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/
$login_success = 0; //if this is 1, good password and username

$user_name_sent = $_POST["username"];
$user_name_sent_pre_hashed = $user_name_sent;
$password_sent = $_POST["password"];

$user_name_sent = hash('sha256', $user_name_sent);
$password_sent = hash('sha256', $password_sent);

include "mysql_access_functions.php";

// try to get the database record that contains the password and username

$sql_query = "SELECT * FROM user_Uploader_Info WHERE (uploader_user_name = '$user_name_sent') AND (password = '$password_sent')";
// prepared statement
$username_to_check = $user_name_sent;
$password_to_check = $password_sent;
    // create the database connection
$database_connection = new mysqli($sql_server, $database_user, $database_user_password, $database_name);
    // prepare the query
$sql_query_p = "SELECT * FROM user_Uploader_Info WHERE (uploader_user_name = ? ) AND (password = ? )";
$prepared_sql_query = $database_connection->prepare($sql_query_p);
$prepared_sql_query->bind_param( 'ss', $username_to_check, $password_to_check);
    // execute the prepared query
$prepared_sql_query->execute();
//
$test_record = $prepared_sql_query->get_result()->fetch_all(MYSQLI_ASSOC);
$prepared_sql_query->close();

// if there is exactly 1 record row retreived, the login is successful
if (count($test_record) == 1) {
    $login_success = 1;
}
if ($login_success == 1) { // redirect to main page
    header("Location: ./main.php");
    $_SESSION["logged_in"] = true;
    $_SESSION["logged_in_user_name"] = $user_name_sent_pre_hashed; // the username of the user that is logged in
    // get the uploader_id
    // ### retrieve records ### put this code block where the function is to be called ###
    // query customization variables -- to define fixed parts of the query
    $table_to_access = "user_Uploader_Info";
    $identifying_column = "uploader_user_name";
    // query customization variables -- for the variable / user input 
    $identifying_record = $user_name_sent;
    $identifying_record_data_type = "s";
        // datatype_of_identifying_record: i -> int
        // datatype_of_identifying_record: d -> float
        // datatype_of_identifying_record: s -> string
        // datatype_of_identifying_record: b -> blob, sent in packets
    $test_record_1  = run_prepared_query_return_all_columns_based_on_one_identifying_record($identifying_record, $identifying_record_data_type, $identifying_column, $table_to_access, $database_user, $database_user_password, $database_name, $sql_server_name);
    $_SESSION["logged_in_uploader_id"] = $test_record_1[0]['uploader_Id']; // the uploader id of the logged in user
}
if ($login_success == 0) { // redirect back to login
    header("Location: ./index.php");
    $_SESSION["logged_in"] = false;
}
?>