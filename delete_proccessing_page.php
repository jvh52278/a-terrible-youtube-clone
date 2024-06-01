<?php
session_start();
if ($_SESSION["logged_in"] != true) {
    header("Location: ./index.php");
}
include "./mysql_access_functions.php";
$user_id = $_SESSION['logged_in_uploader_id'];
$video_id = $_POST['video_id'];


$input_scroll_position = "current_scroll_position";
$record_id = $_POST['loop_record_id'];
$input_to_fetch = $input_scroll_position.$record_id;
$input_to_fetch = strval($input_to_fetch);
$previous_scroll_position = $_POST["$input_to_fetch"];

/*
echo "$input_to_fetch";
echo "<br>";
echo "|$previous_scroll_position|";
echo "<br>";
print_r($_POST);
echo "<br>";
*/

// ### retrieve records - one identifier ### put this code block where the function is to be called ###
// query customization variables -- to define fixed parts of the query
$table_to_access = "video_info";
$identifying_column = "upload_Id";
// query customization variables -- for the variable / user input 
$identifying_record = $video_id;
$identifying_record_data_type = "s";
    // datatype_of_identifying_record: i -> int
    // datatype_of_identifying_record: d -> float
    // datatype_of_identifying_record: s -> string
    // datatype_of_identifying_record: b -> blob, sent in packets
$video_records = run_prepared_query_return_all_columns_based_on_one_identifying_record($identifying_record, $identifying_record_data_type, $identifying_column, $table_to_access, $database_user, $database_user_password, $database_name, $sql_server_name);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>deleting video</title>
</head>
<body>
</body>
</html>
<?php
// UNTESTED -- wait until you can sort by date

// ### delete records ### put this code block where the function is to be called ###
// query customization variables -- to define fixed parts of the query
$table_to_access = "video_info";
$identifying_column = "upload_Id";
// query customization variables -- for the variable / user input 
$identifying_record = $video_id;
$identifying_record_data_type = "s";
    // record_datatype: i -> int
    // record_datatype: d -> float
    // record_datatype: s -> string
    // record_datatype: b -> blob, sent in packets
run_prepared_query_delete_row_based_on_one_identifying_record($identifying_record, $identifying_record_data_type, $identifying_column, $table_to_access, $database_user, $database_user_password, $database_name, $sql_server_name);

// delete the video and thumbnail
$document_root = $_SERVER['DOCUMENT_ROOT'];
$video_path = $video_records[0]['file_Location'];
$thumbnail_path = $video_records[0]['path_To_Video_Thumbnail'];
$full_video_path = $document_root."/".$video_path;
$full_thumbnail_path = $document_root."/".$thumbnail_path;
unlink($full_video_path);
unlink($full_thumbnail_path);
echo $full_video_path."<br>".$full_thumbnail_path;
// redirect back to the view video page
//header("Location: ./view_uploads.php");

//header("refresh:20;url=./view_uploads.php");

$redirect_back = "./view_uploads.php"; // name of page to redirect back to
echo '<form id="send_back_previous_position" action="'.$redirect_back.'" method="post">';
$input_name_previous_position = "previous_page_position"; // the name of the input containing the previous page position
echo '<input type="hidden" name="'.$input_name_previous_position.'" id="'.$input_name_previous_position.'" value="'.$previous_scroll_position.'">';
echo '<script>document.getElementById("send_back_previous_position").submit();</script>';
echo '</form>';

?>