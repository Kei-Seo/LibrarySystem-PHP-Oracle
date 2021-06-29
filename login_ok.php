<meta charset="utf-8"/>

<?php
$tns = "
(DESCRIPTION=
(ADDRESS_LIST= (ADDRESS=(PROTOCOL=TCP)(HOST=cnusdlab.synology.me)(PORT=1521)))
(CONNECT_DATA= (SERVICE_NAME=XE))
)
";
$dbHost = "localhost";      // 호스트 주소(localhost, 120.0.0.1)
$dbName = "xe";      // 데이타 베이스(DataBase) 이름
$dbChar = "utf8";
$dsn = "oci:dbname={$dbHost};dbname={$dbName};charset={$dbChar}";
$username = 'D201702026';
$password = '1234';
try {
    $conn = new PDO($dsn, $username, $password);
} catch (PDOException $e) {
    echo("에러 내용: " . $e->getMessage());
}
include "./db.php";
include "./password.php";
//echo "{$_POST['userid']}";
//POST로 받아온 아이다와 비밀번호가 비었다면 알림창을 띄우고 전 페이지로 돌아갑니다.
if ($_POST["userid"] == "" || $_POST["userpw"] == "") {
    echo '<script> alert("아이디나 패스워드 입력하세요"); history.back(); </script>';
} else {

    $records = $conn->prepare('SELECT * FROM CUSTOMER WHERE CNO = :id');
    $records->bindParam(':id', $_POST['userid']);
    $records->execute();
    $results = $records->fetch(PDO::FETCH_ASSOC);
//
//    //password변수에 POST로 받아온 값을 저장하고 sql문으로 POST로 받아온 아이디값을 찾습니다.
    $passwd = $_POST['userpw'];
//    $sql = "select * from CUSTOMER where CNO ='{$_POST['userid']}'";
//
//    $conn->query($sql);
//    $member = $sql->fetch_array();
    $hash_pw = $results['CPASSWD']; //$hash_pw에 POSt로 받아온 아이디열의 비밀번호를 저장합니다.
   // echo "{$hash_pw}";
   // echo "{$passwd}";
    if (password_verify($passwd, $hash_pw)) //만약 password변수와 hash_pw변수가 같다면 세션값을 저장하고 알림창을 띄운후 main.php파일로 넘어갑니다.
    {
        $_SESSION['userid'] = $results["CNO"];
        $_SESSION['userpw'] = $results["CPASSWD"];

        echo "<script>alert('로그인되었습니다.'); location.href='/main.php';</script>";
    } else { // 비밀번호가 같지 않다면 알림창을 띄우고 전 페이지로 돌아갑니다
        echo "<script>alert('아이디 혹은 비밀번호를 확인하세요.'); history.back();</script>";
    }
}
?>