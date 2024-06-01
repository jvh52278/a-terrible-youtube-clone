<?php
session_start();
if ($_SESSION["logged_in"] != true) {
    header("Location: ./index.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>search results</title>
    <link rel="stylesheet" href="./css/search_results.css">
</head>

<body>
    <nav id="flex_section">
        <div id="homepage_return">
            <a href="./main.php"><img src="./images/not_youtube_logo.png" alt="logo return link" height="75x" width="200px"></a>
        </div>
        <form action="search_results.php" method="get">
            <input type="search" name="search_bar" id="search_bar">
            <input type="submit" value="search" id="submit">
        </form>
        <div id="links">
            <a href="./upload.php">Upload</a>
            <a href="./log_out.php">Log out</a>
            <a href="./account_management.php">Manage account</a>
        </div>
    </nav>
    <div id="results">
    <?php
    include "./search_results_processing.php";
    ?>
    </div> 
</body>
</html>
