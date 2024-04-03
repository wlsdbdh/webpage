<?php
include 'database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postId = $_POST['post_id'];

    $delete_query = "DELETE FROM posts WHERE idx = $postId";
    $delete_result = mysqli_query($db, $delete_query);

    if ($delete_result) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
} else {
    echo json_encode(['error' => 'Invalid request method']);
}
?>