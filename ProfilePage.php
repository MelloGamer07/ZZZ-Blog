<?php
    session_start();

    $Username = $_SESSION['Username'] ?? "Guest";
    $IDUser = intval($_SESSION['IdUsername'] ?? -1);
    $_SESSION['IdUsername'] = $IDUser;
    $IDAvatar = intval($_SESSION['IdAvatar'] ?? 0);

    $hostname = "localhost";
    $username = "root";
    $password = "";
    $database = "zzz_2";

    $conn = mysqli_connect($hostname, $username, $password, $database);
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $profileId = isset($_GET['id']) ? intval($_GET['id']) : $IDUser;

    if ($profileId === -1) {
        die("No user specified.");
    }

    $userResult = mysqli_query($conn, "
        SELECT Id, Username, Avatar, Ruolo, DataCreazione, XP, Descrizione
        FROM Utente 
        WHERE Id = $profileId
    ");
    $utente = mysqli_fetch_assoc($userResult);

    if (!$utente) {
        die("User not found.");
    }

    require_once 'PHP/levelSystem.php';
    $levelData = getLevelData((int)($utente['XP'] ?? 0));

    $isOwnProfile = ($IDUser === intval($utente['Id']));

    $isFollowing = false;
    if (!$isOwnProfile && $IDUser !== -1) {
        $followCheck = $conn->prepare("SELECT 1 FROM Follow WHERE IdUtente = ? AND IDUtenteFollow = ?");
        $followCheck->bind_param("ii", $IDUser, $profileId);
        $followCheck->execute();
        $followCheck->store_result();
        $isFollowing = $followCheck->num_rows > 0;
        $followCheck->close();
    }

    $articlesResult = mysqli_query($conn, "
        SELECT Id, Title, Img 
        FROM Articolo 
        WHERE IdUtente = $profileId AND Pubblicato = TRUE 
        ORDER BY DataCreazione DESC
        LIMIT 6
    ");

    $avatarSrc = "ASSETS/IMG/Avatars/Avatar" . $utente['Avatar'] . ".png";
    $avatarFallback = "ASSETS/IMG/Avatars/Avatar0.png";
    if (!file_exists(__DIR__ . '/' . $avatarSrc)) {
        $avatarSrc = $avatarFallback;
    }

    $profileDesc = htmlspecialchars($utente['Descrizione'] ?? '');
?>

<html>
<head>
    <link rel="stylesheet" href="CSS/ProfilePage.css">
</head>
<body>

    <header>
        <div class="back-btn" onclick="window.location.href = 'home.php';">
            <img class="back-btn-img" src="ASSETS/IMG/UI/BackButton.png" alt="Back">
        </div>
        <div class="search-btn" onclick="openSearchModal();">
            <p>Search Users</p>
        </div>
        <div class="friend-list-btn" id="friendList">
            <p>Friend List</p>
        </div>
        <?php if ($isOwnProfile): ?>
        <div class="logout-btn" onclick="openLogoutModal();">
            <p>Logout</p>
        </div>
        <?php endif; ?>
    </header>

    <!-- Logout Confirmation Modal -->
    <div class="logout-modal-overlay" id="logoutModalOverlay">
        <div class="logout-modal">
            <h2 class="logout-modal-title">Log Out?</h2>
            <p class="logout-modal-body">Are you sure you want to log out?</p>
            <div class="logout-modal-actions">
                <button class="logout-cancel-btn" onclick="closeLogoutModal();">Cancel</button>
                <button class="logout-confirm-btn" onclick="window.location.href='PHP/logout.php';">Log Out</button>
            </div>
        </div>
    </div>

    <div class="modal-overlay" id="searchModalOverlay" onclick="closeSearchModal(event);">
        <div class="search-modal">
            <input 
                class="search-input" 
                id="searchInput" 
                type="text" 
                placeholder="Search users..." 
                oninput="searchUsers(this.value);"
                autocomplete="off"
            >
            <div class="search-results" id="searchResults">
                <p class="search-placeholder">Start typing to search...</p>
            </div>
        </div>
    </div>

    <div class="edit-modal-overlay" id="editModalOverlay">
        <div class="edit-modal">
            <h2 class="edit-modal-title">Edit Profile</h2>

            <div class="edit-avatar-row">
                <img class="edit-avatar-preview" id="editAvatarPreview"
                     src="<?php echo $avatarSrc; ?>"
                     alt="Avatar preview">
                <button class="edit-change-avatar-btn" onclick="openAvatarPicker();">
                    Change Avatar
                </button>
            </div>

            <div class="edit-field">
                <label for="editUsername">Username</label>
                <input type="text"
                       id="editUsername"
                       maxlength="30"
                       value="<?php echo htmlspecialchars($utente['Username']); ?>"
                       oninput="updateCharCount('editUsername','usernameCount',30);"
                       placeholder="Your username">
                <span class="edit-char-count" id="usernameCount">
                    <?php echo mb_strlen($utente['Username']); ?>/30
                </span>
            </div>

            <div class="edit-field">
                <label for="editDesc">Description</label>
                <textarea id="editDesc"
                          maxlength="160"
                          oninput="updateCharCount('editDesc','descCount',160);"
                          placeholder="Tell the world about yourself..."><?php echo $profileDesc; ?></textarea>
                <span class="edit-char-count" id="descCount">
                    <?php echo mb_strlen($utente['Descrizione'] ?? ''); ?>/160
                </span>
            </div>

            <div class="edit-feedback" id="editFeedback"></div>

            <div class="edit-modal-actions">
                <button class="edit-cancel-btn" onclick="closeEditModal();">Cancel</button>
                <button class="edit-save-btn" id="editSaveBtn" onclick="saveProfile();">Save Changes</button>
            </div>
        </div>
    </div>

    <div class="avatar-picker-overlay" id="avatarPickerOverlay">
        <div class="avatar-picker-modal">
            <div class="avatar-picker-header">
                <h3 class="avatar-picker-title">Choose Avatar</h3>
                <button class="avatar-picker-back" onclick="closeAvatarPicker();">← Back</button>
            </div>
            <div class="avatar-grid" id="avatarGrid">
            </div>
        </div>
    </div>

    <div class="profile-container">
        <div class="profile-header">
            <div class="UID">UID: <?php echo $utente['Id']; ?></div>
            <?php if ($isOwnProfile): ?>
                <button class="edit-info-btn" onclick="openEditModal();">Edit Info</button>
            <?php else: ?>
                <button class="follow-btn <?php echo $isFollowing ? 'following' : ''; ?>" 
                        id="followBtn" 
                        onclick="toggleFollow(<?php echo $utente['Id']; ?>);">
                    <p id="follow-btn-text"><?php echo $isFollowing ? 'Unfollow' : 'Follow'; ?></p>
                </button>
            <?php endif; ?>
        </div>

        <div class="profile-body">
            <div class="profile-main">
                <div class="profile-avatar">
                    <img class="pfp" id="profilePfp" src="<?php echo $avatarSrc; ?>" alt="Avatar">
                </div>
                <div class="profile-username" id="profileUsername"><?php echo htmlspecialchars($utente['Username']); ?></div>

                <div class="profile-level-container">
                    <div class="profile-lvlBar-container"
                         data-percent="<?= $levelData['percent'] ?>"
                         data-xp-current="<?= $levelData['xpThisLevel'] ?>"
                         data-xp-needed="<?= $levelData['xpNeeded'] ?>">
                        <div class="profile-lvlBar-completition"></div>
                    </div>
                    <div class="profile-level-badge">
                        <h2 class="profile-user-lvl"><?= $levelData['level'] ?></h2>
                        <p class="profile-level-tag">LEVEL</p>
                    </div>
                </div>

                <div class="profile-desc" id="profileDesc"><?php echo $profileDesc ?: htmlspecialchars($utente['Username']); ?></div>
            </div>

            <div class="profile-user-posts">
            <?php
                if (mysqli_num_rows($articlesResult) === 0) {
                    echo '<p class="no-posts">No posts yet.</p>';
                } else {
                    while ($row = mysqli_fetch_assoc($articlesResult)) {
                        $imgPath = __DIR__ . '/' . $row['Img'];
                        if (empty($row['Img']) || !file_exists($imgPath)) {
                            $row['Img'] = 'ASSETS/IMG/UI/plus.png';
                        }
                        $title = mb_strimwidth($row['Title'], 0, 30, '...');
                        echo '
                        <div class="post-container" onclick="window.location.href=\'home.php#InterKnot/idArticle=' . $row['Id'] . '\';">
                            <img class="img" src="' . $row['Img'] . '" alt="">
                            <h2 class="post-title">' . $title . '</h2>
                        </div>
                        ';
                    }
                }
                mysqli_close($conn);
            ?>
            </div>
        </div>
    </div>

    <script>
        const currentProfileId = <?php echo $utente['Id']; ?>;
        window.location.hash = '#user/' + currentProfileId;

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

        // --- Logout Modal ---
        function openLogoutModal() {
            document.getElementById('logoutModalOverlay').classList.add('active');
        }

        function closeLogoutModal() {
            document.getElementById('logoutModalOverlay').classList.remove('active');
        }

        document.getElementById('logoutModalOverlay').addEventListener('click', function(e) {
            if (e.target === this) closeLogoutModal();
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
                closeLogoutModal();
                document.getElementById('searchModalOverlay').classList.remove('active');
                closeAvatarPicker();
                closeEditModal();
            }
        });

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

        let pendingAvatarIndex = <?php echo intval($utente['Avatar']); ?>;

        function openEditModal() {
            document.getElementById('editFeedback').textContent = '';
            document.getElementById('editFeedback').className = 'edit-feedback';

            pendingAvatarIndex = <?php echo intval($utente['Avatar']); ?>;
            document.getElementById('editAvatarPreview').src =
                'ASSETS/IMG/Avatars/Avatar' + pendingAvatarIndex + '.png';

            document.getElementById('editModalOverlay').classList.add('active');
        }

        function closeEditModal() {
            document.getElementById('editModalOverlay').classList.remove('active');
        }

        document.getElementById('editModalOverlay').addEventListener('click', function(e) {
            if (e.target === this) closeEditModal();
        });

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

        const AVATAR_COUNT = 58;

        function buildAvatarGrid() {
            const grid = document.getElementById('avatarGrid');
            if (grid.childElementCount > 0) return;

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
            document.getElementById('avatarPickerOverlay').classList.remove('active');
        }

        document.getElementById('avatarPickerOverlay').addEventListener('click', function(e) {
            if (e.target === this) closeAvatarPicker();
        });

        function selectAvatar(index) {
            pendingAvatarIndex = index;

            document.querySelectorAll('.avatar-grid-item').forEach(el => {
                el.classList.toggle('selected', parseInt(el.dataset.index) === index);
            });

            const newSrc = 'ASSETS/IMG/Avatars/Avatar' + index + '.png';
            document.getElementById('editAvatarPreview').src = newSrc;

            setTimeout(closeAvatarPicker, 180);
        }
    </script>

</body>
</html>