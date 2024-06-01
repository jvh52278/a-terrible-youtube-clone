<?php
session_start();
if ($_SESSION["logged_in"] != true) {
    header("Location: ./index.php");
}
include "mysql_access_functions.php";
$user_id = $_SESSION['logged_in_uploader_id'];
$video_id = $_POST['video_id'];
// retrieve errors
$thumbnail_error = "";
try {
	$thumbnail_error = $_POST['thumbnail_error'];
    }
catch(err) {
	$thumbnail_error = "";
};
// declare helper functions
// ### function block ###
function isolate_decimal_from_float ($input_float_number) {
    $test_number = $input_float_number;
    while ($test_number > 1) {
        $test_number = $test_number - 1;
    }
    return $test_number;
}
function format_seconds_as_HOUR_MINUTE_SECOND ($input_number_of_seconds){
    $limit_24_hours = 86400;
    $return_string = "";
    // round the number of seconds to 0 decimal places
    $number_of_seconds = round($input_number_of_seconds,0);
    // if the number of seconds is less than 24 hours
    if ($number_of_seconds < $limit_24_hours) {
        $return_string = gmdate("H:i:s", $input_number_of_seconds);
    }
    // if the number of seconds equals 24 hours
    if ($number_of_seconds == $limit_24_hours) {
        $return_string = "24:00:00";
    }
    // if the number of seconds is greater than 24 hours
    if ($number_of_seconds > $limit_24_hours) {
        $hours = 0;
        $minutes = 0;
        $seconds = 0;
        $one_hour = (60*60);
        $hours = $number_of_seconds / $one_hour;
        $minutes = (isolate_decimal_from_float($hours) * 60);
        $seconds = (isolate_decimal_from_float($minutes) * 60);
        // final display -- floor each of the values
        $hours = floor($hours);
        $minutes = floor($minutes);
        $seconds = floor($seconds);
        $return_string = $hours.":".$minutes.":".$seconds;
    }
    return $return_string;
}
// ### function block ###
// declare helper functions
// retreive all records for this video id
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
$video_info_records = run_prepared_query_return_all_columns_based_on_one_identifying_record($identifying_record, $identifying_record_data_type, $identifying_column, $table_to_access, $database_user, $database_user_password, $database_name, $sql_server_name);

$video_thumbnail = $video_info_records[0]['path_To_Video_Thumbnail'];
$video_title = $video_info_records[0]['video_Title'];
$video_description = $video_info_records[0]['file_Description'];
$video_length = $video_info_records[0]['video_Length'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>edit video</title>
    <link rel="stylesheet" href="./css/css_edit_video.css">
</head>
<body>
    <div id="header_section">
        <h1>Edit video</h1>
    </div>
    <div id="content_container">
        <div class="content_section">
            <div id="content_section_part">
                <img src="<?php echo $video_thumbnail ?>" alt="video thumbnail" height="300px">
            </div>
            <form action="./edit_video_processing.php" class="content_section_part" enctype="multipart/form-data" method="post">
                <!--the video id-->
                <input type="hidden" name="video_id" id="video_id" value="<?php echo $video_id ?>">
                <!--show video title, description and video length-->
                <div class="input_combo">
                    <label for="new_title">Video title</label>
                    <input type="text" name="new_title" id="new_title" value="<?php echo $video_title ?>">
                </div>
                <div class="input_combo">
                    <label for="new_description">Video description</label>
                    <input type="text" name="new_description" id="new_description" value="<?php echo $video_description ?>">
                </div>
                <p class="non_input_display">Video length</p>
                <p class="non_input_display_B"><?php echo format_seconds_as_HOUR_MINUTE_SECOND($video_length) ?></p> <!--put this in terms of HH:MM:SS-->
                <!--option to upload a new video-->
                <div class="input_combo">
                    <label for="new_video">Upload new video</label>
                    <input type="file" name="new_video" id="new_video">
                </div>
                <!--option to upload a new thumbnail-->
                <!--yes no options -> if no, autogenerate thumbnail -> if yes, require a thumbnail upload-->
                <div id="select_thumbnail_option">
                    <div>
                        <label style="margin: 10px; width: 200px; display: inline-block;" for="new_video_yes_no">upload new video?</label>
                        <select style="margin: 10px;" name="new_video_yes_no" id="new_video_yes_no">
                            <option value="yes">yes</option>
                            <option value="no">no</option>
                        </select>
                    </div>
                    <div>
                        <label style="margin: 10px; width: 200px; display: inline-block;" for="new_thumbnail_yes_no">upload new thumbnail?</label>
                        <select style="margin: 10px" name="new_thumbnail_yes_no" id="new_thumbnail_yes_no">
                            <option value="yes">yes</option>
                            <option value="no">no</option>
                        </select>
                    </div>
                    <div class="input_combo">
                        <label for="new_thumbnail">New thumbnail</label>
                        <input type="file" name="new_thumbnail" id="new_thumbnail">
                        <p class="error_text"><?php echo $thumbnail_error ?></p> <!--thumbnail error-->
                    </div>
                </div>
                <input type="submit" value="submit changes">
            </form>
        </div>
    </div>
    <div id="footer_section">
        <div id="link_section">
            <!--to do: return the previous page scroll postion-->
            <div class="link_flex_section">
                <a class="link_button" href="./view_uploads.php">back</a>
            </div>
            <div class="link_flex_section">
                <a class="link_button" href="./main.php">return to main menu</a>
            </div>
        </div>
    </div>
</body>
</html>