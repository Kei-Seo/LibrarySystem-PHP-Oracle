 <?php
  session_start();
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
 //$dsn = "oci:dbname=" . $tns . ";charset=utf8";
 $username = 'D201702026';
 $password = '1234';
 $searchWord = $_GET['searchWord'] ?? '';

 try {
     $conn = new PDO($dsn, $username, $password);
 } catch (PDOException $e) {
     echo("에러 내용: " . $e->getMessage());
 }
 //var_dump($conn);
 //$db = new mysqli("localhost","kei","1234", "c##madang");
 //$conn->set_charset("utf8");
 // . connection

  function mq($sql){
    global $conn;
    return $conn->query($sql);
  }

  ?>