<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <div style="background-color: lightgrey">
        <?php
            echo "this is an iframe<br>";
            echo "this was the input sent to the iframe<br>";
            $form_input = $_POST['name_of_input'];
            echo "|".$form_input."|";
        ?>
    </div>
</body>
</html>