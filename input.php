<?php
$bookId = $_GET['bookId'] ?? '';
$mode = $_GET['mode'] ?? '';
$bookName = '';
$publisher = '';
$price = '';
if ($mode == 'modify') {
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
    // 쿼리 문법부터 먼저 전달
    $stmt = $conn->prepare("SELECT ISBN,TITLE, PUBLISHER, YEAR FROM EBOOK WHERE TITLE = :bookId ");
    //prepared statement 를 생성=
    $stmt->execute(array($bookId));
    // 데이터 전달
    if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $bookName = $row['TITLE'];
        $publisher = $row['PUBLISHER'];
        $publisher = $row['ISBN'];
        $year = $row['YEAR'];
    }


}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-wEmeIV1mKuiNpC+IOBjI7aAzPcEZeedi5yW5f2yOq55WWLwNGmvvx4Um1vskeMj0"
          crossorigin="anonymous">
    <style>
        a { text-decoration: none; }
    </style>
    <title>Book input</title>
</head>
<body>
<div class="container mb-3">
    <h2 class="display-4"><?= $mode == 'insert' ? '책 등록' : '책 수정'?></h2>
    <form class="row g-3 needs-validation" method="post" action="process.php?mode=<?= $mode ?>" novalidate>
        <div class="form-floating mb-3">
            <input type="text" class="form-control" maxlength="13" id="bookName" name="bookName"
                   placeholder="책 제목" value="<?= $bookName ?>" required>
            <label for="bookName" class="form-label">제목</label>
            <div class="invalid-tooltip">제목을 입력하세요.</div>
        </div>
        <div class="form-floating mb-3">
            <input type="text" class="form-control" maxlength="13" id="publisher" name="publisher"
                   placeholder="출판사" value="<?= $publisher ?>" required>
            <label for="publisher" class="form-label">출판사</label>
            <div class="invalid-tooltip">출판사를 입력하세요.</div>
        </div>
        <div class="form-floating mb-3">
            <input type="text" class="form-control" maxlength="13" id="publisher" name="author"
                   placeholder="작가" value="<?= $publisher ?>" required>
            <label for="author" class="form-label">작가</label>
            <div class="invalid-tooltip">작가를 입력하세요.</div>
        </div>
        <div class="form-floating mb-3">
            <input type="text" class="form-control" maxlength="13" id="publisher" name="author"
                   placeholder="발행연도" value="<?= $year ?>" required>
            <label for="year" class="form-label">발행연도</label>
            <div class="invalid-tooltip">발행연도를 입력하세요.</div>
        </div>

        <div class="mb-3">
            <input type="hidden" name="bookId" value="<?= $bookId ?>">
            <button class="btn btn-primary" type="submit"><?= $mode == 'insert' ? '등록' : '수정'?></button>
        </div>
    </form>
</body>
<script src="main.js"></script>
</html>