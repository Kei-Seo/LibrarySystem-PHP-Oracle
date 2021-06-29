<?php
session_start();
$dbHost = "localhost";      // 호스트 주소(localhost, 120.0.0.1)
$dbName = "xe";      // 데이타 베이스(DataBase) 이름
$dbChar = "utf8";
$dsn = "oci:dbname={$dbHost};dbname={$dbName};charset={$dbChar}";
//$dsn = "oci:dbname=" . $tns . ";charset=utf8";
$username = 'D201702026';
$password = '1234';
$bookId = $_GET['bookId'];
try {
    $conn = new PDO($dsn, $username, $password);
} catch (PDOException $e) {
    echo("에러 내용: " . $e->getMessage());
}



$stmt = $conn->prepare("SELECT ISBN, TITLE, PUBLISHER, CNO, DATEDUE FROM EBOOK WHERE ISBN = ? ");
$stmt->execute(array($bookId));
$bookName = '';
$publisher = '';

$price = '';
if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $bookName = $row['TITLE'];
    $publisher = $row['PUBLISHER'];
    $rent = $row['CNO'];
    $rdate = $row['DATEDUE'];

$auth = $conn->prepare("SELECT * FROM AUTHORS WHERE ISBN =: isbn ");
$auth->bindParam(':isbn', $row['ISBN']);
$auth->execute();
$row2 = $auth->fetch(PDO::FETCH_ASSOC)
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="/css/common.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css"
          rel="stylesheet" integrity="sha384-wEmeIV1mKuiNpC+IOBjI7aAzPcEZeedi5yW5f2yOq55WWLwNGmvvx4Um1vskeMj0"
          crossorigin="anonymous">
    <style>
        a { text-decoration: none; }
    </style>
    <title>Book VIEW</title>
</head>
<body>
<div id="wrap">

    <div id="header">
        <?php include "./lib/top_login1.php"; ?>
    </div> <!-- end of header -->
    <div id="menu">
        <?php include "./lib/top_menu1.php"; ?>
    </div> <!-- end of menu -->
    <div style="margin: 150px"></div>
<div class="container">
    <h2 class="display-6">상세 화면</h2>
    <table class="table table-bordered text-center">
        <tbody>
        <tr>
            <td>제목</td>
            <td><?= $bookName ?></td>
        </tr>
        <tr>
            <td>출판사</td>
            <td><?= $publisher ?></td>
        </tr>
        <tr>
            <td>작가</td>
            <td><?= $row2['AUTHOR'] ?></td>
        </tr>
        <tr>
            <td>대여정보</td>
            <td><?php
                if(!isset($_SESSION['userid'])) { echo '로그인 해주세요'; exit;}
                if(isset($rent)){
                    ?>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="reservation.php?bookId=<?= $bookId ?> " class="btn btn-info">예약가능</a>
                    </div>
                <?php
                }else{
                    ?>
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <a href="rent.php?bookId=<?= $bookId ?> " class="btn btn-info">대여가능</a>
                <?php
                }
                ?>
            </td>
        </tr>
        <tr>
            <td>예상반납일</td>
            <td><?= $rdate ?></td>
        </tr>
        </tbody>
    </table>
    <?php
    }
    ?>
    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
        <a href="booklist.php" class="btn btn-success">목록</a>

    </div>
</div>
<!-- Delete Confirm Modal -->
<div class="modal fade" id="deleteConfirmModal" aria-labelledby="deleteConfirmModalLabel" ariahidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteConfirmModalLabel"><?= $bookName ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" arialabel="Close"></button>
            </div>
            <div class="modal-body">
                위의 책을 삭제하시겠습니까?
            </div>
            <div class="modal-footer">
                <form action="process.php?mode=delete" method="post" class="row">
                    <input type="hidden" name="bookId" value="<?= $bookId ?>">
                    <button type="submit" class="btn btn-danger">삭제</button>
                </form>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">취소</button>
            </div>
        </div>
    </div>
</div>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4"
        crossorigin="anonymous"></script>
</html>