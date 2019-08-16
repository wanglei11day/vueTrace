<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo $title;?></title>
    <link rel="stylesheet" href="">
    <!-- Flipbook StyleSheet -->
    <link href="flip/css/dflip.css" rel="stylesheet" type="text/css">

    <!-- Icons Stylesheet -->
    <link href="flip/css/themify-icons.css" rel="stylesheet" type="text/css">
    <style>
        body, html {
            height:100%;
            margin:0;
        }
    </style>
</head>
<body>
<div id="flipbookContainer"></div>

<!-- jQuery 1.9.1 or above -->
<script src="flip/js/libs/jquery.min.js" type="text/javascript"></script>

<!-- Flipbook main Js file -->
<script src="flip/js/dflip.js" type="text/javascript"></script>

<script>
    var flipBook;
    jQuery(document).ready(function () {

        //uses source from online(make sure the file has CORS access enabled if used in cross domain)
        var pdf = '<?php echo $pdf;?>';

        var options = {height: '100%', duration: 800};

        flipBook = $("#flipbookContainer").flipBook(pdf, options);

    });
</script>
</body>
</html>