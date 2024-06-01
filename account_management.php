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
// check if the user is an admin user
    // check if the current user is on the list of admin users
// ### retrieve records - one identifier ### put this code block where the function is to be called ###
// query customization variables -- to define fixed parts of the query
$table_to_access = "admin_Passwords";
$identifying_column = "associated_Admin_User";
// query customization variables -- for the variable / user input 
$identifying_record = hash('sha256', $_SESSION['logged_in_user_name']); 
$identifying_record_data_type = "s";
    // datatype_of_identifying_record: i -> int
    // datatype_of_identifying_record: d -> float
    // datatype_of_identifying_record: s -> string
    // datatype_of_identifying_record: b -> blob, sent in packets
$query_results_array = run_prepared_query_return_all_columns_based_on_one_identifying_record($identifying_record, $identifying_record_data_type, $identifying_column, $table_to_access, $database_user, $database_user_password, $database_name, $sql_server_name);

$user_is_admin = false; // if this is true, the current logged in user has admin privileges
if (count($query_results_array) == 1) {
    $user_is_admin = true;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account managment</title>
    <link rel="stylesheet" href="./css/css_account_managment.css">
</head>
<body>
    <div id="content_container">
        <div id="header_section">
            <h1>Account managment page</h1>
        </div>
        <div id="flex_section">
            <div id="left_section">
                <h2 class="section_header">Account settings</h2>
                <div class="sub_content_container">
                    <a href="./change_password.php">Change password</a>
                    <a href="./change_user_name.php">Change username</a>
                    <a href="/log_out.php">logout</a>
                    <?php 
                        if ($user_is_admin == true) {
                            echo '<div class="admin_panel">';
                            echo '<a href="">edit/remove other user accounts</a>';
                            echo '<a href="">add user accounts</a>';
                            echo '<a href="./change_assigned_admin_password.php">change assigned admin password</a>';
                            echo '<a href="">edit user admin privileges</a>';
                            echo '</div>';
                        }
                    ?>
                </div>
            </div>
            <div id="right_section">
                <h2 class="section_header">Manage uploads</h2>
                <div class="sub_content_container">
                <a href="./view_uploads.php">view uploads</a>  
                <a href="./upload.php">upload a video</a>
                </div>
            </div>
        </div>
        <div id="bottom_section">
            <a href="./main.php"><button>Return to main menu</button></a>
        </div>
    </div>
    

</body>
</html>
