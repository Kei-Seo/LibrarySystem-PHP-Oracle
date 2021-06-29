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

    <title>BOOK LIST</title>
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
        <h2 class="text-center"></h2>
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
            if ($searchWord != '') {
                $category = $_GET['category'];

                if ($category == '제목' || $category =="제목&츨판사"|| $category =="제목&저자"||$category=="제목+저자"||$category=="제목&출판사&저자") {
                    $stmt = $conn->prepare("SELECT ISBN, TITLE, PUBLISHER, CNO FROM EBOOK
            WHERE LOWER(TITLE) LIKE '%' || :searchWord ||  '%'  ORDER BY ISBN");
                    $stmt->execute(array($searchWord));
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        ?>

                        <tr>
                            <td><?= $row['ISBN'] ?></td>
                            <td><a href="bookview.php?bookId=<?= $row['ISBN'] ?>"><?= $row['TITLE'] ?></a></td>
                            <td><?= $row['PUBLISHER'] ?></td>
                            <?php
                            $auth = $conn->prepare("SELECT * FROM AUTHORS WHERE ISBN =: isbn ");
                            $auth->bindParam(':isbn', $row['ISBN']);
                            $auth->execute();
                            $row2 = $auth->fetch(PDO::FETCH_ASSOC)
                            ?>
                            <td><?= $row2['AUTHOR'] ?></td>

                        </tr>
                        <?php
                    }
                } else if ($category == '출판사') {
                    $stmt = $conn->prepare("SELECT ISBN, TITLE, PUBLISHER, CNO FROM EBOOK
            WHERE LOWER(PUBLISHER) LIKE '%' || :searchWord ||  '%'  ORDER BY ISBN");
                    $stmt->execute(array($searchWord));
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        ?>

                        <tr>
                            <td><?= $row['ISBN'] ?></td>
                            <td><a href="bookview.php?bookId=<?= $row['ISBN'] ?>"><?= $row['TITLE'] ?></a></td>
                            <td><?= $row['PUBLISHER'] ?></td>
                            <?php
                            $auth = $conn->prepare("SELECT * FROM AUTHORS WHERE ISBN =: isbn ");
                            $auth->bindParam(':isbn', $row['ISBN']);
                            $auth->execute();
                            $row2 = $auth->fetch(PDO::FETCH_ASSOC)
                            ?>
                            <td><?= $row2['AUTHOR'] ?></td>

                        </tr>
                        <?php


                    }
                } else if ($category == '작가') {
                    $stmt = $conn->prepare("SELECT ISBN, AUTHOR FROM AUTHORS
            WHERE LOWER(AUTHOR) LIKE '%' || :searchWord || '%' ORDER BY ISBN");
                    $stmt->execute(array($searchWord));
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        ?>
                        <tr>
                            <?php
                            $auth = $conn->prepare("SELECT * FROM EBOOK WHERE ISBN =: isbn ");
                            $auth->bindParam(':isbn', $row['ISBN']);
                            $auth->execute();
                            $row2 = $auth->fetch(PDO::FETCH_ASSOC)
                            ?>
                            <td><?= $row2['ISBN'] ?></td>
                            <td><a href="bookview.php?bookId=<?= $row2['ISBN'] ?>"><?= $row2['TITLE'] ?></a></td>
                            <td><?= $row2['PUBLISHER'] ?></td>

                            <td><?= $row['AUTHOR'] ?></td>

                        </tr>
                        <?php


                    }
                } else if ($category == '제목+출판사') {
                    $stmt = $conn->prepare("SELECT ISBN, TITLE, PUBLISHER, CNO FROM EBOOK
            WHERE LOWER(TITLE) LIKE '%' || :searchWord ||  '%' or LOWER(PUBLISHER) LIKE '%' || :searchWord ||  '%'  ORDER BY ISBN");
                    $stmt->execute(array($searchWord));
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        ?>

                        <tr>
                            <td><?= $row['ISBN'] ?></td>
                            <td><a href="bookview.php?bookId=<?= $row['ISBN'] ?>"><?= $row['TITLE'] ?></a></td>
                            <td><?= $row['PUBLISHER'] ?></td>
                            <?php
                            $auth = $conn->prepare("SELECT * FROM AUTHORS WHERE ISBN =: isbn ");
                            $auth->bindParam(':isbn', $row['ISBN']);
                            $auth->execute();
                            $row2 = $auth->fetch(PDO::FETCH_ASSOC)
                            ?>
                            <td><?= $row2['AUTHOR'] ?></td>

                        </tr>
                        <?php


                    }

                } else if ($category == '제목+출판사+저자') {
                    $stmt = $conn->prepare("SELECT ISBN, TITLE, PUBLISHER, CNO FROM EBOOK
            WHERE LOWER(TITLE) LIKE '%' || :searchWord ||  '%' or LOWER(PUBLISHER) LIKE '%' || :searchWord ||  '%'  ORDER BY ISBN");
                    $stmt->execute(array($searchWord));
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        ?>

                        <tr>
                            <td><?= $row['ISBN'] ?></td>
                            <td><a href="bookview.php?bookId=<?= $row['ISBN'] ?>"><?= $row['TITLE'] ?></a></td>
                            <td><?= $row['PUBLISHER'] ?></td>
                            <?php
                            $auth = $conn->prepare("SELECT * FROM AUTHORS WHERE ISBN =: isbn ");
                            $auth->bindParam(':isbn', $row['ISBN']);
                            $auth->execute();
                            $row2 = $auth->fetch(PDO::FETCH_ASSOC)
                            ?>
                            <td><?= $row2['AUTHOR'] ?></td>

                        </tr>
                        <?php


                    }

                }


            }
            ?>


            </tbody>
        </table>

        <form class="row" style="padding: 50px">
            <div><label for="ex_select"/>
                <select id="ex_select" name="category">
                    <option selected>제목</option>
                    <option>출판사</option>
                    <option>작가</option>
                    <option>제목+출판사</option>
                    <option>제목+저자</option>
                    <option>제목&츨판사</option>
                    <option>제목&저자</option>
                    <option>제목+출판사+저자</option>
                    <option>제목&출판사&저자</option>
                </select>
            </div>
            <div class="col-10">
                <label for="searchWord" class="visually-hidden">Search Word</label>
                <input type="text" class="form-control" id="searchWord" name="searchWord" placeholder="원하는 책을 검색하세요."
                       value="<?=
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