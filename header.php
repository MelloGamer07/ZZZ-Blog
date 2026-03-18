<header>
    <div class="back-btn" onclick="window.location.href = 'loginIndex.php';">
        <img class="back-btn-img" src="ASSETS/IMG/UI/BackButton.png">
    </div>

    <div class="profile-btn" id="profile-btn" onclick="handleProfileClick()">
        <div class="profile-btn-extra">
            <?php echo '<img class="pfp" src="ASSETS/IMG/Avatars/Avatar' . $IDAvatar . '.png">' ?>
            <div class="user-data">
                <p id="username"><?= $Username ?></p>
                <div class="lvlBar-container">
                    <div class="lvlBar-completition"></div>
                </div>
                <div class="user-level-container">
                    <h1 id="user-lvl">0</h1>
                    <p id="level-tag">LEVEL</p>
                </div>
            </div>
        </div>
    </div>

    <h1 class="title-page" id="title" data-text="Home page">Home page</h1>

    <?php include 'PHP/enterBtn.php'; ?>
</header>

