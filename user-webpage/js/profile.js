// 프로필 편집 모달창
function editProfile() {
    openEditProfileModal();
}

function openEditProfileModal() {
    document.getElementById('editProfileModal').style.display = 'flex';
    document.body.classList.add('dark-overlay');
}

function closeEditProfileModal() {
    document.getElementById('editProfileModal').style.display = 'none';
    document.body.classList.remove('dark-overlay');
}

var closeButton = document.querySelector('.close');
    if (closeButton) {
        closeButton.addEventListener('click', closeEditProfileModal);
    }

    window.addEventListener('click', function(event) {
        if (event.target == document.getElementById('editProfileModal')) {
            closeEditProfileModal();
        }
    });

document.getElementById('editProfileButton').addEventListener('click', openEditProfileModal);

document.getElementById('editProfileForm').addEventListener('submit', function (event) {
    event.preventDefault();
    closeEditProfileModal();
});

document.querySelector('.close').addEventListener('click', closeEditProfileModal);

window.addEventListener('click', function(event) {
    if (event.target == document.getElementById('editProfileModal')) {
        closeEditProfileModal();
    }
});



// 이미지 처리 
function previewImage() {
    var imageUrl = document.getElementById('profile_picture').value;

    var imagePreviewContainer = document.getElementById('imagePreviewContainer');
    
    var imagePreview = document.getElementById('imagePreview');

    if (!imageUrl.trim()) {
        imagePreviewContainer.style.display = 'none';
    } else {
        imagePreviewContainer.style.display = 'block';
        imagePreview.src = imageUrl;
    }
}

