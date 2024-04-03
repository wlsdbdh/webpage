<?php
include 'database.php';
$userid = $_REQUEST['userid'];
$sql = "select * from user where userid = $userid";
$res = mysqli_query($db, $sql);
if (mysqli_num_rows($res) > 0){
    echo "0";
} else {
    echo "1";
}
?>

