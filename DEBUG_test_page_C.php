<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>test page</title>
</head>
<body>
    <h1>how to make form submit, but only refresh a part of a page</h1>
    <h1>this page was loaded on <?php echo time() ?></h1>
    <!--an iframe -> essentially a webpage in a web page-->
    <iframe src="./DEBUG_test_page_C_iframe.php" width="100%" height="100px" id="name_of_iframe" name="name_of_iframe" frameborder="0"></iframe>
    
    <!--a form that does only targets / reloads the iframe-->
<form target="name_of_iframe" action="./DEBUG_test_page_C_iframe.php" method="post">
    <input type="text" id="name_of_input" name="name_of_input">
    <input type="submit" value="submit_change_to_iframe">
</form>
</body>
</html>