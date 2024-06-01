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
    <title>processing username change</title>
</head>
<body>
    
</body>
</html>
<?php
//echo "processing username change";
$user_id = $_SESSION['logged_in_uploader_id'];
$password_provided = $_POST['password'];
$password_provided = hash('sha256', $password_provided);

$username_alias = $_POST['username_alias'];
$username_alias_not_hashed = $username_alias;
$username_alias = hash('sha256', $username_alias);

$password_is_correct = false;
$username_alias_is_blank = false;
$username_alias_already_exists = false;
$user_name_alias_is_the_same = false;
$all_checks_pass = false;
// check if the password is correct
// ### retrieve records - two identifiers ### put this code block where the function is to be called ###
// query customization variables -- to define fixed parts of the query
$table_to_access = "user_Uploader_Info";
$identifying_column_1 = "uploader_Id";
$identifying_column_2 = "password";
// query customization variables -- for the variable / user input 
$identifying_record_1 = $user_id;
$identifying_record_2 = $password_provided;
$identifying_record_1_data_type = "i";
$identifying_record_2_data_type = "s";
    // datatype_of_identifying_record: i -> int
    // datatype_of_identifying_record: d -> float
    // datatype_of_identifying_record: s -> string
    // datatype_of_identifying_record: b -> blob, sent in packets
$array_check_password = run_prepared_query_return_all_columns_based_on_two_identifying_records($identifying_record_1, $identifying_record_1_data_type, $identifying_column_1, $identifying_record_2, $identifying_record_2_data_type, $identifying_column_2, $table_to_access, $database_user, $database_user_password, $database_name, $sql_server_name);

if (count($array_check_password) == 1) {
    $password_is_correct = true;
}
// check if the username alias is blank
if (empty($username_alias_not_hashed)) {
    $username_alias_is_blank = true;
}
// check if the username alias already exists -- in the alias column and the username column
// ### retrieve records - one identifier ### put this code block where the function is to be called ###
// query customization variables -- to define fixed parts of the query
$table_to_access = "user_Uploader_Info";
$identifying_column = "username_Alias";
// query customization variables -- for the variable / user input 
$identifying_record = $username_alias_not_hashed;
$identifying_record_data_type = "s";
    // datatype_of_identifying_record: i -> int
    // datatype_of_identifying_record: d -> float
    // datatype_of_identifying_record: s -> string
    // datatype_of_identifying_record: b -> blob, sent in packets
$array_check_if_alias_exists_in_alias = run_prepared_query_return_all_columns_based_on_one_identifying_record($identifying_record, $identifying_record_data_type, $identifying_column, $table_to_access, $database_user, $database_user_password, $database_name, $sql_server_name);
$identifying_column = "uploader_user_name";
$array_check_if_alias_exists_in_username = run_prepared_query_return_all_columns_based_on_one_identifying_record($identifying_record, $identifying_record_data_type, $identifying_column, $table_to_access, $database_user, $database_user_password, $database_name, $sql_server_name);

// ### retrieve records - two identifiers ### put this code block where the function is to be called ###
// query customization variables -- to define fixed parts of the query
$table_to_access = "user_Uploader_Info";
$identifying_column_1 = "uploader_Id";
$identifying_column_2 = "username_Alias";
// query customization variables -- for the variable / user input 
$identifying_record_1 = $user_id;
$identifying_record_2 = $username_alias_not_hashed;
$identifying_record_1_data_type = "i";
$identifying_record_2_data_type = "s";
    // datatype_of_identifying_record: i -> int
    // datatype_of_identifying_record: d -> float
    // datatype_of_identifying_record: s -> string
    // datatype_of_identifying_record: b -> blob, sent in packets
$array_check_same = run_prepared_query_return_all_columns_based_on_two_identifying_records($identifying_record_1, $identifying_record_1_data_type, $identifying_column_1, $identifying_record_2, $identifying_record_2_data_type, $identifying_column_2, $table_to_access, $database_user, $database_user_password, $database_name, $sql_server_name);

if (count($array_check_if_alias_exists_in_alias) > 0 || count($array_check_if_alias_exists_in_username) > 0) {
    $username_alias_already_exists = true;
}
if ($username_alias_already_exists == true && count($array_check_same) == 1) {
    $user_name_alias_is_the_same = true;
}
// check if all checks pass
if ($password_is_correct == true && $username_alias_is_blank == false && $username_alias_already_exists == false) {
    $all_checks_pass = true;
    // update the record in the database
    // ### update one record ### put this code block where the function is to be called ###
    // query customization variables -- to define fixed parts of the query
    $table_to_access = "user_Uploader_Info";
    $identifying_column = "uploader_Id";
    $column_to_update = "username_Alias";
    // query customization variables -- for the variable / user input 
    $identifying_record = $user_id;
    $new_updated_value = $username_alias_not_hashed;
    $identifying_record_data_type = "i";
    $new_updated_value_data_type = "s";
        // record_datatype: i -> int
        // record_datatype: d -> float
        // record_datatype: s -> string
        // record_datatype: b -> blob, sent in packets
    run_prepared_query_update_a_column_based_on_one_identifying_record($column_to_update, $new_updated_value, $new_updated_value_data_type, $identifying_record, $identifying_record_data_type, $identifying_column, $table_to_access, $database_user, $database_user_password, $database_name, $sql_server_name);
}
// redirect back to username change page
echo '<form id="if_auto_submit_final" action="./change_user_name.php" method="post">';
if ($password_is_correct == false) {
    $input_type = "hidden";
    $name_of_input = "error_incorrect_password";
    $input_value = "password does not match current password for this user account";
    $input_string = '<input type="'.$input_type.'" name="'.$name_of_input.'" id="'.$name_of_input.'" value="'.$input_value.'">';
    echo $input_string;
}
if ($username_alias_is_blank == true) {
    $input_type = "hidden";
    $name_of_input = "error_username_alias_is_blank";
    $input_value = "username alias cannot be blank";
    $input_string = '<input type="'.$input_type.'" name="'.$name_of_input.'" id="'.$name_of_input.'" value="'.$input_value.'">';
    echo $input_string;
}
if ($username_alias_already_exists == true) {
    $input_type = "hidden";
    $name_of_input = "error_username_alias_already_exists";
    $input_value = "username alias already exists";
    if ($user_name_alias_is_the_same == true) {
        $input_value = "username alias is already $username_alias_not_hashed";
    }
    $input_string = '<input type="'.$input_type.'" name="'.$name_of_input.'" id="'.$name_of_input.'" value="'.$input_value.'">';
    echo $input_string;
}
if ($all_checks_pass == true) {
    $input_type = "hidden";
    $name_of_input = "success_message";
    $input_value = "Username alias succesfully changed";
    $input_string = '<input type="'.$input_type.'" name="'.$name_of_input.'" id="'.$name_of_input.'" value="'.$input_value.'">';
    echo $input_string;
}
echo '<script>document.getElementById("if_auto_submit_final").submit();</script>';
echo "</form>";
?>
all_checks_pass