<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/common.css">
    <link rel="stylesheet" href="css/contents.css">
    <script src="js/profile.js"></script>
    <title>게시물 작성</title>
    <script>
        function checkLogin() {
            <?php
            session_start();
            if (isset($_SESSION['userid'])) {
                echo 'window.location.href = "profile.php";';
            } else {
                echo 'window.location.href = "login.html";';
            }
            ?>
        }

        function writeContents() {
            <?php
            session_start();
            if (isset($_SESSION['userid'])) {
                echo 'window.location.href = "writecontents.php";';
            } else {
                echo 'window.location.href = "login.html";';
            }
            ?>
        } 
    </script>
</head>
<body>
    <!--navigation-->
    <nav>
        <div class="nav-container">
          <div class="nav-1">
          <a href="index.php"><img class="main_logo" src="img/logo.png"></a>
          </div>
          <input id="searchInput" type="search" class="input-search" placeholder="게시물 검색">
          <div class="nav-2">
          <a href="index.php"><img class="home" src="img/home.png" alt="홈"></a>
            <a href="https://github.com/wlsdbdh"><img class = "github" src="img/github.png" alt="github"></a>
            <img class = "post" src="img/posts.png" alt="글쓰기" onclick="writeContents()">
            <img class = "heart" src="img/heart.png" alt="하트">
            <img class="profile" src="img/profile.png" alt="마이페이지" onclick="checkLogin()">
          </div>
        </div>
    </nav>
    <div class="container"></div>


    
    <div class=contents>

    <form method="post" action="">
        <input type="hidden" name="idx"> 
    
        <input type="text" name="userid" required value="<?php echo isset($_SESSION['userid']) ? $_SESSION['userid'] : ''; ?>">

        <textarea name="contents" required placeholder="300자 이내로 적어주세요"></textarea>

        <input class="postbtn" type="submit" value="게시">
    </form>
    </div>

<?php
include "database.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $userid = $_POST['userid'];
    $contents = $_POST['contents'];

    // 현재 시간을 등록일로 사용
    $reg_date = time();

    // 데이터베이스에 데이터 삽입
    $sql = "INSERT INTO posts (userid, reg_date, contents) VALUES ('$userid', FROM_UNIXTIME($reg_date), '$contents')";

    $result = mysqli_query($db, $sql);

    if (!$result) {
        die("쿼리 실패: " . mysqli_error($db));
    }
    header('Location: index.php');
    exit;
}
?>

    
</body>
</html>