<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/common.css">
    <script src="js/contents.js"></script>
    <title>Y-LOG</title>
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

    <!--board view-->
    <?php
    session_start();
    include 'database.php';

    $records_per_page = 10;
    $page = isset($_GET['page']) ? $_GET['page'] : 1;
    $offset = ($page - 1) * $records_per_page;

// 전체 게시물 수
$total_records_query = "SELECT COUNT(*) as total FROM posts";
$total_records_result = mysqli_query($db, $total_records_query);
$total_records = mysqli_fetch_assoc($total_records_result)['total'];

// 전체 페이지 수 계산
$total_pages = ceil($total_records / $records_per_page);

$loggedInUserId = isset($_SESSION['userid']) ? $_SESSION['userid'] : null;

// 게시물 목록
$query = "SELECT * FROM posts ORDER BY reg_date DESC LIMIT $offset, $records_per_page";
$result = mysqli_query($db, $query);

//게시물 10개씩 보여주기
while ($row = mysqli_fetch_assoc($result)) {
    $contents = nl2br($row['contents']);
    $userid = $row['userid'];
    $reg_date = $row['reg_date'];

    // contents 스타일링
    $contents = preg_replace('/@(\w+)/', '<span class="mentioned-user">@$1</span>', $contents);
    ?>

    <div id="post_<?php echo $row['idx']; ?>" class="thread">
        <div class="thread-header">
            <p class="userid"><?php echo $userid; ?></p>
            <p class="reg-date"><?php echo $reg_date; ?></p>
        </div>
        <div class="contents">
            <p class="post-contents"><?php echo $contents; ?></p>
        </div>
        <div class="thread-bottom">
            <img class="comments" src="img/comments.png" alt="댓글">
            <img class="heart" src="img/heart.png" alt="하트">
            <img class="share" src="img/share.png" alt="공유">
            <img class="edit post-edit" src="img/edit.png" alt="편집" onclick="toggleEditOptions(event, <?php echo $row['idx']; ?>)">
            <div class='edit-options'>
            <ul>
                    <?php if ($row['userid'] == $_SESSION['userid']){ ?>
                        <li><a href="modify.php?post_id=<?php echo $row['idx']; ?>"><button class="modify-button">수정하기</button></a></li>
                        <li><button class="delete-button" onclick="deletePost(<?php echo $row['idx']; ?>)">삭제하기</button></li>
                    <?php } else { ?>
                        <li><button class="report-button">신고하기</button></li>
                        <li><button class="block-button">차단하기</button></li>
                    <?php } ?>
                </ul>
        </div>
        </div>
    </div>

    <?php
} // while 루프 종료

// 페이지 링크 표시
echo '<div class="pagination">';
for ($i = 1; $i <= $total_pages; $i++) {
    echo '<a href="?page=' . $i . '">' . $i . '</a>';
}
echo '</div>';
?>







































































    



    
</body>
</html>