<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body style="background-color: gray;">
    <div style="margin-right: auto; margin-left:auto; margin-top:10px; margin-bottom: 10px; width: 50%; background-color: orange; padding: 10px;">
        <h1 style="text-align:center">New user created</h1>
        <a href="./index.php"><h2 style="text-align:center; margin-top:10px; margin-bottom: 10px;"> Return to login page</h2></a>
        <h2 style="text-align:center; margin-top:10px; margin-bottom: 10px;">You will redirected to the login page in 5 seconds</h2>
    </div>
    <?php
    header("refresh:5;url=./index.php");
    ?>
</body>
</html>