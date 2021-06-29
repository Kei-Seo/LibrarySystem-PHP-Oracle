<?php
session_start();
if(!isset($_SESSION['userid'])) {
    ?>
    <script type="text/javascript">alert('로그인 해주새요');location.href='/index.php';</script>
    <?php
    exit; }
$admin = $_SESSION['userid'];
if($admin != 'abc123') {
    ?>
    <script type="text/javascript">alert('관리자만 접근할 수 있습니다.');location.href='/main.php';</script>
    <?php
    exit; }


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
$userId = $_SESSION['userid'];
try {
    $conn = new PDO($dsn, $username, $password);
} catch (PDOException $e) {
    echo("에러 내용: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <link rel="stylesheet" type="text/css" href="/css/common.css">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-wEmeIV1mKuiNpC+IOBjI7aAzPcEZeedi5yW5f2yOq55WWLwNGmvvx4Um1vskeMj0"
          crossorigin="anonymous">
    <style>
        a {
            text-decoration: none;
        }
    </style>


</head>
<div id="wrap">
    <div id="header">
        <?php include "./lib/top_login1.php"; ?>
    </div> <!-- end of header -->
    <div id="menu">
        <?php include "./lib/top_menu1.php"; ?>
    </div> <!-- end of menu -->
<body>
<div style="padding: 100px"/>

<div class="container" >
    <h2 class="text-center" style="padding: 10px; font-weight: bold">도서 대출 현황</h2>
    <table class="table table-bordered text-center">
        <thead>
        <tr>
            <th>책 번호</th>
            <th>책 이름</th>
            <th>대여일</th>
            <th>반납일</th>
            <th>대여 횟수</th>
        </tr>
        </thead>
        <tbody>
        <?php

        $stmt = $conn->prepare("SELECT * FROM EBOOK");
        $stmt->bindParam(':id', $_SESSION['userid']);
        $stmt->bindParam(':cnt',$nRows );
        $stmt->execute();



        $stmt = $conn->prepare("SELECT ISBN,  RANK() OVER (ORDER BY :cnt)  AS BRNA, DATERENTED, DATERETURNED
FROM PREVIOUSRENTAL WHERE ROWID IN(SELECT  MAX(ROWID) FROM PREVIOUSRENTAL GROUP BY ISBN) order by ISBN");
        $stmt->bindParam(':id', $_SESSION['userid']);
        $stmt->bindParam(':cnt',$nRows );
        $stmt->execute();

        //<td><button onclick="document.location.href='exttimes.php';bookId=<?= $row['ISBN']
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            ?>
            <tr>

                <td><?= $row['ISBN']?></td>
                <?php
                $records = $conn->prepare('SELECT * FROM EBOOK WHERE ISBN = :bookId  ');
                $records->bindParam(':bookId', $row['ISBN']);
                $records->bindParam(':bid', $bookId);
                $records->execute();
                $results = $records->fetch(PDO::FETCH_ASSOC);
                $ext = $results['TITLE'];
                $sql = "SELECT COUNT(*) FROM PREVIOUSRENTAL WHERE ISBN = '".$row['ISBN']."'";
                $nRows = $conn->query($sql)->fetchColumn();
                $nRows;

                ?>
                <td><?=$ext?></td>
                <td><?= $row['DATERENTED'] ?></td>
                <td><?= $row['DATERETURNED'] ?></td>
                <td><?= $nRows?></td>

            </tr>
            <?php
        }




        ?>
        </tbody>
    </table>

    <h2 class="text-center" style="padding: 10px; font-weight: bold">사용자 대여 횟수</h2>

    <table class="table table-bordered text-center">
        <thead>
        <tr>
            <th>아이디</th>
            <th>이름</th>

            <th>대여 횟수</th>
        </tr>
        </thead>
        <tbody>
        <?php

        $stmt = $conn->prepare("SELECT * FROM EBOOK");
        $stmt->bindParam(':id', $_SESSION['userid']);
        $stmt->bindParam(':cnt',$nRows );
        $stmt->execute();



        $stmt = $conn->prepare("SELECT CNO,  RANK() OVER (ORDER BY :cnt)  AS BRNA, DATERENTED, DATERETURNED
FROM PREVIOUSRENTAL WHERE ROWID IN(SELECT  MAX(ROWID) FROM PREVIOUSRENTAL GROUP BY CNO) order by ISBN");
        $stmt->bindParam(':id', $_SESSION['userid']);
        $stmt->bindParam(':cnt',$nRows );
        $stmt->execute();

        //<td><button onclick="document.location.href='exttimes.php';bookId=<?= $row['ISBN']
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            ?>
            <tr>

                <td><?= $row['CNO']?></td>
                <?php
                $records = $conn->prepare('SELECT * FROM CUSTOMER WHERE CNO = :cno  ');
                $records->bindParam(':cno', $row['CNO']);
                $records->bindParam(':bid', $bookId);
                $records->execute();
                $results = $records->fetch(PDO::FETCH_ASSOC);
                $ext = $results['CNAME'];
                $sql = "SELECT COUNT(*) FROM PREVIOUSRENTAL WHERE CNO = '".$row['CNO']."'";
                $nRows = $conn->query($sql)->fetchColumn();
                $nRows;

                ?>
                <td><?=$ext?></td>

                <td><?= $nRows?></td>

            </tr>
            <?php
        }




        ?>
        </tbody>
    </table>
    <div class="d-grid d-md-flex justify-content-md-end">
        <a href="input.php?mode=insert" class="btn btn-warning">등록</a>
    </div>

</div>
</div>

</body>
</html>