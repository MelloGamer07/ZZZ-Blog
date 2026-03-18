// --- Set URL hash to current profile ID on load ---
const currentProfileId = <?php echo $utente['Id']; ?>;
window.location.hash = '#user/' + currentProfileId;

// --- XP bar animation ---
document.addEventListener('DOMContentLoaded', () => {
    const bar = document.querySelector('.profile-lvlBar-container');
    if (!bar) return;

    const fill    = bar.querySelector('.profile-lvlBar-completition');
    const percent = parseFloat(bar.dataset.percent) || 0;
    const current = bar.dataset.xpCurrent;
    const needed  = bar.dataset.xpNeeded;

    requestAnimationFrame(() => {
        fill.style.transition = 'width 1s cubic-bezier(0.4, 0, 0.2, 1)';
        fill.style.width = percent + '%';
    });

    bar.title = `${current} / ${needed} XP`;
});

// --- Search Modal ---
function openSearchModal() {
    document.getElementById('searchModalOverlay').classList.add('active');
    setTimeout(() => document.getElementById('searchInput').focus(), 50);
}

function closeSearchModal(e) {
    if (e.target === document.getElementById('searchModalOverlay')) {
        document.getElementById('searchModalOverlay').classList.remove('active');
        document.getElementById('searchInput').value = '';
        document.getElementById('searchResults').innerHTML = '<p class="search-placeholder">Start typing to search...</p>';
    }
}

document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
        document.getElementById('searchModalOverlay').classList.remove('active');
        closeAvatarPicker();
        closeEditModal();
    }
});

// --- Live Search ---
let searchTimeout;
function searchUsers(query) {
    clearTimeout(searchTimeout);
    const resultsDiv = document.getElementById('searchResults');

    if (query.trim().length === 0) {
        resultsDiv.innerHTML = '<p class="search-placeholder">Start typing to search...</p>';
        return;
    }

    resultsDiv.innerHTML = '<p class="search-placeholder">Searching...</p>';

    searchTimeout = setTimeout(() => {
        fetch('PHP/search_users.php?q=' + encodeURIComponent(query))
            .then(res => res.json())
            .then(users => {
                if (users.length === 0) {
                    resultsDiv.innerHTML = '<p class="search-placeholder">No users found.</p>';
                    return;
                }

                resultsDiv.innerHTML = '';
                users.forEach(user => {
                    const avatarSrc = 'ASSETS/IMG/Avatars/Avatar' + user.Avatar + '.png';
                    const div = document.createElement('div');
                    div.className = 'search-result-item';
                    div.innerHTML = `
                        <img src="${avatarSrc}" onerror="this.src='ASSETS/IMG/Avatars/Avatar0.png'" alt="">
                        <span>${user.Username}</span>
                    `;
                    div.onclick = () => goToProfile(user.Id);
                    resultsDiv.appendChild(div);
                });
            })
            .catch(() => {
                resultsDiv.innerHTML = '<p class="search-placeholder">Error searching.</p>';
            });
    }, 250);
}

function goToProfile(userId) {
    window.location.href = 'ProfilePage.php?id=' + userId + '#user/' + userId;
}

// --- Follow Button ---
let isFollowing = <?php echo $isFollowing ? 'true' : 'false'; ?>;

function toggleFollow(userId) {
    const btn = document.getElementById('followBtn');
    const text = document.getElementById('follow-btn-text');

    isFollowing = !isFollowing;
    text.textContent = isFollowing ? 'Unfollow' : 'Follow';
    btn.classList.toggle('following', isFollowing);

    fetch('PHP/isFollow.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'user_id=' + userId
    })
    .then(res => res.json())
    .then(data => {
        if (data.error) {
            isFollowing = !isFollowing;
            text.textContent = isFollowing ? 'Unfollow' : 'Follow';
            btn.classList.toggle('following', isFollowing);
            alert(data.error);
            return;
        }
        isFollowing = data.following;
        text.textContent = isFollowing ? 'Unfollow' : 'Follow';
        btn.classList.toggle('following', isFollowing);
    })
    .catch(() => {
        isFollowing = !isFollowing;
        text.textContent = isFollowing ? 'Unfollow' : 'Follow';
        btn.classList.toggle('following', isFollowing);
    });
}

// ================================================================
//  EDIT INFO MODAL
// ================================================================

// Track the avatar index the user has selected (may differ from saved)
let pendingAvatarIndex = <?php echo intval($utente['Avatar']); ?>;

function openEditModal() {
    // Reset feedback
    document.getElementById('editFeedback').textContent = '';
    document.getElementById('editFeedback').className = 'edit-feedback';

    // Sync preview to current saved avatar
    pendingAvatarIndex = <?php echo intval($utente['Avatar']); ?>;
    document.getElementById('editAvatarPreview').src =
        'ASSETS/IMG/Avatars/Avatar' + pendingAvatarIndex + '.png';

    document.getElementById('editModalOverlay').classList.add('active');
}

function closeEditModal() {
    document.getElementById('editModalOverlay').classList.remove('active');
}

// Close edit modal when clicking outside its box
document.getElementById('editModalOverlay').addEventListener('click', function(e) {
    if (e.target === this) closeEditModal();
});

// Character counter helper
function updateCharCount(inputId, counterId, max) {
    const len = document.getElementById(inputId).value.length;
    const el  = document.getElementById(counterId);
    el.textContent = len + '/' + max;
    el.className = 'edit-char-count' +
        (len >= max ? ' over' : len >= max * 0.85 ? ' warn' : '');
}

// Save profile via AJAX
function saveProfile() {
    const username = document.getElementById('editUsername').value.trim();
    const desc     = document.getElementById('editDesc').value.trim();
    const feedback = document.getElementById('editFeedback');
    const saveBtn  = document.getElementById('editSaveBtn');

    if (username.length === 0) {
        feedback.textContent = 'Username cannot be empty.';
        feedback.className = 'edit-feedback error';
        return;
    }

    saveBtn.disabled = true;
    feedback.textContent = 'Saving…';
    feedback.className = 'edit-feedback';

    const body = new URLSearchParams({
        username:   username,
        desc:       desc,
        avatar_id:  pendingAvatarIndex
    });

    fetch('PHP/updateProfile.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: body.toString()
    })
    .then(res => res.json())
    .then(data => {
        saveBtn.disabled = false;

        if (data.error) {
            feedback.textContent = data.error;
            feedback.className = 'edit-feedback error';
            return;
        }

        // Update page live
        const newAvatarSrc = 'ASSETS/IMG/Avatars/Avatar' + pendingAvatarIndex + '.png';
        document.getElementById('profilePfp').src      = newAvatarSrc;
        document.getElementById('profileUsername').textContent = username;
        document.getElementById('profileDesc').textContent     = desc || username;

        feedback.textContent = '✓ Profile updated!';
        feedback.className = 'edit-feedback';

        setTimeout(closeEditModal, 900);
    })
    .catch(() => {
        saveBtn.disabled = false;
        feedback.textContent = 'Network error. Please try again.';
        feedback.className = 'edit-feedback error';
    });
}

// ================================================================
//  AVATAR PICKER MODAL
// ================================================================

const AVATAR_COUNT = 58; // Avatar0 … Avatar57

function buildAvatarGrid() {
    const grid = document.getElementById('avatarGrid');
    if (grid.childElementCount > 0) return; // already built

    for (let i = 0; i < AVATAR_COUNT; i++) {
        const item = document.createElement('div');
        item.className = 'avatar-grid-item' + (i === pendingAvatarIndex ? ' selected' : '');
        item.dataset.index = i;
        item.innerHTML = `
            <div class="avatar-circle">
                <img src="ASSETS/IMG/Avatars/Avatar${i}.png"
                        onerror="this.src='ASSETS/IMG/Avatars/Avatar0.png'"
                        alt="Avatar ${i}">
            </div>`;
        item.addEventListener('click', () => selectAvatar(i));
        grid.appendChild(item);
    }
}

function openAvatarPicker() {
    buildAvatarGrid();

    // Highlight current pending selection
    document.querySelectorAll('.avatar-grid-item').forEach(el => {
        el.classList.toggle('selected', parseInt(el.dataset.index) === pendingAvatarIndex);
    });

    document.getElementById('avatarPickerOverlay').classList.add('active');
}

function closeAvatarPicker() {
    document.getElementById('avatarPickerOverlay').classList.remove('active');
}

// Close avatar picker when clicking outside
document.getElementById('avatarPickerOverlay').addEventListener('click', function(e) {
    if (e.target === this) closeAvatarPicker();
});

function selectAvatar(index) {
    pendingAvatarIndex = index;

    // Update selection highlight in grid
    document.querySelectorAll('.avatar-grid-item').forEach(el => {
        el.classList.toggle('selected', parseInt(el.dataset.index) === index);
    });

    // Update the preview back in the edit modal
    const newSrc = 'ASSETS/IMG/Avatars/Avatar' + index + '.png';
    document.getElementById('editAvatarPreview').src = newSrc;

    // Short delay so user sees the selection, then go back
    setTimeout(closeAvatarPicker, 180);
}