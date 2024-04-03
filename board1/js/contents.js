function toggleEditOptions(event, postIndex) {
    var editOptions = document.getElementById('post_' + postIndex).querySelector('.edit-options');

    // edit-options 요소가 보이는 상태이면 숨김 처리, 아니면 보이도록 처리
    if (editOptions.style.display === 'block') {
        editOptions.style.display = 'none';
    } else {
        editOptions.style.display = 'block';
    }

    event.stopPropagation();
}

var editButtons = document.querySelectorAll('.post-edit');
editButtons.forEach(function (button) {
    var postIndex = button.parentElement.id.split('_')[1];
    button.addEventListener('click', function (event) {
        toggleEditOptions(event, postIndex);
    });
});

document.addEventListener('click', function (event) {
    var isClickInsideEditOptions = event.target.closest('.edit-options');

    if (!isClickInsideEditOptions) {
        var allEditOptions = document.querySelectorAll('.edit-options');
        allEditOptions.forEach(function (editOptions) {
            editOptions.style.display = 'none';
        });
    }
});

function toggleEditOptions(event, postIndex, sessionUserId) {
    var editOptions = document.getElementById('post_' + postIndex).querySelector('.edit-options');

    if (editOptions.style.display === 'block') {
        editOptions.style.display = 'none';
    } else {
        editOptions.style.display = 'block';
    }

    event.stopPropagation();
}

// 삭제 버튼을 눌렀을 때 이벤트
function deletePost(postId) {
    var confirmed = confirm('정말로 삭제하시겠습니까?');
    if (confirmed) {
        
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'delete_post.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        
        
        var postData = 'post_id=' + postId;
        
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4) {
                if (xhr.status === 200) {
                    var response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        // 성공적으로 삭제되면 적절한 처리 수행
                        alert('게시물이 삭제되었습니다.');
                        window.location.reload();
                    } else {
                        alert('게시물 삭제에 실패했습니다.');
                    }
                } else {
                    alert('서버 오류로 인해 게시물 삭제에 실패했습니다.');
                }
            }
        };
        
        xhr.send(postData);
    }
}