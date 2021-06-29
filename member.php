<?php  
	include "./db.php";
$userid = $_GET['userid'] ?? '';
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />

	<title>회원가입 폼</title>
    <link rel="stylesheet" type="text/css" href="css/common.css">

</head>
<body>
<div id="wrap">
    <div id="header">
        <?php include "./lib/top_login1.php"; ?>
    </div> <!-- end of header -->
    <div id="menu">
        <?php include "./lib/top_menu1.php"; ?>
    </div> <!-- end of menu -->
    <div style="padding: 100px"/>
	<form method="POST" action="member_ok.php" >
		<h1>회원가입 폼</h1>
			<fieldset>
				<legend>입력사항</legend>
					<table>
						<tr>
							<td>아이디</td>
							<td><input type="text" size="35" name="userid"  id="userid"  placeholder="아이디" value="<?=$userid?>">

                                <a href="member_dupl.php?userid=<?=$userid?>" class="btn btn-info">중복확인</a>

                            </td>
						</tr>
						<tr>
							<td>비밀번호</td>
							<td><input type="password" size="35" name="userpw" placeholder="비밀번호"></td>
						</tr>
						<tr>
							<td>이름</td>
							<td><input type="text" size="35" name="name" placeholder="이름"></td>
						</tr>
						<tr>
							<td>이메일</td>
							<td><input type="text" name="email">@<select name="emadress"><option value="naver.com">naver.com</option><option value="nate.com">nate.com</option>
							<option value="hanmail.com">hanmail.com</option></select></td>
						</tr>
					</table>
				<input type="submit" value="가입하기" /><input type="reset" value="다시쓰기" />
		</fieldset>
    </form>
</div>
</body>
</html>