<?php
session_start();
if ($_SESSION["logged_in"] != true) {
    header("Location: ./index.php");
}
include "mysql_access_functions.php";
/*
$database_user = "sqladmin"; // the database user -- user name
$database_user_password = "sqladmin"; // the password of the database user
$database_name = "database_test_A"; // the name of the database that is being accessed
$sql_server = "localhost";
$sql_server_name = $sql_server;

function run_prepared_query_return_all_columns_based_on_one_identifying_record($identifying_record, $identifying_record_data_type, $column_containing_identifying_record, $table_to_access, $database_user, $database_user_password, $database_to_access, $sql_server_name){
    $database_connection = new mysqli($sql_server_name, $database_user, $database_user_password, $database_to_access);
    // step 1A: prepare the query
    $sql_query = "SELECT * FROM $table_to_access WHERE $column_containing_identifying_record = ?";
    $prepared_sql_query = $database_connection->prepare($sql_query);
    // step 1B: bind the user / dynamic input variables to fixed data types
    $prepared_sql_query->bind_param($identifying_record_data_type, $identifying_record);
    // step 2A: execute the prepared query -- does not return output results, if any exist
    $prepared_sql_query->execute();
    // step 2B: retrieve the results of the query
    $query_results = $prepared_sql_query->get_result()->fetch_all(MYSQLI_ASSOC);
    // close the prepared query
    $prepared_sql_query->close();
    // return the output results
    return $query_results;
}

function run_prepared_query_return_all_columns_based_on_two_identifying_records($identifying_record_1, $identifying_record_1_data_type, $column_containing_identifying_record_1, $identifying_record_2, $identifying_record_2_data_type, $column_containing_identifying_record_2, $table_to_access, $database_user, $database_user_password, $database_to_access, $sql_server_name){
    $database_connection = new mysqli($sql_server_name, $database_user, $database_user_password, $database_to_access);
    // step 1A: prepare the query
    $sql_query = "SELECT * FROM $table_to_access WHERE $column_containing_identifying_record_1 = ? AND $column_containing_identifying_record_2 = ? ";
    $prepared_sql_query = $database_connection->prepare($sql_query);
    // step 1B: bind the user / dynamic input variables to fixed data types
    $combined_binding_key = $identifying_record_1_data_type.$identifying_record_2_data_type;
    $prepared_sql_query->bind_param($combined_binding_key, $identifying_record_1, $identifying_record_2);
    // step 2A: execute the prepared query -- does not return output results, if any exist
    $prepared_sql_query->execute();
    // step 2B: retrieve the results of the query
    $query_results = $prepared_sql_query->get_result()->fetch_all(MYSQLI_ASSOC);
    // close the prepared query
    $prepared_sql_query->close();
    // return the output results
    return $query_results;
}

function run_prepared_query_update_a_column_based_on_one_identifying_record($column_to_update, $new_updated_value, $new_updated_value_data_type, $identifying_record, $identifying_record_data_type, $column_containing_identifying_record, $table_to_access, $database_user, $database_user_password, $database_to_access, $sql_server_name){
    $database_connection = new mysqli($sql_server_name, $database_user, $database_user_password, $database_to_access);
    // step 1A: prepare the query
    $sql_query = "UPDATE $table_to_access SET $column_to_update = ? WHERE $column_containing_identifying_record = ? ;";
    $prepared_sql_query = $database_connection->prepare($sql_query);
    // step 1B: bind the user / dynamic input variables to fixed data types
    $combined_type_bind = $new_updated_value_data_type.$identifying_record_data_type;
    $prepared_sql_query->bind_param($combined_type_bind, $new_updated_value, $identifying_record);
    // step 2A: execute the prepared query -- does not return output results, if any exist
    $prepared_sql_query->execute();
    // step 2B: retrieve the results of the query
    //$query_results = $prepared_sql_query->get_result()->fetch_all(MYSQLI_ASSOC);
    // close the prepared query
    $prepared_sql_query->close();
    // return the output results
    //return $query_results;
}

function run_prepared_query_delete_row_based_on_one_identifying_record($identifying_record, $identifying_record_data_type, $column_containing_identifying_record, $table_to_access, $database_user, $database_user_password, $database_to_access, $sql_server_name){
    $database_connection = new mysqli($sql_server_name, $database_user, $database_user_password, $database_to_access);
    // step 1A: prepare the query
    $sql_query = "DELETE FROM $table_to_access WHERE $column_containing_identifying_record = ? ;";
    $prepared_sql_query = $database_connection->prepare($sql_query);
    // step 1B: bind the user / dynamic input variables to fixed data types
    $prepared_sql_query->bind_param($identifying_record_data_type, $identifying_record);
    // step 2A: execute the prepared query -- does not return output results, if any exist
    $prepared_sql_query->execute();
    // step 2B: retrieve the results of the query
    //$query_results = $prepared_sql_query->get_result()->fetch_all(MYSQLI_ASSOC);
    // close the prepared query
    $prepared_sql_query->close();
    // return the output results
    //return $query_results;
}
*/
// ### retrieve records - one identifier ### put this code block where the function is to be called ###
// query customization variables -- to define fixed parts of the query
$user_id = $_SESSION['logged_in_uploader_id'];
$table_to_access = "user_Uploader_Info";
$identifying_column = "uploader_Id";
// query customization variables -- for the variable / user input 
$identifying_record = $user_id;
$identifying_record_data_type = "i";
    // datatype_of_identifying_record: i -> int
    // datatype_of_identifying_record: d -> float
    // datatype_of_identifying_record: s -> string
    // datatype_of_identifying_record: b -> blob, sent in packets
$array_all_user_info_records = run_prepared_query_return_all_columns_based_on_one_identifying_record($identifying_record, $identifying_record_data_type, $identifying_column, $table_to_access, $database_user, $database_user_password, $database_name, $sql_server_name);
$username_alias = $array_all_user_info_records[0]['username_Alias'];

$error_incorrect_password = "";
try {
    $error_incorrect_password = $_POST['error_incorrect_password'];
}
catch (Exception $e) {
    $error_incorrect_password = "";
}
$error_username_alias_is_blank = "";
try {
    $error_username_alias_is_blank = $_POST['error_username_alias_is_blank'];
}
catch (Exception $e) {
    $error_username_alias_is_blank = "";
}
$error_username_alias_already_exists = "";
try {
    $error_username_alias_already_exists = $_POST['error_username_alias_already_exists'];
}
catch (Exception $e) {
    $error_username_alias_already_exists = "";
}
$success_message = "";
try {
    $success_message = $_POST['success_message'];
}
catch (Exception $e) {
    $success_message = "";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change username</title>
    <link rel="stylesheet" href="./css/css_change_username.css">
</head>
<body>
    <div id="header">
        <h1>Change your user name</h1>
    </div>
    <div id="form_container">
        <h2 style="text-align:center;color:darkgreen;"><?php echo $success_message ?></h2>
        <form action="./change_user_name_processing.php" method="post" id="input_form">
            <div class="input_combo">
                <label for="password">password</label>
                <input type="password" name="password" id="password">
                <p class="input_error"><?php echo $error_incorrect_password ?></p> <!-- input error goes here-->
            </div>
            <p style="text-align:center; font-weight:bold;">
                <?php
                    // if the username alias is not set
                    if (empty($username_alias)) {
                        echo "username alias is not set";
                    }
                    if (!empty($username_alias)) {
                        echo "current username alias: $username_alias";
                    }
                ?>
            </p>
            <div class="input_combo">
                <label for="username_alias">new username alias</label>
                <input type="text" name="username_alias" id="username_alias">
                <p class="input_error"><?php echo $error_username_alias_is_blank ?></p> <!-- input error goes here-->
                <p class="input_error"><?php echo $error_username_alias_already_exists ?></p>
                <p class="input_error"></p>
            </div>
            <input type="submit" value="change username" class="submit_button">
        </form>
        <button class="button_link"><a href="./main.php">Return to main page</a></button>
        <button class="button_link"><a href="./account_management.php">Back</a></button>
    </div>
</body>
</html>