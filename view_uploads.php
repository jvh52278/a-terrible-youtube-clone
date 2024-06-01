<?php
session_start();
if ($_SESSION["logged_in"] != true) {
    header("Location: ./index.php");
}

// put this at the top of the PHP section
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/
// ### function block ###


// set the previous scroll postion to 0 as default
$previous_scroll_position = 0;
// get the previous scroll postion from the redirecting form
$sent_back_previous_position = "previous_page_position";
$previous_scroll_position = $_POST[$sent_back_previous_position];
$last_scroll_position = "previous_page_position_sent_back";
echo '<input type="hidden" name="'.$last_scroll_position.'" id="'.$last_scroll_position.'" value="'.$previous_scroll_position.'">';


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

include "mysql_access_functions.php";
$user_id = $_SESSION['logged_in_uploader_id'];
// ### retrieve records - one identifier ### put this code block where the function is to be called ###
// query customization variables -- to define fixed parts of the query
$table_to_access = "video_info";
$identifying_column = "uploader_Id";
// query customization variables -- for the variable / user input 
$identifying_record = $user_id;
$identifying_record_data_type = "i";
    // datatype_of_identifying_record: i -> int
    // datatype_of_identifying_record: d -> float
    // datatype_of_identifying_record: s -> string
    // datatype_of_identifying_record: b -> blob, sent in packets
$all_uploads = run_prepared_query_return_all_columns_based_on_one_identifying_record($identifying_record, $identifying_record_data_type, $identifying_column, $table_to_access, $database_user, $database_user_password, $database_name, $sql_server_name);

// sort by date -- decending order -- largest to smallest
$date_array = array();
foreach ($all_uploads as $upload) {
    array_push($date_array, $upload['upload_Date']);
}
rsort ($date_array);
// loop  through all results and arrange them by date
$display_array = array();
foreach ($date_array as $date) {
    $date_stamp = $date;
    foreach ($all_uploads as $upload) {
        if ($upload['upload_Date'] == $date_stamp) {
            // put the following in an array -- video_Title, upload_Date, path_To_Video_Thumbnail, video_Length, upload_Id
            $array_to_insert = array();
            $array_to_insert['video_Title'] = $upload['video_Title'];
            $array_to_insert['upload_Date'] = $upload['upload_Date'];
            $array_to_insert['path_To_Video_Thumbnail'] = $upload['path_To_Video_Thumbnail'];
            $array_to_insert['video_Length'] = $upload['video_Length'];
            $array_to_insert['upload_Id'] = $upload['upload_Id'];
            // insert that array into the display array
            array_push($display_array, $array_to_insert);
        }
    }
}
//
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>view uploads</title>
    <link rel="stylesheet" href="css/css_view_uploads.css">
</head>
<body>
    <div id="header">
        <h1>Your uploads</h1>
    </div>
    <div id="content_header">
        <div id="content_header_center_this">
            <a class="link_button" href="./account_management.php"><button>back</button></a>
            <a class="link_button" href="./main.php"><button>return to main page</button></a>
        </div>
    </div>
    <div style="background-color: darkgrey; padding: 10px;">
        <div id="all_videos"> <!--show all uploads here-->
        <?php
        if (count($display_array) <= 0) {
            echo "<h2>You have no uploads</h2>";
        }
        ?>
            <div style="display: flex;">
                <div style="flex:1;padding: 5px"><p style="font-weight: bold;">Video title</p></div>
                <div style="flex:1;padding: 5px"><p style="font-weight: bold;">upload date</p></div>
                <div style="flex:1;padding: 5px"><p style="font-weight: bold;">thumbnail image</p></div>
                <div style="flex:1;padding: 5px"><p style="font-weight: bold;">video length</p></div>
                <div style="flex:1;padding: 5px"><p style="font-weight: bold;">Options:</p></div>
            </div>
            <?php
            $count = 1;
            if (count($display_array) > 0) {
                foreach ($display_array as $video) {
                    $row_id = time() + $count;
                    $count = $count + 1;
                    $edit_form_id = "edit".$row_id;
                    $view_form_id = "view".$row_id;
                    $edit_scroll_id = "current_scroll_position_edit_AAA".$row_id;
                    $delete_scroll_id = "current_scroll_position".$row_id;
                    // video_Title, file_Description, , upload_Date, path_To_Video_Thumbnail, video_Length, view (link to video page), edit
                    $video_title = $video['video_Title'];
                    //$video_description = $video['file_Description'];
                    $upload_date = $video['upload_Date'];
                    $thumbnail = $video['path_To_Video_Thumbnail'];
                    $video_length = $video['video_Length']; // in seconds
                    // put the video length in terms of hours:minutes:seconds
                    $video_length = format_seconds_as_HOUR_MINUTE_SECOND($video_length);
                    $max_width = "calc(100% / 5);";
                    $upload_id = $video['upload_Id'];
                    $view_form = "<form id='".$view_form_id."' action='./vpage_template.php' method='get'><input type='hidden' name='video_id' id='video_id' value=$upload_id> </form>";
                    $edit_form = "<form id="."'".$edit_form_id."'"." "."' action='./edit_video.php' method='post'><input type='hidden' name='".$edit_scroll_id."' id='".$edit_scroll_id."' value='0'><input type='hidden' name='loop_record_id' id='loop_record_id' value='$edit_form_id'><input type='hidden' name='video_id' id='video_id' value='$upload_id'></form>";
                    $delete_form = "<form id=$row_id "."' action='./delete_proccessing_page.php' method='post'><input type='hidden' name='".$delete_scroll_id."' id='".$delete_scroll_id."' value='0'><input type='hidden' name='loop_record_id' id='loop_record_id' value='$row_id'><input type='hidden' name='video_id' id='video_id' value='$upload_id'></form>";
                    $delete_button = '<button type="button" class="form_submit_button" onclick="delete_and_send_scroll_position('.$row_id.','.$row_id.')">Delete</button>';
                    $view_button = "<button type='button' class='form_submit_button' onclick='view_video(\"$view_form_id\")'>View</button>";
                    //$edit_button = '<button type="button" class="form_submit_button" onclick="delete_and_send_scroll_position('.$edit_form_id.','.$row_id.')">Edit</button>';
                    $edit_button = "<button type='button' class='form_submit_button' onclick='delete_and_send_scroll_position(\"$edit_form_id\"".",".$row_id.")'>Edit</button>";
                    echo '<div style="display: flex;">';
                    echo "<div style='flex:1; max-width: $max_width; overflow-wrap: break-word; padding: 5px'><p>$video_title</p></div>";
                    echo "<div style='flex:1; max-width: $max_width; overflow-wrap: break-word;padding: 5px'><p>$upload_date</p></div>";
                    echo "<div style='flex:1; max-width: $max_width; padding: 5px'><img src='$thumbnail' alt='thumbnail image' style='width:100%'></div>";
                    echo "<div style='flex:1; max-width: $max_width; overflow-wrap: break-word;padding: 5px'><p>$video_length</p></div>";
                    echo "<div style='flex:1; max-width: $max_width; overflow-wrap: break-word;padding: 5px'>$view_form$edit_form$delete_form$view_button$delete_button$edit_button</div>";
                    //echo $view_button;
                    //echo $delete_button;
                    //echo $edit_button;
                    echo '</div>';
            }
        }
            ?>
        </div>
    </div>
    <script>
    function get_current_scroll_position (row_identifier) {
        console.log(`updating scroll position`);
        let input_identifier = row_identifier;
        // create aliases for the two possible html element identifyer of the webpage
        let body = document.body;
        let html = document.documentElement;
        // get the maximum value of the possible values for the maximum page height
        let max_height = Math.max( body.scrollHeight, body.offsetHeight, html.clientHeight, html.scrollHeight, html.offsetHeight );
        // get the current scroll position
            // use document.documentElement.scrollTop OR document.body.scrollTop
        let current_scroll_position = document.documentElement.scrollTop;
        if (current_scroll_position <= 0) {
            current_scroll_position = document.body.scrollTop;
        }
        // get the scroll postion as a percentage of the total screen height
        let current_scroll_postion_percent = current_scroll_position / max_height;
        // set the form input to the value of the current scroll postion
        console.log(`for the edit form:`);
        let form_input_containing_scroll_postion = "current_scroll_position_edit_AAA" + input_identifier;
        console.log(`changing value of input: ${form_input_containing_scroll_postion}`);
        document.getElementById(form_input_containing_scroll_postion).value = current_scroll_postion_percent;
        console.log(`value is now: ${document.getElementById(form_input_containing_scroll_postion).value}`);
        form_input_containing_scroll_postion = "current_scroll_position" + input_identifier;
        console.log(`for the delete form`);
        console.log(`changing value of input: ${form_input_containing_scroll_postion}`);
        //document.getElementById(form_input_containing_scroll_postion).value = current_scroll_postion_percent;
        // the input name/id is correct, the changes are being made on pressing the submit button
        //, so why are the changes not being relected when the form is submitted
        // try this: replace the submit button with normal button,
            // when the button is clicked, the scroll position is updated, then (seperatly) the form is submitted
        document.getElementById(form_input_containing_scroll_postion).value = current_scroll_postion_percent;
        console.log(`value is now: ${document.getElementById(form_input_containing_scroll_postion).value}`);
    }
    function submit_form_and_get_current_scroll_position (record_identifier) {
        console.log(`record identifier is: ${record_identifier}`);
        console.log(`the previous scroll position is: ${previous_page_position_sent_back}`);
        record_identifier = String(record_identifier);
        get_current_scroll_position(record_identifier);
    }
    function scroll_to_previous_position () {
        // get the maximum height
        let body = document.body;
        let html = document.documentElement;

        let max_page_height = Math.max( body.scrollHeight, body.offsetHeight, html.clientHeight, html.scrollHeight, html.offsetHeight );
        // from the percentage value of the scroll postion, get a pixel value
        let form_input_containing_previous_scroll_postion = "previous_page_position_sent_back";
        let previous_scroll_postion = document.getElementById(form_input_containing_previous_scroll_postion).value;
        let aproximate_pixel_position = parseFloat(previous_scroll_postion) * max_page_height;
        // scroll to the pixel location
        window.scrollTo(0,aproximate_pixel_position);
    }
    document.addEventListener("DOMContentLoaded", function() {
        scroll_to_previous_position();
    });
    function delete_and_send_scroll_position (form_id_to_submit,display_order_id) {
        console.log(`----form to submit: ${form_id_to_submit}----`);
        console.log(`row id is: ${display_order_id}`);
        // get the scroll position
        record_identifier = String(display_order_id);
        get_current_scroll_position(record_identifier);
        // submit the form
        let form_to_submit = String(form_id_to_submit);
        console.log(`submitting form: ${form_to_submit}`);
        document.getElementById(form_to_submit).submit();
    }
    function view_video (form_id_to_submit) {
        let view_form_to_submit = String(form_id_to_submit);
        document.getElementById(view_form_to_submit).submit();
    }
    </script>
</html>