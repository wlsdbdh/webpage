<?php
    $db= mysqli_connect('localhost', 'prog222', '2022100877');
    if(!$db) {
    //접속 오류
        echo "DBMS 접속 오류<br>";
        exit(0); 
    }
//작업 db 선택
    if( !mysqli_select_db($db, 'prog222')) {
        echo "작업 DB 선택 오류<br>";
        mysqli_close($db); 
        exit(0);
    }
?>

<?php
session_start();

//로그인여부 확인
if (isset($_SESSION['user_id'])) {
    header('Location: profile.html');
    exit;
}
?>