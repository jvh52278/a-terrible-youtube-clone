<?php
session_start();
if ($_SESSION["logged_in"] != true) {
    header("Location: ./index.php");
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$video_file_path = $_POST['path_to_video'];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>upload processing</title>
    <link rel="stylesheet" href="css/css_upload_processing_2.css">
</head>
<body>
    <video id="test_video" width="854" height="480" controls hidden>
        <source src="<?php echo $video_file_path ?>"> 
        <source src="videos/placeholder_video/placeholder_video.webm"> 
    </video>
    <div id="title_container">
        <h1>Your upload is currently being processed</h1> 
    </div>   
    <form id="if_auto_submit_final" action="./upload_processing_3.php" method="post">
        <input type="hidden" name="video_length" id="video_length" value="0">
        <input type="hidden" name="redirect_back_to_upload" id="redirect_back_to_upload" value="no">
        <?php 
            $upload_id_send = $_POST['upload_id'];
            echo '<input type="hidden" name="upload_id_send" id="upload_id_send" value="'.$upload_id_send.'">';
        ?>
        <!--<script>document.getElementById("if_auto_submit_final").submit();</script>-->
    </form>
</body>
<script>
    // This works in the test page -- maybe something to do with the auto submit?
    try {
        let test_video = document.getElementById("test_video");
        test_video.onloadedmetadata = function() {
            let video_length = document.getElementById("test_video").duration;
            document.getElementById("video_length").value = video_length;
            document.getElementById("if_auto_submit_final").submit();
        };
    }
    catch(err) {
        document.getElementById("redirect_back_to_upload").value = "yes";
        document.getElementById("if_auto_submit_final").submit();
    };

</script>
</html>

