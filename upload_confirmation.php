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
    <title>Document</title>
</head>
<body>
    <h1>This is the upload confirmation page</h1>
    <h1>user will be redirected back to the upload page in 5 seconds</h1>
    <a href="./upload.php"><h1>Upload another video</h1></a>
    <a href="./main.php"><h1>Return to main page</h1></a>
    <?php
    // redirect back to upload page after 5 seconds
    header("refresh:5;url=./upload.php");
    //header("Location: ./main.php");
    ?>
</body>
</html>
