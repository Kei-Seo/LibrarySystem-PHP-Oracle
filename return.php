<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

include "./db.php";
include "./password.php";
require "./PHPMailer-master/src/PHPMailer.php";
require "./PHPMailer-master/src/SMTP.php";
require "./PHPMailer-master/src/Exception.php";
$mail = new PHPMailer(true);

$dater = $_GET['dater'] ?? '';
$bookId = $_GET['bookId'] ?? '';
$bookname = $_GET['bookname'] ?? '';
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
//이전 대여기록에 데이터를 저장
$records = $conn->prepare('INSERT INTO PREVIOUSRENTAL(ISBN, DATERENTED,DATERETURNED,CNO) 
VALUES (:bid, :dater, sysdate, :id)');
$records->bindParam(':id', $_SESSION['userid']);
$records->bindParam(':bid', $bookId);
$records->bindParam(':dater', $dater);
$records->execute();

//해당도서가 예약이 되어있는지 확인.
$check_res = $conn->prepare('SELECT * FROM RESERVATION WHERE ISBN =:bid ORDER BY RESERVATIONTIME');
$check_res->bindParam(':id', $_SESSION['userid']);
$check_res->bindParam(':bid', $bookId);
$check_res->bindParam(':dater', $dater);
$check_res->execute();
$row = $check_res->fetch(PDO::FETCH_ASSOC);
$resid = '';
//가장 먼저 예약한 사람을 추출
for ($i = 0; $i < 1; $i++) {
    $resid = $row['CNO'];
    //예약 테이블에서 데이터를 지우고 해당 사용자에게 이메일 보낸다.
    if ($resid != '') {
       // echo "오";
        $remove_res = $conn->prepare('DELETE FROM RESERVATION WHERE ISBN =:bid AND CNO =: id');
        $remove_res->bindParam(':id', $resid);
        $remove_res->bindParam(':bid', $bookId);
        $remove_res->execute();

        $title = $conn->prepare('SELECT * FROM EBOOK WHERE ISBN =: bid');
        $title->bindParam(':bid', $bookId);
        $title->execute();
        $row2 = $title->fetch(PDO::FETCH_ASSOC);
        $title ='';
        for ($k = 0; $k < 1; $k++) {
            $title = $row2['TITLE'];
        }


        $send_email = $conn->prepare('SELECT * FROM CUSTOMER WHERE CNO =: id');
        $send_email->bindParam(':id', $resid);
        $send_email->bindParam(':bid', $bookId);
        $send_email->execute();
        $row1 = $send_email->fetch(PDO::FETCH_ASSOC);
        $email = '';
        for ($j = 0; $j < 1; $j++) {
            $email = $row1['CMAIL'];
            //echo $email,$title;
            try {
// 서버세팅
//디버깅 설정을 0 으로 하면 아무런 메시지가 출력되지 않습니다
                $mail->SMTPDebug = 2; // 디버깅 설정
                $mail->isSMTP(); // SMTP 사용 설정
// 지메일일 경우 smtp.gmail.com, 네이버일 경우 smtp.naver.com
                $mail->Host = "smtp.naver.com";               // 네이버의 smtp 서버
                $mail->SMTPAuth = true;                         // SMTP 인증을 사용함
                $mail->Username = "kei3824@naver.com";    // 메일 계정 (지메일일경우 지메일 계정)
                $mail->Password = "Cheld!#5";                  // 메일 비밀번호
                $mail->SMTPSecure = "ssl";                       // SSL을 사용함
                $mail->Port = 465;                                  // email 보낼때 사용할 포트를 지정
                $mail->CharSet = "utf-8"; // 문자셋 인코딩
// 보내는 메일
                $mail->setFrom("kei3824@naver.com", "EBOOK 도서관");
// 받는 메일
                $mail->addAddress("$email", "receive01");
// 첨부파일
                // $mail->addAttachment("./test1.zip");
                // $mail->addAttachment("./test2.jpg");
// 메일 내용
                $mail->isHTML(true); // HTML 태그 사용 여부
                $mail->Subject = "[EBOOK] 예약하신 $title 책이 반납되었습니다.";  // 메일 제목
                $mail->Body = "내일까지 대출해주세요.";     // 메일 내용
                $mail->SMTPOptions = array(
                    "ssl" => array(
                        "verify_peer" => false
                    , "verify_peer_name" => false
                    , "allow_self_signed" => true
                    )

                );
// 메일 전송
                $mail->send();
                echo "Message has been sent";
            } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error : ", $mail->ErrorInfo;
            }

        }

    }


}


$stmt = $conn->prepare('UPDATE EBOOK SET EXTTIMES=0, DATERENTED = NULL, DATEDUE=NULL, CNO = NULL WHERE  ISBN=:bid');
$stmt->bindParam(':id', $_SESSION['userid']);
$stmt->bindParam(':bid', $bookId);
$stmt->bindParam(':dater', $dater);
$stmt->execute();

echo "<script>alert('반납되었습니다.'); location.href='/Mybook.php';</script>";


//$results = $records->fetch(PDO::FETCH_ASSOC);
////
////    //password변수에 POST로 받아온 값을 저장하고 sql문으로 POST로 받아온 아이디값을 찾습니다.
//$ext = $results['EXTTIMES'];

//if ($ext < 2){
//    //$timestamp = strtotime("+1 days");
//    $mydate = date('y/m/d' , strtotime("+1 day", strtotime($dated)));
//    $next_date = date("y/m/d", strtotime(date($dated) . "+1 days")); // 하루후 날자
//    //$ext =$ext+1;
//    echo "{$ext}{$_SESSION['userid']}{$bookId}{$dated}{$next_date}";
//    $stmt = $conn->prepare("UPDATE EBOOK SET EXTTIMES={$ext}+1, DATEDUE=sysdate+10 WHERE ISBN=:bid and CNO=:id");
//    //echo "UPDATE EBOOK SET EXTTIMES=:0 WHERE ISBN=:bid and CNO=:id";
//    $stmt->bindParam(':ext', $ext);
//    $stmt->bindParam(':id', $_SESSION['userid']);
//    $stmt->bindParam(':bid', $bookId);
//    $stmt->bindParam(':dd', $dated);
//    $stmt->execute();
//    echo "<script>alert('연장했습니다.'); location.href='/Mybook.php';</script>";
//}else{
//    echo "<script>alert('더는 연장할 수 없습니다.'); location.href='/Mybook.php';</script>";
//}

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

