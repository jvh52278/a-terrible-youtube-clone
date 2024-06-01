
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
if ($_SESSION["logged_in"] != true) {
    header("Location: ../index.php");
}

$video_id = -5;
try {
    $video_id = $_GET['video_id'];
    if (is_null($video_id)) {
        header("Location: ./main.php");
    }
}
catch (Exception $e) {
    header("Location: ./main.php");
}
include "./mysql_access_functions.php";

$database_record_id = $video_id; // change this for each page

// ### retrieve records ### put this code block where the function is to be called ###
// query customization variables -- to define fixed parts of the query
$table_to_access = "video_info";
$identifying_column = "upload_Id";
// query customization variables -- for the variable / user input 
$identifying_record = $database_record_id;
$identifying_record_data_type = "s";
    // datatype_of_identifying_record: i -> int
    // datatype_of_identifying_record: d -> float
    // datatype_of_identifying_record: s -> string
    // datatype_of_identifying_record: b -> blob, sent in packets
$query_results = run_prepared_query_return_all_columns_based_on_one_identifying_record($identifying_record, $identifying_record_data_type, $identifying_column, $table_to_access, $database_user, $database_user_password, $database_name, $sql_server_name);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $query_results[0]["video_Title"]; ?></title>
    <link rel="stylesheet" href="./css/css_video_page_mobile.css">
    <script src="../js/scripts_vpage_1.js"></script>
</head>
<body>
    <div id="content">
        <!--the link image header-->
        <a id="image_logo" name="image_logo" href="../main_mobile.php"><img src="../images/not_youtube_logo.png" alt="logo return link" height="100px" width="100%"></a>
        <!--redirect form-->
        <form id="desktop_redirect" name="desktop_redirect" action="./vpage_template.php" method="get">
            <input id="video_id" name="video_id" type="hidden" value="<?php echo $video_id ?>">
            <!--do nothing. this only exists to refirect-->
        </form>
        <!--search bar-->
        <form action="../search_results.php" method="get">
            <input id="search_bar" type="search" name="search_bar" id="search_bar" placeholder="search">
        </form>
        <!--navigation links-->
        <a class="navigation_links" href="../upload.php">Upload</a>
        <a class="navigation_links" href="../log_out.php">Log out</a>
        <a class="navigation_links" href="./account_management.php">Manage account</a>
        <!--video-->
        <video id="video_player" src="<?php echo $query_results[0]["file_Location"] ?>" width="70%" height="400" controls poster="<?php echo $query_results[0]["path_To_Video_Thumbnail"] ?>" id="video_player"></video>
        <!--like/dislike buttons-->
        <div id="rating_panel">
            <p style="text-align: center;">like/dislike buttons go here</p>
        </div>
        <!--video information-->
        <div id="video_information">
            <p id="video_title"><?php echo $query_results[0]["video_Title"]; ?></p>
            <?php
            echo $query_results[0]["file_Description"];
        ?>
        </div>
        <!--recomendations-->
        <div id="recomendations_section">
            <p style="text-align: center;">recomendations go here</p>
        </div>
                <!--comments section -->
        <div id="comments_section">
            <p style="text-align: center;">comments section goes here</p>
        </div>
    </div>
    </div>
</body>
</html>
<script>
    // redirect to mobile video page
    let screen_width = window.innerWidth;
    // If the screen width is more than 1200px, redirect to desktop page
    console.log(`screen width is: ${screen_width}`);
    if (screen_width > 1200) {
        window.onload = function() {
            console.log("screen width is equal to or greater than defined desktop resolution");
            document.getElementById("desktop_redirect").submit();
        }
    }
</script>