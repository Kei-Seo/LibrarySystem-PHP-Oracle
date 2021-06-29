<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="./css/common.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-wEmeIV1mKuiNpC+IOBjI7aAzPcEZeedi5yW5f2yOq55WWLwNGmvvx4Um1vskeMj0"
          crossorigin="anonymous">
    <title>CHUNGNAM EBOOK</title>
</head>
<body>
<div id="wrap">

    <div id="header">
        <?php include "./lib/top_login1.php"; ?>
    </div> <!-- end of header -->
    <div id="menu">
        <?php include "./lib/top_menu1.php"; ?>
    </div> <!-- end of menu -->
    <p id="content">
        EBOOK 도서관에<br>
        오신것을 환영합니다.
    </p> <!-- end of content -->
</div> <!-- end of wrap -->

</body>
</html>