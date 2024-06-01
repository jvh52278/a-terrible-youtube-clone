<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>test page</title>
</head>
<body>
    <?php
    // ### prepare access and customization variables ###
    // database access variables
    $database_user = "uv_standard_user"; // the database user -- user name
    $database_user_password = "Uvsqlhsybvo0987=-0"; // the password of the database user
    $database_name = "database_test_A"; // the name of the database that is being accessed
    $sql_server_name = "localhost"; // is usually localhost
    // query customization variables -- to define fixed parts of the query
    $table_to_access = "video_info";
    $identifying_column = "uploader_Id";
    // query customization variables -- for the dynamic / user input 
        // this is the ?
    $identifying_record = 0;
    $identifying_column_A = "file_Name_Full";
    $identifying_record_A = "blank";
    $identifying_record_data_type = "is";
        // identifying_record_datatype: i -> int
        // identifying_record_datatype: d -> float
        // identifying_record_datatype: s -> string
        // identifying_record_datatype: b -> blob, sent in packets
    // ### run the sql query ###
    $database_connection = new mysqli($sql_server_name, $database_user, $database_user_password, $database_name);
    // step 1A: prepare the query
        // create the query, but put a ? where a user / dynamic input would be
    $sql_query = "SELECT * FROM $table_to_access WHERE $identifying_column = ? AND $identifying_column_A = ?";
    $prepared_sql_query = $database_connection->prepare($sql_query);
    // step 1B: bind the user / dynamic input variables to fixed data types
        // bind_param('|input_1_datatype|input_2_datatype|....', $input_1, $input_2, .....)
        // NOTE: the inputs go in order that they would appear in the query, left to right, where the ? are
        // datatype: i -> int
        // datatype: d -> float
        // datatype: s -> string
        // datatype: b -> blob, sent in packets
    $prepared_sql_query->bind_param($identifying_record_data_type, $identifying_record, $identifying_record_A);
    // step 2A: execute the prepared query
    $prepared_sql_query->execute(); // NOTE: this does not return the result, if any exists
    // step 2B: retrieve the results of the query and put them into an array
    $query_results = $prepared_sql_query->get_result()->fetch_all(MYSQLI_ASSOC);
    //echo count($query_results);
    $display_this = $query_results[3]['video_Title'];
    echo $display_this;
    // close the prepared query
    $prepared_sql_query->close();
    ?>
</body>
</html>