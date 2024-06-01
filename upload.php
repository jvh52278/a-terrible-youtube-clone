<?php
session_start();
if ($_SESSION["logged_in"] != true) {
    header("Location: ./index.php");
}
// set value for failed upload error
$failed_upload_error = "";
try {
    $failed_upload_error = $_POST['failed_upload_error'];
}
catch (Exception $e) {
    $failed_upload_error = "";
}
// debug error
$debug_error="";
try {
    $debug_error = $_POST['debug_error'];
}
catch (Exception $e) {
    $debug_error = "";
}
// set dummy values for potential errors
$video_upload_error = "";
//$video_thumbnail_error = "";
$video_title_error = "";
$video_description_error = "";
// set the previous input entries -- if redirected back
$last_input_video_title = $_POST['last_input_video_title'];
$last_input_video_description = $_POST['last_input_video_description'];
// get the video upload error if it exists
try {
    $video_upload_error = $_POST['video_upload_error'];
}
catch (Exception $e) {
    $video_upload_error = "";
}
// get the video title error if it exists
try {
    $video_title_error = $_POST['video_title_error'];
}
catch (Exception $e) {
    $video_title_error = "";
}
// get the video description error if it exists
try {
    $video_description_error = $_POST['video_description_error'];
}
catch (Exception $e) {
    $video_description_error = "";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload</title>
    <link rel="stylesheet" href="./css/css_upload_page.css">
</head>
<body>
    <div id="main_container">
        <div id="header">
            <h1>Upload a video</h1>
        </div>
        <div id="middle_content">
            <form action="upload_processing.php" method="POST" enctype="multipart/form-data">
                <div class="user_input">
                    <label for="video_upload">upload a video</label>
                    <input type="file" id="video_upload" name="video_upload">
                    <h2 id="upload_message"></h2>
                    <p class="input_error"><?php echo $video_upload_error ?></p>
                    <p class="input_error"><?php echo $failed_upload_error ?></p>
                    <p class="input_error"></p> <!--debug error goes here-->
                </div>
                <div class="user_input">
                    <label for="thumbnail_upload">upload a thumbnail image</label>
                    <input type="file" id="thumbnail_upload" name="thumbnail_upload">
                </div>
                <div class="user_input_A">
                    <label for="video_title">video title</label>
                    <textarea name="video_title" id="video_title" cols="60" rows="5"><?php echo $last_input_video_title?></textarea>
                    <p class="input_error"><?php echo $video_title_error ?></p> <!--input error goes here-->
                </div>
                <div class="user_input_A">
                    <label for="video_description">Description</label>
                    <textarea name="video_description" id="video_description" cols="60" rows="5"><?php echo $last_input_video_description ?></textarea>
                    <p class="input_error"><?php echo $video_description_error ?></p> <!--input error goes here-->
                </div>
                <input id="submit_button" type="submit" value="upload" onclick="display_upload_message()">
            </form>
            <a href="./main.php">return to home page</a>
            <a href="./account_management.php">manage account</a>
        </div>
        <div id="footer">
        </div>
    </div>
</body>
<script>
    function display_upload_message () {
        document.getElementById('upload_message').innerHTML = "file upload in progress";
        document.getElementById('upload_message').className = "upload_message";
    }
</script>
</html>