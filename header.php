<?php
require_once 'PHP/levelSystem.php';

$_headerXP = 0;
if (!empty($IDUser) && $IDUser !== -1) {
    $_headerConn = mysqli_connect("localhost", "root", "", "zzz_2");
    if ($_headerConn) {
        $stmt = $_headerConn->prepare("SELECT XP FROM Utente WHERE Id = ? LIMIT 1");
        $stmt->bind_param("i", $IDUser);
        $stmt->execute();
        $stmt->bind_result($_headerXP);
        $stmt->fetch();
        $stmt->close();
        mysqli_close($_headerConn);
    }
}

$levelData = getLevelData((int)$_headerXP);
?>

<header>
    <div class="profile-btn" id="profile-btn" onclick="handleProfileClick()">
        <div class="profile-btn-extra">
            <?php echo '<img class="pfp" src="ASSETS/IMG/Avatars/Avatar' . $IDAvatar . '.png">' ?>
            <div class="user-data">
                <p id="username"><?= htmlspecialchars($Username) ?></p>
                <div class="lvlBar-container"
                     data-percent="<?= $levelData['percent'] ?>"
                     data-xp-current="<?= $levelData['xpThisLevel'] ?>"
                     data-xp-needed="<?= $levelData['xpNeeded'] ?>">
                    <div class="lvlBar-completition"></div>
                </div>
                <div class="user-level-container">
                    <h1 id="user-lvl"><?= $levelData['level'] ?></h1>
                    <p id="level-tag">LEVEL</p>
                </div>
            </div>
        </div>
    </div>

    <h1 class="title-page" id="title" data-text="Home page">Home page</h1>

    <?php include 'PHP/enterBtn.php'; ?>
</header>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const bar = document.querySelector('.lvlBar-container');
        if (!bar) return;

        const fill    = bar.querySelector('.lvlBar-completition');
        const percent = parseFloat(bar.dataset.percent) || 0;
        const current = bar.dataset.xpCurrent;
        const needed  = bar.dataset.xpNeeded;

        requestAnimationFrame(() => {
            fill.style.transition = 'width 1s cubic-bezier(0.4, 0, 0.2, 1)';
            fill.style.width = percent + '%';
        });

        bar.title = `${current} / ${needed} XP`;
    });
</script>