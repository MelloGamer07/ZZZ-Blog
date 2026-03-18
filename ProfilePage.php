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

    // Determine which profile to show (?id= or fall back to logged-in user)
    $profileId = isset($_GET['id']) ? intval($_GET['id']) : $IDUser;

    if ($profileId === -1) {
        die("No user specified.");
    }

    // Fetch user info
    $userResult = mysqli_query($conn, "
        SELECT Id, Username, Avatar, Ruolo, DataCreazione 
        FROM Utente 
        WHERE Id = $profileId
    ");
    $utente = mysqli_fetch_assoc($userResult);

    if (!$utente) {
        die("User not found.");
    }

    // Fetch user's published articles
    $articlesResult = mysqli_query($conn, "
        SELECT Id, Title, Img 
        FROM Articolo 
        WHERE IdUtente = $profileId AND Pubblicato = TRUE 
        ORDER BY DataCreazione DESC
        LIMIT 6
    ");

    // Avatar path
    $avatarSrc = "ASSETS/IMG/Avatars/Avatar" . $utente['Avatar'] . ".png";
    $avatarFallback = "ASSETS/IMG/Avatars/Avatar0.png";
    if (!file_exists(__DIR__ . '/' . $avatarSrc)) {
        $avatarSrc = $avatarFallback;
    }

    $isOwnProfile = ($IDUser === intval($utente['Id']));
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
    </header>

    <!-- Search Modal -->
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

    <div class="profile-container">
        <div class="profile-header">
            <div class="UID">UID: <?php echo $utente['Id']; ?></div>
            <?php if ($isOwnProfile): ?>
                <div class="edit-info-btn">Edit Info</div>
            <?php else: ?>
                <div class="follow-btn" id="followBtn" onclick="toggleFollow(<?php echo $utente['Id']; ?>);">
                    Follow
                </div>
            <?php endif; ?>
        </div>

        <div class="profile-body">
            <div class="profile-main">
                <div class="profile-avatar">
                    <img class="pfp" src="<?php echo $avatarSrc; ?>" alt="Avatar">
                </div>
                <div class="profile-username"><?php echo $utente['Username']; ?></div>
                <div class="profile-desc"><?php echo $utente['Username']; ?></div>
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
        // --- Set URL hash to current profile ID on load ---
        const currentProfileId = <?php echo $utente['Id']; ?>;
        window.location.hash = '#user/' + currentProfileId;

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
            }, 250); // debounce 250ms
        }

        function goToProfile(userId) {
            window.location.href = 'ProfilePage.php?id=' + userId + '#user/' + userId;
        }

        // --- Follow Button (stub — wire up to your follow system) ---
        let isFollowing = false;
        function toggleFollow(userId) {
            const btn = document.getElementById('followBtn');
            isFollowing = !isFollowing;
            btn.textContent = isFollowing ? 'Unfollow' : 'Follow';
            btn.classList.toggle('following', isFollowing);
        }
    </script>

</body>
</html>
