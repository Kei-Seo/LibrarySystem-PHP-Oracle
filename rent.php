<?php
	include "./db.php";
	include "./password.php";

$userId = $_SESSION['userid'];
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
//$query = "SELECT COUNT(*) AS abc FROM EBOOK WHERE CNO = 'abc123'";
//$rent_ok = $conn->prepare($query);

//// execute query
//$rent_ok->execute();
//
//// get total rows
//$row = $rent_ok->fetch(PDO::FETCH_ASSOC);

$sql = "SELECT COUNT(*) FROM EBOOK WHERE CNO = '".$userId."'";
$nRows = $conn->query($sql)->fetchColumn();

$nRows;
//$rent_ok = $conn->prepare("SELECT * FROM EBOOK WHERE CNO=:bcno");
////책을 빌리면서 정보를 업데이트 합니다.

////$stmt->bindParam(':address',$address);
////$stmt->bindParam(':csex',$sex);
////$rent_ok->bindParam(':rtime',$email);
//$rent_ok->execute([$bar]);
//$count = $rent_ok->rowCount();

if($nRows > 3){
    ?>
    <script>alert("대출은 최대 4권까지 입니다."); location.href="/booklist.php"; </script>
<?php
}else {


    $stmt = $conn->prepare("UPDATE EBOOK SET 
CNO=:bcno, DATERENTED=sysdate, DATEDUE=sysdate+10 WHERE ISBN=:bid");
//책을 빌리면서 정보를 업데이트 합니다.
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
<script type="text/javascript">alert('대여되었습니다.');location.href='/booklist.php';</script>
<meta http-equiv="refresh" content="0 url=/">
