<?php
session_start();
if ($_SESSION["logged_in"] != true) {
    header("Location: ./index.php");
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$video_length = $_POST['video_length'];
try {
    // cast the video length into a double
    $video_length_record = (float)$video_length;
    $video_length = (float)$video_length;
    // round to two decimal places
    $video_length = round($video_length,4);
}
catch (Exception $e) {
    // if for some reason, this fails set it to 0
    $video_length = 0;
    //header("Location: ./DEBUG_unknown_error.php");
}

if ($_POST['redirect_back_to_upload'] == "yes") {
    echo '<form id="if_auto_submit_AF" action="./upload.php" method="post">';
    echo '<input type="text" name="not_a_video_error" id="not_a_video_error">';
    echo '<script>document.getElementById("if_auto_submit_AF").submit();</script>';
    echo "</form>";
}
$upload_id = $_POST['upload_id_send'];

//echo "the upload id is |$upload_id|<br>";
//echo "the video length is |$video_length|";
// update the video length from 0 (default at the first processing page) to the actual video length
// ##################
include "mysql_access_functions.php";

// ##################

// check if the video that was uploaded is 5 seconds hours or less
// if the video is less than 5 seconds, return error:
    // Cannot upload video:
    // The uploaded file must be an accepted video format. Use mp4 or webm for best results.
    // The uploaded video must be longer than 5 seconds
    // Cannot upload video:<br>The uploaded file must be an accepted video format. Use mp4 or webm for best results.<br>The uploaded video must be longer than 5 seconds
//>>
// delete the record if the video is less than 5 seconds
// delete the file that was uploaded in the uploaded file in the uploads folder

// save the new video length to the database
//
// ### update one record ### put this code block where the function is to be called ###
// query customization variables -- to define fixed parts of the query
$table_to_access = "video_info";
$identifying_column = "upload_Id";
$column_to_update = "video_Length";
// query customization variables -- for the variable / user input 
$identifying_record = $upload_id;
$new_updated_value = $video_length;
$identifying_record_data_type = "s";
$new_updated_value_data_type = "d";
    // record_datatype: i -> int
    // record_datatype: d -> float
    // record_datatype: s -> string
    // record_datatype: b -> blob, sent in packets
run_prepared_query_update_a_column_based_on_one_identifying_record($column_to_update, $new_updated_value, $new_updated_value_data_type, $identifying_record, $identifying_record_data_type, $identifying_column, $table_to_access, $database_user, $database_user_password, $database_name, $sql_server_name);
//

//
// ### retrieve records ### put this code block where the function is to be called ###
// query customization variables -- to define fixed parts of the query
$table_to_access = "video_info";
$identifying_column = "upload_Id";
// query customization variables -- for the variable / user input 
$identifying_record = $upload_id;
$identifying_record_data_type = "s";
    // datatype_of_identifying_record: i -> int
    // datatype_of_identifying_record: d -> float
    // datatype_of_identifying_record: s -> string
    // datatype_of_identifying_record: b -> blob, sent in packets
$test_array = run_prepared_query_return_all_columns_based_on_one_identifying_record($identifying_record, $identifying_record_data_type, $identifying_column, $table_to_access, $database_user, $database_user_password, $database_name, $sql_server_name);
$test_array_use_if_good = run_prepared_query_return_all_columns_based_on_one_identifying_record($identifying_record, $identifying_record_data_type, $identifying_column, $table_to_access, $database_user, $database_user_password, $database_name, $sql_server_name);
$file_paths_to_delete = run_prepared_query_return_all_columns_based_on_one_identifying_record($identifying_record, $identifying_record_data_type, $identifying_column, $table_to_access, $database_user, $database_user_password, $database_name, $sql_server_name);
//

$relative_path_to_thumbnail_recorded = $test_array_use_if_good[0]['path_To_Video_Thumbnail'];
$relative_path_to_video_recorded = $test_array_use_if_good[0]['file_Location'];


if ($test_array[0]['video_Length'] < 5) {
    // delete the record
        // sql query to delete the record in the database
    // ### delete records ### put this code block where the function is to be called ###
    // query customization variables -- to define fixed parts of the query
    $table_to_access = "video_info";
    $identifying_column = "upload_Id";
    // query customization variables -- for the variable / user input 
    $identifying_record = $upload_id;
    $identifying_record_data_type = "s";
        // record_datatype: i -> int
        // record_datatype: d -> float
        // record_datatype: s -> string
        // record_datatype: b -> blob, sent in packets
    run_prepared_query_delete_row_based_on_one_identifying_record($identifying_record, $identifying_record_data_type, $identifying_column, $table_to_access, $database_user, $database_user_password, $database_name, $sql_server_name);
    //
    // delete the uploaded files
        // do not delete the thumbnail file if it is the default placeholder thumbnail
    $document_root = $_SERVER['DOCUMENT_ROOT']; // the absolute path of the directory containing the website files
    $video_path = $file_paths_to_delete[0]['file_Location']; // the relative file path of the video
    $absolute_path_of_video = $document_root."/".$video_path;
    unlink($absolute_path_of_video);
        // delete the thumbnail if it is not the default one
    $default_thumbnail_relative_path = "images/default_thumbnail.png";
    $retrieved_thumbnail_relative_path = $file_paths_to_delete[0]['path_To_Video_Thumbnail'];
    if ($retrieved_thumbnail_relative_path != $default_thumbnail_relative_path) {
        $absolute_path_of_thumbnail = $document_root."/".$retrieved_thumbnail_relative_path;
        unlink($absolute_path_of_thumbnail);
    }
    //
    // redirect the user back to the upload page
    echo '<form id="if_auto_submit_final" action="./upload.php" method="post">';
    echo '<input type="hidden" name="failed_upload_error" id="failed_upload_error" value="Cannot upload video:<br>The uploaded file must be an accepted video format. Use mp4 or webm for best results.<br>The uploaded video must be longer than 5 seconds">';
    echo '<input type="hidden" name="debug_error" id="debug_error" value="'.$upload_id.'">';
    echo '<script>document.getElementById("if_auto_submit_final").submit();</script>';
    echo "</form>";
}


if ($test_array[0]['video_Length'] > 5 ) {
    // set the video thumbnail to NULL OR auto generate thumbnail if it is the default thumbnail
    // the auto thumbnail system -- using ffmpeg
    try {
        $default_thumbnail_relative_path = "images/default_thumbnail.png";
        if ($relative_path_to_thumbnail_recorded == $default_thumbnail_relative_path) {
            // get the halfway point of the video -- the length / 2
            $video_half_way_point = $test_array[0]['video_Length'] / 2;
            // put it in terms of minutes, hours and seconds
            $formated_time_duration = gmdate("H:i:s", $video_half_way_point);
            // run an ffmpeg command to get thumbnail, save thumbnail to the thumbnail directory
                // example: ffmpeg -i video_name.file_extention -ss |number_of_seconds| -vframes 1 name_of_output_image.png
            $root_directory_path = $_SERVER['DOCUMENT_ROOT'];
            $thumbnail_relative_path_auto_generated = "uploads/thumbnails/"."auto_thumbnail_".$upload_id.".png";
            $thumbnail_file_name_path = $root_directory_path."/".$thumbnail_relative_path_auto_generated;
            $video_file_name_path = $root_directory_path."/".$relative_path_to_video_recorded;
            $cut_off_point = $video_half_way_point;
            $cut_off_point = strval($cut_off_point);
            $command = "ffmpeg -i ".$video_file_name_path." -ss ".$cut_off_point." -vframes 1 ".$thumbnail_file_name_path;
            shell_exec($command);
            // update the database record
            // ### update one record ### put this code block where the function is to be called ###
            // query customization variables -- to define fixed parts of the query
            $table_to_access = "video_info";
            $identifying_column = "upload_Id";
            $column_to_update = "path_To_Video_Thumbnail";
            // query customization variables -- for the variable / user input 
            $identifying_record = $upload_id;
            $new_updated_value = $thumbnail_relative_path_auto_generated;
            $identifying_record_data_type = "s";
            $new_updated_value_data_type = "s";
                // record_datatype: i -> int
                // record_datatype: d -> float
                // record_datatype: s -> string
                // record_datatype: b -> blob, sent in packets
            run_prepared_query_update_a_column_based_on_one_identifying_record($column_to_update, $new_updated_value, $new_updated_value_data_type, $identifying_record, $identifying_record_data_type, $identifying_column, $table_to_access, $database_user, $database_user_password, $database_name, $sql_server_name);
            //
        }
    }
    catch(err) {
        echo "Something went wrong";
        $default_thumbnail_relative_path = "images/default_thumbnail.png";
        // ### update one record ### put this code block where the function is to be called ###
        // query customization variables -- to define fixed parts of the query
        $table_to_access = "video_info";
        $identifying_column = "upload_Id";
        $column_to_update = "path_To_Video_Thumbnail";
        // query customization variables -- for the variable / user input 
        $identifying_record = $upload_id;
        $new_updated_value = $default_thumbnail_relative_path;
        $identifying_record_data_type = "s";
        $new_updated_value_data_type = "s";
            // record_datatype: i -> int
            // record_datatype: d -> float
            // record_datatype: s -> string
            // record_datatype: b -> blob, sent in packets
        run_prepared_query_update_a_column_based_on_one_identifying_record($column_to_update, $new_updated_value, $new_updated_value_data_type, $identifying_record, $identifying_record_data_type, $identifying_column, $table_to_access, $database_user, $database_user_password, $database_name, $sql_server_name);
        //
    };

    //
    // redirect to confirmation page
    header("Location: ./upload_confirmation.php");
    //header("refresh:6;url=./upload_confirmation.php");
}
?>

