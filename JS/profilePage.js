// All PHP variables are injected by ProfilePage.php via window.PAGE_DATA

document.addEventListener('DOMContentLoaded', () => {
    // --- XP Bar ---
    const bar = document.querySelector('.profile-lvlBar-container');
    if (bar) {
        const fill    = bar.querySelector('.profile-lvlBar-completition');
        const percent = parseFloat(bar.dataset.percent) || 0;
        const current = bar.dataset.xpCurrent;
        const needed  = bar.dataset.xpNeeded;

        requestAnimationFrame(() => {
            fill.style.transition = 'width 1s cubic-bezier(0.4, 0, 0.2, 1)';
            fill.style.width = percent + '%';
        });

        bar.title = `${current} / ${needed} XP`;
    }

    // --- Set URL hash ---
    window.location.hash = '#user/' + window.PAGE_DATA.profileId;

    // --- Logout Modal ---
    const logoutOverlay = document.getElementById('logoutModalOverlay');
    if (logoutOverlay) {
        logoutOverlay.addEventListener('click', function(e) {
            if (e.target === this) closeLogoutModal();
        });
    }

    // --- Edit Modal ---
    const editOverlay = document.getElementById('editModalOverlay');
    if (editOverlay) {
        editOverlay.addEventListener('click', function(e) {
            if (e.target === this) closeEditModal();
        });
    }

    // --- Avatar Picker ---
    const avatarOverlay = document.getElementById('avatarPickerOverlay');
    if (avatarOverlay) {
        avatarOverlay.addEventListener('click', function(e) {
            if (e.target === this) closeAvatarPicker();
        });
    }

    // --- Escape key closes all modals ---
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            closeLogoutModal();
            const searchOverlay = document.getElementById('searchModalOverlay');
            if (searchOverlay) searchOverlay.classList.remove('active');
            closeAvatarPicker();
            closeEditModal();
        }
    });
});

// ============================================================
// Logout Modal
// ============================================================
function openLogoutModal() {
    document.getElementById('logoutModalOverlay').classList.add('active');
}

function closeLogoutModal() {
    document.getElementById('logoutModalOverlay').classList.remove('active');
}

// ============================================================
// Search Modal
// ============================================================
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

// ============================================================
// Follow Button
// ============================================================
let isFollowing = window.PAGE_DATA?.isFollowing ?? false;

function toggleFollow(userId) {
    const btn  = document.getElementById('followBtn');
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

// ============================================================
// Edit Profile Modal
// ============================================================
let pendingAvatarIndex = window.PAGE_DATA?.avatarIndex ?? 0;

function openEditModal() {
    document.getElementById('editFeedback').textContent = '';
    document.getElementById('editFeedback').className = 'edit-feedback';

    pendingAvatarIndex = window.PAGE_DATA.avatarIndex;
    document.getElementById('editAvatarPreview').src =
        'ASSETS/IMG/Avatars/Avatar' + pendingAvatarIndex + '.png';

    document.getElementById('editModalOverlay').classList.add('active');
}

function closeEditModal() {
    const el = document.getElementById('editModalOverlay');
    if (el) el.classList.remove('active');
}

function updateCharCount(inputId, counterId, max) {
    const len = document.getElementById(inputId).value.length;
    const el  = document.getElementById(counterId);
    el.textContent = len + '/' + max;
    el.className = 'edit-char-count' +
        (len >= max ? ' over' : len >= max * 0.85 ? ' warn' : '');
}

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
        username:  username,
        desc:      desc,
        avatar_id: pendingAvatarIndex
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

        const newAvatarSrc = 'ASSETS/IMG/Avatars/Avatar' + pendingAvatarIndex + '.png';
        document.getElementById('profilePfp').src             = newAvatarSrc;
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

// ============================================================
// Avatar Picker
// ============================================================
const AVATAR_COUNT = 58;

function buildAvatarGrid() {
    const grid = document.getElementById('avatarGrid');
    if (!grid || grid.childElementCount > 0) return;

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
    document.querySelectorAll('.avatar-grid-item').forEach(el => {
        el.classList.toggle('selected', parseInt(el.dataset.index) === pendingAvatarIndex);
    });
    document.getElementById('avatarPickerOverlay').classList.add('active');
}

function closeAvatarPicker() {
    const el = document.getElementById('avatarPickerOverlay');
    if (el) el.classList.remove('active');
}

function selectAvatar(index) {
    pendingAvatarIndex = index;
    document.querySelectorAll('.avatar-grid-item').forEach(el => {
        el.classList.toggle('selected', parseInt(el.dataset.index) === index);
    });
    document.getElementById('editAvatarPreview').src = 'ASSETS/IMG/Avatars/Avatar' + index + '.png';
    setTimeout(closeAvatarPicker, 180);
}