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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Processing password change</title>
</head>
<body>
    
</body>
</html>
<?php
$old_password = $_POST['old_password'];
$new_password = $_POST['new_password'];
$new_password_confirmation = $_POST['new_password_confirmation'];

$new_password_is_blank = false;
// check if the new password is blank
if (!isset($new_password) || trim($new_password) == "") {
//if (empty($new_password)) {
    $new_password_is_blank = true;
}

$old_password = hash('sha256', $old_password);
$new_password = hash('sha256', $new_password);
$new_password_confirmation = hash('sha256', $new_password_confirmation);

$old_password_matches_existing_records = false;
$new_password_matches_confirmation = false;

// check if the old password matches the currently stored password
// ### retrieve records - two identifiers ### put this code block where the function is to be called ###
// query customization variables -- to define fixed parts of the query
$table_to_access = "user_Uploader_Info";
$identifying_column_1 = "uploader_Id";
$identifying_column_2 = "password";
// query customization variables -- for the variable / user input 
$identifying_record_1 = $_SESSION['logged_in_uploader_id'];
$identifying_record_2 = $old_password;
$identifying_record_1_data_type = "i";
$identifying_record_2_data_type = "s";
    // datatype_of_identifying_record: i -> int
    // datatype_of_identifying_record: d -> float
    // datatype_of_identifying_record: s -> string
    // datatype_of_identifying_record: b -> blob, sent in packets
$old_password_check_array = run_prepared_query_return_all_columns_based_on_two_identifying_records($identifying_record_1, $identifying_record_1_data_type, $identifying_column_1, $identifying_record_2, $identifying_record_2_data_type, $identifying_column_2, $table_to_access, $database_user, $database_user_password, $database_name, $sql_server_name); 
    // if there is exactly one record, the old password is a match
if (count($old_password_check_array) == 1) {
    $old_password_matches_existing_records = true;
}
// check if the new password and confirmation match
if ($new_password == $new_password_confirmation) {
    $new_password_matches_confirmation = true;
}
// if all checks are good, change the password in the database
if ($old_password_matches_existing_records == true && $new_password_matches_confirmation == true && $new_password_is_blank == false) {
    // ### update one record ### put this code block where the function is to be called ###
    // query customization variables -- to define fixed parts of the query
    $table_to_access = "user_Uploader_Info";
    $identifying_column = "uploader_Id";
    $column_to_update = "password";
    // query customization variables -- for the variable / user input 
    $identifying_record = $_SESSION['logged_in_uploader_id'];
    $new_updated_value = $new_password;
    $identifying_record_data_type = "i";
    $new_updated_value_data_type = "s";
        // record_datatype: i -> int
        // record_datatype: d -> float
        // record_datatype: s -> string
        // record_datatype: b -> blob, sent in packets
    run_prepared_query_update_a_column_based_on_one_identifying_record($column_to_update, $new_updated_value, $new_updated_value_data_type, $identifying_record, $identifying_record_data_type, $identifying_column, $table_to_access, $database_user, $database_user_password, $database_name, $sql_server_name);
}
// redirect back to the password change page
echo '<form id="if_auto_submit_final" action="./change_password.php" method="post">';
if ($old_password_matches_existing_records == false) {
    echo '<input type="hidden" name="error_old_password_does_not_match" id="error_old_password_does_not_match" value="password does not match the current password for this user account">';
}
if ($new_password_matches_confirmation == false) {
    echo '<input type="hidden" name="error_new_password_does_not_match_comfirmation" id="error_new_password_does_not_match_comfirmation" value="new password does not match confirmation">';
}
if ($new_password_is_blank == true) {
    // error_new_password_is_blank
    echo '<input type="hidden" name="error_new_password_is_blank" id="error_new_password_is_blank" value="new password cannot be blank">';
}
if ($old_password_matches_existing_records == true && $new_password_matches_confirmation == true && $new_password_is_blank == false) {
    echo '<input type="hidden" name="password_success_message" id="password_success_message" value="Your password has successfully been changed">';
}
echo '<script>document.getElementById("if_auto_submit_final").submit();</script>';
echo "</form>";
?>