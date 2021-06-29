    <div id="logo"><a href="/main.php"><img src="./img/logo3.gif" border="0"></a></div>

	<div id="top_login">
<?php
    if(!isset($_SESSION["userid"]))
	{
?>
          <a href="/index.php">로그인</a> | <a href="/member.php">회원가입</a>
<?php
	}
	else
	{
?>
		<?=$_SESSION["userid"]?> 님
		<a href="./logout.php">로그아웃</a> | <a href="./member/updateForm.php?id=<?=$_SESSION["userid"]?>">정보수정</a>
<?php
	}
?>
	 </div>
