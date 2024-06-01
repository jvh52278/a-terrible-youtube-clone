<?php
    session_start();
    if ($_SESSION["logged_in"] != true) {
        header("Location: ./index.php");
    }
    include "mysql_access_functions.php";
    $video_id = $_POST['video_id'];

    $thumbnail_error = ""; // error if no thumbnail is selected
    $video_error = ""; // error if no video is selected
    // check if the new title and description are the same as the old values
        // if any one of them is different, update the database
    // if option to upload a new video is set to yes
        // check if the 
        // upload the new video
        // delete the old video
        // update the database entry for the video path
        // calculate new video length and update the video length
    // if option to upload a new video is set to no
        // do nothing
    // if the upload thumbnail option is set to "no"
        // do nothing with the thumbnail upload
    // if the upload thumbnail option is set to "yes"
    if ($_POST['new_thumbnail_yes_no'] == "yes") {
        // check if the thumbnail upload is empty
            if (empty($_FILES['new_thumbnail']["name"])) {
            // if it is empty, do not upload and return an error
                // set the error value
                $thumbnail_error = "You must upload a thumbnail";
            }
            // if it is not empty
                $upload_directory = "ploads/thumbnails/";
                // delete the old thumbnail
                // upload the new thumbnail
                $char_list = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
                // generate a random file 
                    // set the random name minus the file extention
                $target_name = time();
                for ($x = 0; $x < 10; $x = $x + 1) {
                    $random_char = $char_list[rand(0,strlen($char_list) - 1)];
                    $target_name = $target_name.$random_char;
                }
                $new_file_name = "MTF".$target_name;
                    // get the file extention
                    // assemble the full file name
                // upload the the new thumbnail
                // update the thumbnail path in the database
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>processing changes</title>
</head>
<body>
    <h1>processing changes</h1>
    <!--form to redirect back to edit page-->
    <form action="./edit_video.php" method="post" id="auto_form">
        <input type="hidden" name="video_id" id="video_id" value="<?php echo $video_id ?>">
        <input type="hidden" name="thumbnail_error" value="<?php echo $thumbnail_error ?>" value="<?php  echo $thumbnail_error?>">
        <?php 
            // redirect back to the edit page
            echo '<script>document.getElementById("auto_form").submit();</script>';
            //echo $_POST['new_thumbnail_yes_no'];
        ?>
    </form>
</body>
</html>