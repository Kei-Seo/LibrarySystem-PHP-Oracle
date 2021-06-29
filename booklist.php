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

    <title>추천도서</title>
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

<div class="container">
    <div style="padding-bottom: 10px">
    <h2 class="text-center" style="font-weight: bold">추천도서</h2>
    <table class="table table-bordered text-center">
        <thead>
        <tr>
            <th>책 번호</th>
            <th>책 이름</th>
            <th>출판사</th>
            <th>작가</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $stmt = $conn->prepare("SELECT ISBN, TITLE, PUBLISHER, CNO FROM EBOOK WHERE LOWER(TITLE) LIKE '%' || :searchWord || 
'%' ORDER BY ISBN"); //EBOOK 테이블에서 쿼리를 미리 준비해서 넣어 놓습니다.
        $stmt->execute(array($searchWord)); //검색어와 같이 실행합니다.

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { //row 하나씩 한 테이블에 출력합니다.
            ?>
            <tr>
                <?php
                $auth = $conn->prepare("SELECT * FROM AUTHORS WHERE ISBN =: isbn ");
                $auth->bindParam(':isbn', $row['ISBN']); //pdo 실행하려면 바인딩해야합니다.
                $auth->execute(); //쿼리 실행
                $row2 = $auth->fetch(PDO::FETCH_ASSOC)
                ?>
                <td><?= $row['ISBN']?></td>
                <td><a href="bookview.php?bookId=<?= $row['ISBN'] ?>"><?= $row['TITLE'] ?></a></td>
                <td><?= $row['PUBLISHER'] ?></td>
                <td><?= $row2['AUTHOR'] ?></td>
            </tr>
            <?php
        }
        ?>
        </tbody>
    </table>

    <form class="row">
        <div class="col-10">
            <label for="searchWord" class="visually-hidden">Search Word</label>
            <input type="text" class="form-control" id="searchWord" name="searchWord" placeholder="검색어 입력" value="<?=
            $searchWord ?>">
        </div>
        <div class="col-auto text-end">
            <button type="submit" class="btn btn-primary mb-3">검색</button>
        </div>
    </form>
</div>
</div>
</body>
</html>