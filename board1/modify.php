<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/common.css">
    <link rel="stylesheet" href="css/contents.css">
    <script src="js/profile.js"></script>
    <title>게시물 수정</title>
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


    <!--modify from-->
<div class="contents">

<?php
include 'database.php';

$post_id = isset($_GET['post_id']) ? $_GET['post_id'] : null;
if ($post_id === null || !is_numeric($post_id)) {
    echo "유효하지 않은 포스트입니다.";
    exit;
}
include 'database.php';

$sql = "SELECT idx, userid, contents FROM posts WHERE idx = $post_id";
$result = mysqli_query($db, $sql);

// 결과 확인
if (!$result) {
    echo "데이터베이스 조회 오류: " . mysqli_error($db);
    exit;
}

// 데이터 가져오기
$row = mysqli_fetch_assoc($result);

?>

<form method="post" action="">

<input type="hidden" name="idx" value="<?php echo $row['idx']; ?>">
<input type="text" name="userid" required value="<?php echo $row['userid']; ?>">
<textarea name="contents" required placeholder="300자 이내로 적어주세요"><?php echo $row['contents']; ?></textarea>
<input class="postbtn" type="submit" value="수정">
</form>

<?
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idx = isset($_POST['idx']) ? $_POST['idx'] : null;
    $userid = $_POST['userid'];
    $contents = $_POST['contents'];

    if ($idx !== null) {
        $sql = "UPDATE posts SET userid='$userid', contents='$contents' WHERE idx=$idx";
        $result = mysqli_query($db, $sql);

        if ($result) {

            echo '<script>
                    setTimeout(function() {
                        window.history.go(-2);
                    }); 
                  </script>';
        } else {
            echo "게시물 수정에 실패했습니다. " . mysqli_error($db);
        }
    } else {
        echo "게시물 수정에 실패했습니다. 글 번호가 올바르지 않습니다.";
    }
}
?>

</div>
</body>
</html>
