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
} catch (Exception $e) {
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
    <link rel="stylesheet" href="../css/css_video_page.css">
    <script src="./js/scripts_vpage_1.js"></script>
</head>

<body>
    <nav id="flex_section">
        <div id="homepage_return">
            <a href="../main.php"><img src="../images/not_youtube_logo.png" alt="logo return link" height="75x" width="200px"></a>
        </div>
        <form action="../search_results.php" method="get">
            <input type="search" name="search_bar" id="search_bar">
            <input type="submit" value="search" id="submit">
        </form>
        <div id="links">
            <a href="../upload.php">Upload</a>
            <a href="../log_out.php">Log out</a>
            <a href="./account_management.php">Manage account</a>
        </div>
    </nav>
    <div id="video_section">
        <video src="<?php echo $query_results[0]["file_Location"] ?>" width="1104" height="621" controls poster="<?php echo $query_results[0]["path_To_Video_Thumbnail"] ?>" id="video_player"></video>
        <form action="search_results.php" method="post" id="rating_panel" onsubmit="return test()"> <!--this form should send user input, but not go to a new page-->
            <input type="button" name="like" value="blank" id="like" onclick="change_value_like()">
            <input type="button" name="dislike" value="blank" id="dislike">
            <input type="submit">
            <!--does this actaully send anything?-->
        </form>
        <div>
            <div id="video_description">
                <p id="description_header"><?php echo $query_results[0]["video_Title"]; ?></p>
                <?php
                echo $query_results[0]["file_Description"];
                ?>
            </div>
            <div id="bottom_flex_section">
                <div id="left">collapsible comment section</div>
                <div id="right">recomendations</div>
            </div>
        </div>
    </div>
    <!--redirect to mobile video view-->
    <form id="mobile_redirect" name="mobile_redirect" action="./vpage_template_mobile.php" method="get">
        <input id="video_id" name="video_id" type="hidden" value="<?php echo $video_id ?>">
        <!--do nothing. this only exists to refirect-->
    </form>
</body>

</html>
<script>
    // redirect to mobile video page
    let screen_width = window.innerWidth;
    // If the screen width is less than 1200px, redirect to mobile page
    console.log(`screen width is: ${screen_width}`);
    if (screen_width < 1200) {
        window.onload = function() {
            console.log("screen width is smaller than defined desktop resolution");
            document.getElementById("mobile_redirect").submit();
        }
    }
</script>