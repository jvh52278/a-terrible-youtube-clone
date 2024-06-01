<?php
session_start();
if ($_SESSION["logged_in"] != true) {
    header("Location: ./index.php");
}
include "mysql_access_functions.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/css_main.css">
    <script src="./js/scripts_index.js"></script>
    <title>not youtube php</title>
</head>
<body>
    <div id="container">
        <div id="right_side_bar">
              <img src="./images/not_youtube_logo.png" height="75px" width="200px" alt="logo">
              <div style="margin-left: 10px;">
                    <p>filter options here</p>
              </div>
        </div>
        <nav id="left_side_header">
            <div id="left_side_header_flex_section">
                <form action="./search_results.php"method="get">
                    <input type="search" name="search_bar" id="search_bar">
                    <input type="submit" value="search" id="submit">
                </form>
                <div id="links">
                    <a href="./upload.php">Upload</a>
                    <a href="./log_out.php">Log out</a>
                    <a href="./account_management.php">Manage account</a>
                </div>
            </div>
        </nav>
        <main id="left_side_main_content">
            <?php
            $sql_query_use = "select * from video_info ORDER BY upload_Date DESC";
            $all_video_records = run_sql_query_return_all_results($sql_query_use,$sql_server,$database_user,$database_user_password,$database_name);
            //echo "<p style='color:white'>$all_video_records</p>";
            //var_dump($all_video_records);
            //$test_var = count($all_video_records);
            //echo "<p style='color:white'>$test_var</p>";
            $grid_display = "<div style='display: grid; grid-template-columns: 1fr 1fr 1fr;grid-gap: 50px;'>";
            foreach ($all_video_records as $record) {
                $title_display = $record['video_Title'];
                $display_image = $record['path_To_Video_Thumbnail'];
                $display_link = $record['path_To_Video_Page'];
                $display_test_link = $record['upload_Id'];
                // create each display unit
                $display_unit = "
                <div>
                <form action='./vpage_template.php' method='get'>
                <input name='video_id' id='video_id' type='hidden' value='$display_test_link'>
                <button class='false_link' type='submit' style='display: block; margin-left:auto; margin-right:auto; background-color: transparent; border:none; color: white; font-size: 20px; padding: 20px'>$title_display</button>
                </form>
                <form action='./vpage_template.php' method='get'>
                <input name='video_id' id='video_id' type='hidden' value='$display_test_link'>
                <input type='image' src='$display_image' alt='submit' width='100%' height='300px'>
                </form>
                </div>
                ";
                // append the display unit to the grid to be displayed
                $grid_display = $grid_display.$display_unit;
            }
            //display the video grid
            $grid_display = $grid_display."</div>";
            echo $grid_display;
            ?>
            <!--form to redirect of smaller screen width detected-->
            <form id="mobile_redirect" name="mobile_redirect" action="./main_mobile.php" method="get">
                <!--do nothing. this only exists to refirect-->
            </form>
        </main>
    </div>
</body>
<script>

</script>
</html>