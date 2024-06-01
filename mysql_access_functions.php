<?php
/*
put this on each php page:
include "mysql_access_functions.php";
*/
$database_user = "sql_user"; // the database user -- user name
$database_user_password = "sql_password"; // the password of the database user
$database_name = "database_name"; // the name of the database that is being accessed
$sql_server = "server_name";
$sql_server_name = $sql_server;

$server = $sql_server;
$user = $database_user;
$password = $database_user_password;
$database = $database_name;

// helper function -- run a prepared statement / query that retrieves all records based on one identifying record:
// ### put this at the top of the php file -- this does not change ###
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
function run_sql_query_return_all_results($sql_query, $sql_server_name, $database_user, $database_user_password, $database_to_be_accessed) {
    $con = new mysqli($sql_server_name,$database_user, $database_user_password, $database_to_be_accessed);
    $query_object = $con->query($sql_query); // run the sql query
    if ($query_object) { // if the query was successful -> the $res object exist
        $results = mysqli_fetch_all($query_object, MYSQLI_ASSOC);
        return $results;
    }
}
function run_sql_query_not_retriving_records($sql_query, $sql_server_name, $database_user, $database_user_password, $database_to_be_accessed) {
    $con = new mysqli($sql_server_name,$database_user, $database_user_password, $database_to_be_accessed);
    $query_object = $con->query($sql_query); // run the sql query
}
?>
