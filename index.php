<?php include "db.php"; ?>

<!DOCTYPE html>
<head>
    <meta charset="utf-8"/>

    <title>회원가입 및 로그인 사이트</title>

    <link rel="stylesheet" type="text/css" href="./css/common.css"/>
    <link rel="stylesheet" href="./css/login.css">
    <div id="header">
        <?php include "./lib/top_login1.php"; ?>
    </div> <!-- end of header -->
    <div id="menu">
        <?php include "./lib/top_menu1.php"; ?>
    </div> <!-- end of menu -->
</head>
<body>
<div class="wrap">
    <div class="form-wrap">

        <form method="post" action="login_ok.php" class="input-group">
            <div>
                <input type="text" name="userid" class="input-field" placeholder="아이디" >
                <input type="password" name="userpw" class="input-field" placeholder="비밀번호" >
                <a style="padding-bottom: 10px" href="member.php">회원가입 하시겠습니까?</a>
            </div>

            <button style="padding: 10px" class="submit" id="btn">로그인</button>
        </form>

    </div>
</div>
<script>
    var x = document.getElementById("login");
    var y = document.getElementById("register");
    var z = document.getElementById("btn");


    function login(){
        x.style.left = "50px";
        y.style.left = "450px";
        z.style.left = "0";
    }

    function register(){
        x.style.left = "-400px";
        y.style.left = "50px";
        z.style.left = "110px";
    }
</script>
</body>
</html>