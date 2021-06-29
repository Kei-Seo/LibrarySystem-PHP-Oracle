<?php
session_start();
if(!isset($_SESSION['userid'])) {
    ?>
    <script type="text/javascript">alert('로그인 해주새요');location.href='/index.php';</script>
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
    <h2 class="text-center" style="padding: 10px; font-weight: bold">예약 중인 도서</h2>
    <table class="table table-bordered text-center">
        <thead>
        <tr>
            <th>책 번호</th>
            <th>책 이름</th>
            <th>예약 날짜</th>
            <th>예상 반납일</th>
            <th>예약 순번</th>
            <th>예약 취소</th>

        </tr>
        </thead>
        <tbody>
        <?php

        $stmt = $conn->prepare("SELECT * FROM RESERVATION WHERE CNO = :id  ORDER BY ISBN");
        $stmt->bindParam(':id', $_SESSION['userid']);
        $stmt->execute();
        //<td><button onclick="document.location.href='exttimes.php';bookId=<?= $row['ISBN']
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            ?>
            <tr>
                <?php
                $auth = $conn->prepare("SELECT * FROM EBOOK WHERE ISBN =: isbn ");
                $auth->bindParam(':isbn', $row['ISBN']);
                $auth->execute();
                $row2 = $auth->fetch(PDO::FETCH_ASSOC)
                ?>
                <td><?= $row['ISBN']?></td>
                <td><?= $row2['TITLE'] ?></td>
                <td><?= $row2['DATERENTED'] ?></td>
                <td><?= $row2['DATEDUE'] ?></td>
                <?php
//                $rank = $conn->prepare("SELECT RAN FROM (SELECT RANK() OVER (ORDER BY RESERVATIONTIME) AS RAN FROM RESERVATION WHERE ISBN =: isbn)
//                WHERE CNO =: cno");
                $rank = $conn->prepare("SELECT RANK() OVER (ORDER BY RESERVATIONTIME) AS RAN FROM RESERVATION WHERE ISBN =: isbn");
                $rank->bindParam(':isbn', $row['ISBN']);
                $rank->bindParam(':cno', $userId);
                $rank->execute();
                $row3 = $rank->fetch(PDO::FETCH_ASSOC);
                ?>
                <td><?=$row3['RAN']?>회</a></td>
                <td>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="delete_reservation.php?bookId=<?=$row['ISBN']?> " class="btn btn-info">예약취소</a>
                    </div>
                </td>


            </tr>
            <?php
        }
        $stmt = $conn->prepare("SELECT * FROM PREVIOUSRENTAL WHERE CNO = :id  ORDER BY ISBN");
        $stmt->bindParam(':id', $_SESSION['userid']);
        $stmt->execute();
        //<td><button onclick="document.location.href='exttimes.php';bookId=<?= $row['ISBN']





        ?>
        </tbody>
    </table>


</div>
</div>
</body>
</html>