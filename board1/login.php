<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/login.css">
    <title>LOGIN</title>
</head>
<body>
<div class="main-container">
        <div class="container">
            <div>
                <img class="main_logo" src="img/logo.png">
            </div>
            <form action="login.php" method="POST">

                <input type="text" id="userid" name="userid" required placeholder="전화번호 또는 사용자 이름"><br>

                <input type="password" id="passwd" name="passwd" required placeholder="비밀번호"><br>
        
                <button type="submit">로그인</button>
            </form>
            <?php
    include "database.php";

    $userid = $_POST['userid'];
    $passwd = $_POST['passwd'];

    $sql = "SELECT idx, passwd FROM user WHERE userid = ?";

    $stmt = mysqli_prepare($db, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $userid);

        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);

        if ($result) {
            $row = mysqli_fetch_assoc($result);

            if ($row && password_verify($passwd, $row['passwd'])) {
                // 비밀번호가 일치하면 세션에 userid와 idx 저장
                $_SESSION['userid'] = $userid;
                $_SESSION['idx'] = $row['idx'];
                header("location: index.php");
            } else {
                echo '<p class="error-message">로그인 오류 또는 잘못된 비밀번호입니다. 다시 확인하세요.</p>';
            }
        } else {
            echo "Error: " . mysqli_error($db);
        }

        mysqli_stmt_close($stmt);
    } else {
        echo "Error: " . mysqli_error($db);
    }

    mysqli_close($db);
    ?>

    </div>

        <div class="signup-container">계정이 없으신가요? <a href="signup.html">가입하기</a>
        </div>
    </div>

</body>
</html>