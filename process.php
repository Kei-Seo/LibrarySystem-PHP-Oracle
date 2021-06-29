<?php

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

$dbh = new PDO($dsn, $username, $password);
    switch($_GET['mode']){
        case 'insert':
            $stmt = $dbh->prepare("INSERT INTO EBOOK (ISBN, TITLE, PUBLISHER, YEAR) VALUES ((SELECT 
NVL(MAX(ISBN), 0) + 1 FROM EBOOK), :bookName, :publisher, sysdate)");

            $stmt->bindParam(':bookName',$bookName);
            $stmt->bindParam(':publisher',$publisher);
            //$stmt->bindParam(':price',$price);
            $bookName = $_POST['bookName'];
            $publisher = $_POST['publisher'];
            //
            $stmt->execute();

            $author = $dbh->prepare("INSERT INTO AUTHORS (ISBN, AUTHOR) VALUES ((SELECT 
NVL(MAX(ISBN), 0) FROM EBOOK), :auths)");
            $author->bindParam(':isb',$isbn);
            $author->bindParam(':auths',$auths);
            //$author->bindParam(':publisher',$publisher);
            //$stmt->bindParam(':price',$price);
            $auths= $_POST['author'];
            //$price = $_POST['price'];
            $author->execute();
           // header("Location: booklist.php");
            break;
        case 'delete':
            $stmt = $dbh->prepare('DELETE FROM EBOOK WHERE TITLE = :bookId');
            $stmt->bindParam(':bookId', $bookId);
            $bookId = $_POST['bookId'];
            $stmt->execute();
            header("Location: booklist.php");
            break;
        case 'modify':
            $stmt = $dbh->prepare('UPDATE EBOOK SET TITLE = :bookName, PUBLISHER = :publisher WHERE 
TITLE = :bookId');
            $stmt->bindParam(':bookName', $bookName);
            $stmt->bindParam(':publisher', $publisher);
            $stmt->bindParam(':price', $price);
            $stmt->bindParam(':bookId', $bookId);
            $bookName = $_POST['bookName'];
            $publisher = $_POST['publisher'];
           // $price = $_POST['price'];
            $bookId = $_POST['bookId'];
            $stmt->execute();
            header("Location: bookview.php?bookId=$bookId");
            break;
    }
?>