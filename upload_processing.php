<?php
/*
IMPORTANT TO DO
check if the video upload is a video format
check if the thumbnail is an image format
*/
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
echo "<h1>Upload processing</h1>";

session_start();
if ($_SESSION["logged_in"] != true) {
    header("Location: ./index.php");
}

//echo $_FILES['video_upload']['name'];
//echo "<br>";

$video_title = $_POST['video_title'];
$video_description = $_POST['video_description'];

//echo "video title |$video_title|<br>";
//echo "video description |$video_description|<br>";
$user = $_SESSION['logged_in_user_name'];
//echo "uploaded by: |$user|<br>";
$user_id = $_SESSION['logged_in_uploader_id'];
//echo "with the user id of: |$user_id|<br>";

// ##################
include "mysql_access_functions.php";
/*
$database_user = "sqladmin"; // the database user -- user name
$database_user_password = "sqladmin"; // the password of the database user
$database_name = "database_test_A"; // the name of the database that is being accessed
$sql_server = "localhost";
$sql_server_name = $sql_server;

function run_sql_query_return_all_results($sql_query, $sql_server_name, $database_user, $database_user_password, $database_to_be_accessed) {
    $con = new mysqli($sql_server_name,$database_user, $database_user_password, $database_to_be_accessed);
    $query_object = $con->query($sql_query); // run the sql query
    if ($query_object) { // if the query was successful -> the $res object exist
        $results = mysqli_fetch_all($query_object, MYSQLI_ASSOC);
        return $results;
    }
}
function run_sql_query_return_all_results_multi_query($sql_query, $sql_server_name, $database_user, $database_user_password, $database_to_be_accessed) {
    $con = new mysqli($sql_server_name,$database_user, $database_user_password, $database_to_be_accessed);
    $query_object = $con->multi_query($sql_query); // run the sql query
    if ($query_object) { // if the query was successful -> the $res object exist
        $results = mysqli_fetch_all($query_object, MYSQLI_ASSOC);
        return $results;
    }
}
function run_sql_query_not_retriving_records($sql_query, $sql_server_name, $database_user, $database_user_password, $database_to_be_accessed) {
    $con = new mysqli($sql_server_name,$database_user, $database_user_password, $database_to_be_accessed);
    $query_object = $con->query($sql_query); // run the sql query
}
*/
// ##################

// the actual file upload part
// check that all input fields have are not empty
$video_upload_is_empty = false;
$video_upload_is_not_mp4 = false;
$thumbnail_upload_is_empty = false;
$video_title_is_blank = false;
$video_description_is_blank = false;
$redirect_back_to_upload_page = false;

$last_input_video_title = "";
$last_input_video_description = "";
// check that the video is the correct format
$acceptable_video_formats = array("mp4", "webm");

// check that the video upload field is not blank -- that something has been uploaded
if (!$_FILES['video_upload']["name"]) {
    $video_upload_is_empty = true;
}
// if no thumbnail has been uploaded, set the thumbnail to ""
if (!$_FILES['thumbnail_upload']["name"]) {
    $thumbnail_upload_is_empty = true;
}
// if a thumbnail has been uploaded, check that it is the correct format
$acceptable_thumbnail_formats = array("png");

// check that the video title field is not blank
if ($_POST['video_title'] == "") {
    $video_title_is_blank = true;
}
// check that the video description field is not blank
if ($_POST['video_description'] == "") {
    $video_description_is_blank = true;
}
if ($video_upload_is_empty == true || $video_title_is_blank == true || $video_description_is_blank == true) {
    $redirect_back_to_upload_page = true;
    // get the last user input for the video title
    try {
        $last_input_video_title = $_POST['video_title'];
    }
    catch (Exception $e) {
        $last_input_video_title = "";
    }
    // get the last user input for the video description
    try {
        $last_input_video_description = $_POST['video_description'];
    }
    catch (Exception $e) {
        $last_input_video_description = "";
    }
}
// redirect back to the upload page if any inputs are missing
?>
<form id="if_auto_submit" action="./upload.php" method="post">
    <?php
    if ($redirect_back_to_upload_page == true) {
        echo '<input type="text" name="last_input_video_title" id="last_input_video_title" value="'.$last_input_video_title.'">';
        echo '<input type="text" name="last_input_video_description" id="last_input_video_description" value="'.$last_input_video_description.'">';
        if ($video_upload_is_empty == true) {
            echo '<input type="text" name="video_upload_error" id="video_upload_error" value="you must upload a video">';
        }
        if ($video_title_is_blank == true) {
            echo '<input type="text" name="video_title_error" id="video_title_error" value="the video title cannot be blank">';
        }
        if ($video_description_is_blank == true) {
            echo '<input type="text" name="video_description_error" id="video_description_error" value="the video description cannot be blank">';
        }
        echo '<script>document.getElementById("if_auto_submit").submit();</script>';
    }
    ?>
</form>


<?php
if ($redirect_back_to_upload_page == false) {
    $full_video_path = "";
    $full_thumbnail_path = "images/default_thumbnail.png"; // set to a default thumbnail image if none is selected
    // upload the files
    $target_directory_video = "uploads/videos/"; // where the video file will be stored
    $target_directory_thumbnail = "uploads/thumbnails/"; // where the thumbnail image file will be stored
        // upload the video
    $char_list = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
        // generate a random file name
    $target_name = time();
    for ($x = 0; $x < 10; $x = $x + 1) {
        $random_char = $char_list[rand(0,strlen($char_list) - 1)];
        $target_name = $target_name.$random_char;
    }
    $file_extention = pathinfo($_FILES['video_upload']["name"],PATHINFO_EXTENSION); // get the file extention of uploaded file
    $target_name = $target_name.".".$file_extention;
    $full_target_path = $target_directory_video.$target_name;
    $full_video_path = $full_target_path;
    move_uploaded_file($_FILES["video_upload"]["tmp_name"],$full_target_path);
        // upload the thumbnail, if it exists
    if ($thumbnail_upload_is_empty == false) {
        $target_name = "t".time();
        for ($x = 0; $x < 10; $x = $x + 1) {
            $random_char = $char_list[rand(0,strlen($char_list) - 1)];
            $target_name = $target_name.$random_char;
        }
        $file_extention = pathinfo($_FILES['thumbnail_upload']["name"],PATHINFO_EXTENSION); // get the file extention of uploaded file
        $target_name = $target_name.".".$file_extention;
        $full_target_path = $target_directory_thumbnail.$target_name;
        $full_thumbnail_path = $full_target_path;
        move_uploaded_file($_FILES["thumbnail_upload"]["tmp_name"],$full_target_path);
    }
    // save the initial records to the database
    $upload_id = "placeholder";
        // get the number of records in the video_info database
    $upload_id_test_query = "SELECT upload_Id FROM video_info;";
    $existing_videos = run_sql_query_return_all_results($upload_id_test_query, $sql_server, $database_user, $database_user_password, $database_name);
    // function to generate upload_id
    function generate_unique_upload_id ($uploader_id_input) {
        $uploader_id_to_use = strval($uploader_id_input);
        $random_chars_to_use = ""; // a 12 character string of random characters
        $upload_date = strval(time());
        // get the 10 random chars
        for ($x = 0; $x <= 12; $x = $x + 1) {
            $random_char_list = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
            $random_chars_to_use = $random_chars_to_use.$random_char_list[rand(0,strlen($random_char_list) - 1)];
        }
        $final_uploader_id_full = "U".$uploader_id_to_use.$random_chars_to_use.$upload_date."E";
        // final check to remove all non printable characters
        return $final_uploader_id_full;
    }
    //
        // If there are no records
    if (count($existing_videos) == 0) {
        echo "no existing videos";
        $upload_id = generate_unique_upload_id($_SESSION['logged_in_uploader_id']);
    }
        // if records exist
    if (count($existing_videos) > 0) {
        echo "videos exist";
        echo "<br>";
        $upload_id = generate_unique_upload_id($_SESSION['logged_in_uploader_id']);
        echo "the next upload id is |$upload_id|";
        }
    //    
    $uploader_id = $_SESSION['logged_in_uploader_id'];// for uploader_Id
    // placeholder for upload_Date -- use NOW() as the sql parameter
    $file_location_to_record = $full_video_path; // for file_Location
    $file_name_full = "blank";// placeholder for file_Name_Full -- this field is not currently being used
    $video_title = $_POST['video_title']; // for video_Title
    $video_description = $_POST['video_description']; // for file_Description
    $video_page = "blank"; // placeholder for path_To_Video_Page -- the field is not used currently
    $thumbnail_path_to_record = $full_thumbnail_path; // path_To_Video_Thumbnail
    // run sql query to save records
    // prepared statement
        // create the database connection
    $database_connection = new mysqli($sql_server_name, $database_user, $database_user_password, $database_name);
        // prepare the sql query
    $save_query = "INSERT INTO video_info (upload_Id,uploader_Id, upload_Date,file_Location,file_Name_Full,video_Title,file_Description,path_To_Video_Page,path_To_Video_Thumbnail,video_Length) VALUES ( ? , ? ,NOW(), ? , ? , ? , ? , ? ,? , ? );";
    $prepared_sql_query = $database_connection->prepare($save_query);
    $upload_id_to_record = $upload_id;
    $uploader_id_to_record = $uploader_id;
    $file_path_location_to_record = $file_location_to_record;
    $file_name_full_to_record = $file_name_full;
    $video_title_to_record = $video_title;
    $video_description_to_record = $video_description;
    $video_page_to_record = $video_page;
    $path_to_thumbnail_to_record = $thumbnail_path_to_record;
    $default_video_length = 0;
        // bind the input values
    $prepared_sql_query->bind_param('sissssssd', $upload_id_to_record, $uploader_id_to_record, $file_path_location_to_record, $file_name_full_to_record, $video_title_to_record, $video_description_to_record, $video_page_to_record, $path_to_thumbnail_to_record, $default_video_length);
        // execute the query
    $prepared_sql_query->execute();
    $prepared_sql_query->close();
    //
    echo '<form id="if_auto_submit_final" action="./upload_processing_2.php" method="post">';
    // DEBUG_test_page.php
    //echo '<form id="if_auto_submit_final" action="./DEBUG_test_page.php" method="post">';
    echo '<input type="text" name="path_to_video" id="path_to_video" value="'.$full_video_path.'">';
    echo '<input type="text" name="upload_id" id="upload_id" value="'.$upload_id.'">';
    echo '<script>document.getElementById("if_auto_submit_final").submit();</script>';
    echo "</form>";
}
?>

    
    
    
        
    
    

