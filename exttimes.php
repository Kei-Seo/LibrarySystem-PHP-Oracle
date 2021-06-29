<?php

	include "./db.php";
	include "./password.php";

$ext = $_GET['ext'] ?? '';
$bookId = $_GET['bookId'] ?? '';
$dated = $_GET['dated'] ?? '';
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

//$records = $conn->prepare('SELECT * FROM EBOOK WHERE CNO = :id and ISBN = : bid');
//$records->bindParam(':id', $_SESSION['userid']);
//$records->bindParam(':bid', $bookId);
//$records->execute();
//$results = $records->fetch(PDO::FETCH_ASSOC);
////
////    //password변수에 POST로 받아온 값을 저장하고 sql문으로 POST로 받아온 아이디값을 찾습니다.
//$ext = $results['EXTTIMES'];
$sql = "SELECT COUNT(*) FROM RESERVATION WHERE ISBN = '".$bookId."'";
$nRows = $conn->query($sql)->fetchColumn();
$nRows;
if($nRows > 0) {
    echo "<script>alert('해당 책은 예약되어있어 연장할 수 없습니다.'); location.href='/Mybook.php';</script>";
    exit;
}



if ($ext < 2){
    //$timestamp = strtotime("+1 days");
    $mydate = date('y/m/d' , strtotime("+1 day", strtotime($dated)));
    $next_date = date("y/m/d", strtotime(date($dated) . "+1 days")); // 하루후 날자
    //$ext =$ext+1;
    //echo "{$ext}{$_SESSION['userid']}{$bookId}{$dated}{$next_date}";
    $stmt = $conn->prepare("UPDATE EBOOK SET EXTTIMES={$ext}+1, 
                 DATEDUE=(SELECT DATEDUE FROM EBOOK WHERE CNO = :id AND ISBN =:bid)+10 WHERE ISBN=:bid and CNO=:id");
    //echo "UPDATE EBOOK SET EXTTIMES=:0 WHERE ISBN=:bid and CNO=:id";
    $stmt->bindParam(':ext', $ext);
    $stmt->bindParam(':id', $_SESSION['userid']);
    $stmt->bindParam(':bid', $bookId);
    $stmt->bindParam(':dd', $dated);
    $stmt->execute();
    echo "<script>alert('연장했습니다.'); location.href='/Mybook.php';</script>";
}else{
    echo "<script>alert('더는 연장할 수 없습니다.'); location.href='/Mybook.php';</script>";
}

//CNO=:bcno, DATERENTED=sysdate, DATEDUE=sysdate+10 WHERE ISBN=:bid");

//$stmt->bindParam(':bid',$bookId);
//$stmt->bindParam(':bcno',$userid);
//$stmt->bindParam(':btime',$time);
////$stmt->bindParam(':address',$address);
////$stmt->bindParam(':csex',$sex);
//$stmt->bindParam(':rtime',$email);
//
//$time = date("Y-m-d H:i:s"); //현재시각을 받아옵니다.
//$str_date = strtok($time);
//$rtime = strtok($time.'+14 days');
//$rtime = date($rtime);
//
//
//$bookId = $_GET['bookId'] ?? ''; //어떤 책을 빌려야하는지 정보를 받아옵니다.
//$userid = $_SESSION['userid'] ?? ''; //현재 로그인된 유저 아이디(CNO)를 받아옵니다.
//
////$price = $_POST['price'];
//$stmt->execute();


?>

