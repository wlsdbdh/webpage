<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/login.css">
    <script src="js/signup.js"></script>
    <title>회원가입</title>
</head>
<body>

<?php
include 'database.php'; 

$userid = $_POST['userid'];
$passwd = password_hash($_POST['passwd'], PASSWORD_DEFAULT); 
$username = $_POST['username'];
$phone_num = $_POST['phone_num'];
$idx = $_POST['idx'];

$sql = "INSERT INTO user (userid, passwd, user_name, phone_num) VALUES ('$userid', '$passwd', '$username', '$phone_num')";

if (mysqli_query($db, $sql)) {
   header("Location: index.php");
   exit();
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($db);
}

// 연결 종료
mysqli_close($db);
?>

</body>
</html>
