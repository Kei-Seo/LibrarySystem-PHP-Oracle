<?php
include "./db.php";
include "./password.php";


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
//echo "insert into CUSTOMER (CNO, NAME,PASSWD,EMAIL, ID, ADDRESS, SEX)
//values((SELECT NVL(MAX(CNO), 0) + 1 FROM CUSTOMER),'{$usname}','{$userpw}','{$email}','{$userid}','{$address}','{$sex}')";

//$sql = mq("insert into CUSTOMER (CNO, NAME,PASSWD,EMAIL, ID, ADDRESS, SEX)
//values((SELECT NVL(MAX(CNO), 0) + 1 FROM CUSTOMER),'{$username}','{$userpw}','{$email}','{$userid}','{$adress}','{$sex}')");

//$sql = "insert into CUSTOMER (CNO, NAME,PASSWD,EMAIL, ID, ADDRESS, SEX)
//values((SELECT NVL(MAX(CNO), 0) + 1 FROM CUSTOMER),'{$usname}','{$userpw}','{$email}','{$userid}','{$address}','{$sex}')";

//$sql = 'INSERT INTO CUSTOMER(CNO, NAME,PASSWD,EMAIL, ID, ADDRESS, SEX) VALUES ((SELECT NVL(MAX(CNO), 0) + 1 FROM CUSTOMER), :cname, :pw, :mail, :cid, :address, :csex)';
//$compiled = oci_parse($conn, $sql);
//
//oci_bind_by_name($compiled, ':cid', $userid);
//oci_bind_by_name($compiled, ':pw', $userpw);
//oci_bind_by_name($compiled, ':cname', $usname);
//oci_bind_by_name($compiled, ':address', $address);
//oci_bind_by_name($compiled, ':csex', $sex);
//oci_bind_by_name($compiled, ':mail', $email);
//
//oci_execute($compiled);
//
//if($conn->query($sql)){
//    echo '회원가입 성공';
//}
$bookId = $_GET['bookId'] ?? ''; //어떤 책을 빌려야하는지 정보를 받아옵니다.
$userid = $_SESSION['userid'] ?? ''; //현재 로그인된 유저 아이디(CNO)를 받아옵니다.


$sql = "SELECT COUNT(*) FROM RESERVATION WHERE CNO = '".$userid."'";
$nRows = $conn->query($sql)->fetchColumn();

$nRows;
if($nRows > 2){
    ?>
    <script>alert("예약은 최대 3권까지 가능합니다."); location.href="/booklist.php"; </script>
    <?php
}else {


    $stmt = $conn->prepare("INSERT INTO RESERVATION (ISBN, RESERVATIONTIME, CNO)
 VALUES (:bid, sysdate, :bcno)");

    $stmt->bindParam(':bid', $bookId);
    $stmt->bindParam(':bcno', $userid);
    $stmt->bindParam(':btime', $time);
//$stmt->bindParam(':address',$address);
//$stmt->bindParam(':csex',$sex);
    $stmt->bindParam(':rtime', $email);

    $time = date("Y-m-d H:i:s"); //현재시각을 받아옵니다.
    $str_date = strtok($time);
    $rtime = strtok($time . '+14 days');
    $rtime = date($rtime);


    $bookId = $_GET['bookId'] ?? ''; //어떤 책을 빌려야하는지 정보를 받아옵니다.
    $userid = $_SESSION['userid'] ?? ''; //현재 로그인된 유저 아이디(CNO)를 받아옵니다.

//$price = $_POST['price'];
    $stmt->execute();

}
?>

<meta charset="utf-8" />
<script type="text/javascript">alert('예약되었습니다.');location.href='/booklist.php';</script>
<meta http-equiv="refresh" content="0 url=/">
