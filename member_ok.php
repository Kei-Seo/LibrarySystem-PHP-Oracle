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

$stmt = $conn->prepare("INSERT INTO CUSTOMER(CNO, CNAME,CPASSWD,CMAIL) 
VALUES (:cid, :cname, :pw, :mail)");
$stmt->bindParam(':cid',$userid);
$stmt->bindParam(':pw',$userpw);
$stmt->bindParam(':cname',$usname);
//$stmt->bindParam(':address',$address);
//$stmt->bindParam(':csex',$sex);
$stmt->bindParam(':mail',$email);
$userid = $_POST['userid'];
$userpw = password_hash($_POST['userpw'], PASSWORD_DEFAULT);
$usname = $_POST['name'];
//$address = $_POST['address'];
//$sex = $_POST['sex'];
$email = $_POST['email'].'@'.$_POST['emadress'];

//$price = $_POST['price'];
$stmt->execute();


?>

<meta charset="utf-8" />
<script type="text/javascript">alert('회원가입이 완료되었습니다.');</script>
<meta http-equiv="refresh" content="0 url=/">