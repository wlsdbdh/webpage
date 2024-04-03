<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/common.css">
    <link rel="stylesheet" href="css/profile.css">
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

        function logout() {
        window.location.href = 'logout.php';
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

    <?php
    session_start();
    include 'database.php';

    if (!isset($_SESSION['userid'])) {
        header("Location: login.html");
        exit();
    }

    $userid = $_SESSION['userid'];

    $sql = "SELECT * FROM user WHERE userid = ?";
    $stmt = mysqli_prepare($db, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $userid);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);

        if ($result) {
            $row = mysqli_fetch_assoc($result);

            $userid = $row['userid'];
            $username = $row['user_name'];
            $bio = $row['bio'];
        } else {
            echo "Error: " . mysqli_error($db);
        }

        mysqli_stmt_close($stmt);
    } else {
        echo "Error: " . mysqli_error($db);
    }
    ?>

    <header>
        <div class="profile-header">
            <img src="img/profile_picture.jpg" alt="프로필 사진">
            <div class="user-info">
                <p class="user_id"><?php echo $userid; ?></p>
                <p><button id="editProfileButton" class="edit-profile-btn" onclick="openEditProfileModal()">프로필 편집</button>
                <button class="logout-btn" onclick="logout()">로그아웃</button></p>
                <p class='user_name'><?php echo $username; ?></p>
                <p class='bio'><?php echo $bio; ?></p>
            </div>
        </div>
    </header>
    <div class="contents-bar">
        <p><b>게시물</b></p>
        <?php
        
        $query = "SELECT COUNT(*) as post_count FROM posts WHERE userid = '$userid'";
        $result = mysqli_query($db, $query);

        if ($result) {
            $row = mysqli_fetch_assoc($result);
            $postCount = $row['post_count'];
            echo "<p>$postCount</p>";

        } else {
             echo "<p>쿼리 오류: " . mysqli_error($db) . "</p>";
        }
        ?>
    </div>
    

<!-- 프로필 편집 모달 -->
<div id="editProfileModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeEditProfileModal()">&times;</span>
        <form id="editProfileForm" action="profile.php" method="POST">
            <input type="text" id="userid" name="userid" required placeholder="사용자 이름">

            <input type="text" id="user_name" name="user_name" required placeholder="성명">

            <textarea id="bio" name="bio" placeholder="상태메세지"></textarea>

            <input type="text" id="profile_picture" name="profile_picture" oninput="previewImage()">

            <input type="submit" value="저장">
        </form>

        <div id="imagePreviewContainer">
        <p>프로필 사진 미리보기:</p>
        <img id="imagePreview" src="" alt="프로필 사진">
        </div>

        <?php
        include "database.php";

        if (isset($_SESSION['idx'])) {
            $idx = $_SESSION['idx'];
        
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $userid = mysqli_real_escape_string($db, $_POST['userid']);
                $user_name = mysqli_real_escape_string($db, $_POST['user_name']);
                $bio = mysqli_real_escape_string($db, $_POST['bio']);
        
                $sql = "UPDATE user SET userid='$userid', user_name='$user_name', bio='$bio' WHERE idx=$idx";
        
                $res = mysqli_query($db, $sql);
        
                if ($res) {
                    echo "<p>수정되었습니다.</p>";
                } else {
                    echo "<p>수정 오류입니다.</p>";
                }
            }
        } else {
        }
        ?>
    </div>
</div>



<!--board view-->
    <?php
    include 'database.php';

    $records_per_page = 10;
    $page = isset($_GET['page']) ? $_GET['page'] : 1;
    $offset = ($page - 1) * $records_per_page;

    $total_records_query = "SELECT COUNT(*) as total FROM posts WHERE userid = '$userid'";
    $total_records_result = mysqli_query($db, $total_records_query);
    $total_records = mysqli_fetch_assoc($total_records_result)['total'];
    
    // 전체 페이지 수 계산
    $total_pages = ceil($total_records / $records_per_page);

    // 게시물 목록
    $query = "SELECT * FROM posts WHERE userid = '$userid' ORDER BY reg_date DESC LIMIT $offset, $records_per_page";
    $result = mysqli_query($db, $query);

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
        <img class = "comments" src="img/comments.png" alt="댓글">
        <img class = "heart" src="img/heart.png" alt="하트">
        <img class = "share" src="img/share.png" alt="공유">
        <img class="edit post-edit" src="img/edit.png" alt="편집" onclick="toggleEditOptions(event, <?php echo $row['idx']; ?>)">
            <div class='edit-options'>
            <ul>
                    <?php
                    if ($row['userid'] == $_SESSION['userid']){ ?>
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
  
<script src="js/profile.js"></script>
</body>
</html>