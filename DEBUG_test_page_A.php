<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DEBUG test page A</title>
</head>
<body>
    <h1>TEST PAGE A</h1>
    <?php
        $input_scroll_position = "current_scroll_position";
        $previous_scroll_position = $_POST[$input_scroll_position];
    ?>
    <?php
    echo "<h1>previous scroll postion is: $previous_scroll_position</h1>";
    ?>
    <h1><?php echo time()?></h1>
    <h2><?php echo rand(1,20) ?></h2>
    <video id="test_video" src="./videos/Let_the_Storm_Descend_Upon_You_Avantasia.webm" width="854" height="480" controls poster=""></video>
    <p>video length</p>
    <button onclick="auto_set_video_length()">get video length</button>
    <input type="text" id="test_input" name="test_input" value="0">
    <br>
    <button onclick="get_half()">get video length</button>
    <input type="text" name="half_way" id="half_way">
    <br>
    <button onclick="in_minutes()">get video length</button>
    <input type="text" name="minutes" id="minutes">
    <br>
    <button onclick="in_minutes()">get video length</button>
    <input type="text" name="minutes" id="minutes">
    <p>this is a text</p>
    <br>
    <p>this is a text</p>
    <br>
    <p>this is a text</p>
    <br>
    <p>this is a text</p>
    <br>
    <p>this is a text</p>
    <br>
    <p>this is a text</p>
    <br>
    <p>this is a text</p>
    <br>
    <p>this is a text</p>
    <br>
    <p>this is a text</p>
    <br>
    <p>this is a text</p>
    <br>
    <p>this is a text</p>
    <br>
    <p>this is a text</p>
    <br>
    <p>this is a text</p>
    <br>
    <?php
    $video_length = 86400;
    /*
    $video_length = round($video_length,0);
    $number_of_hours = $video_length / (60 * 60);
    $number_of_minutes = (isolate_decimal_from_float($number_of_hours) * 60);
    $number_of_seconds = (isolate_decimal_from_float($number_of_minutes) * 60);
    $number_of_hours = floor($number_of_hours);
    $number_of_minutes = floor($number_of_minutes);
    $number_of_seconds = floor($number_of_seconds);
    */
    $time_formated = format_seconds_as_HOUR_MINUTE_SECOND($video_length);
    echo "<br>$time_formated";
    //echo "$number_of_hours:$number_of_minutes:$number_of_seconds";
    ?>
    
        
    
    <?php
        $redirect_back = "./DEBUG_test_page.php"; // name of page to redirect back to
        echo '<form id="send_back_previous_position" action="'.$redirect_back.'" method="post">';
        $input_name_previous_position = "previous_scroll_position"; // the name of the input containing the previous page position
        echo '<input type="hidden" name="'.$input_name_previous_position.'" id="'.$input_name_previous_position.'" value="'.$previous_scroll_position.'">';
        $return_button_text = "return button text"; // the return button text
        echo '<input type="submit" value="'.$return_button_text.'">';
        echo '</form>';
    ?>
</body>
<script>
    function test_video_length () {
        let video_length = document.getElementById("test_video").duration;
        alert (video_length);
    }
    function auto_set_video_length() {
        let video_length = document.getElementById("test_video").duration;
        document.getElementById("test_input").value = video_length;
    }
    function get_half () {
        let video_length = document.getElementById("test_video").duration;
        video_length = video_length/2;
        document.getElementById("half_way").value = video_length;
    }
    function in_minutes () {
        let video_length = document.getElementById("test_video").duration;
        video_length = video_length/2;
        video_length  = video_length/60;
        document.getElementById("minutes").value = video_length;
    }
</script>
</html>
<?php
?>