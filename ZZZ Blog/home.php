<?php
    session_start();
    $Username = $_SESSION['Username'] ?? "Guest";
    $IDUsername = $_SESSION['IdUsername'];
    if (empty($IDUsername)) {
        echo '<script>alert("Bruh")</script>';
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>ZZZ Home Page</title>
  <link rel="icon" type="image/x-icon" href="ASSETS/IMG/dumbJaneDoe.png">
  <link rel="stylesheet" href="CSS/home.css">
</head>
<body>
    <video autoplay muted loop id="DynamicWallpaper">
       <source src="ASSETS/DynamicWallpapers/DynamicWallpaper1.mp4" type="video/mp4">
    </video>

    <div id="LoadingScreen"></div>

    <div class="page-wrapper">

      <?php include 'header.php' ?>

      <main class="content" id="posts">
        <?php include 'main.php' ?>
      </main>

    <div class="post-modal-container" id="modal-post">
        <div class="post-modal">

            <div class="post-header">

                <img id="post-user-pfp" src="ASSETS/IMG/Avatars/Avatar34.png">
                <h2 id="post-user-name">Username 7</h2>
                <div class="post-likes"> <p>❤️ <span id="num-likes">69420</span></p> </div>
                <div class="post-exit-button" onclick="closeModal()"><img class="post-exit-button-img"  src="ASSETS/IMG/UI/CancelIMG.png"></div>
            </div>

            <div class="post-main">

                <div class="post-image-container"><img id="post-image" src="ASSETS/IMG/LoadingScreens/7.jpg"></div>

                <div class="post-data">
                    <div id="post-content">
                        <h3 id="post-title">Why Progress Feels Meaningful</h3>
                        <p id="post-text">Advancement in Zenless Zone Zero is structured around relationships as much as stats. Unlocking abilities often coincides with learning more about an agent’s past, motivations, or fears. This intertwining of growth and story makes upgrades feel earned rather than arbitrary. You are not simply optimizing numbers; you are investing in people. The result is a progression loop that reinforces attachment, encouraging players to care about team composition beyond efficiency. In a genre often dominated by abstraction, this approach gives development a narrative spine, turning mechanical improvement into a reflection of trust and shared experience.</p>
                        <div class="post-comments">
                            <div class="comment" >
                                <div class="comment-body">
                                    <div class="comment-header">
                                        <img id="comment-user-pfp" src="ASSETS/IMG/Avatars/Avatar4.png">
                                        <h5 class="comment-user-name">Username 8</h5>
                                    </div>
                                    <p class="comment-user-text" id="comment1">Linking progression to relationships is such a strong point, and you explained it really well. I definitely care more about agents whose stories I’ve unlocked, not just their stats. This makes me more patient with building teams slowly instead of rushing toward efficiency.</p>
                                </div>
                            </div>
                            <div class="comment">
                                <div class="comment-body">
                                    <div class="comment-header">
                                        <img id="comment-user-pfp" src="ASSETS/IMG/Avatars/Avatar3.png">
                                        <h5 class="comment-user-name">Username 9</h5>
                                    </div>
                                    <p class="comment-user-text" id="comment2">I agree that tying upgrades to story makes progression feel less mechanical. It also motivates me to try agents I might otherwise ignore. When I unlock more of their background, I end up caring about team balance in a more personal way.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="change-background-btn" id="change-footer-btn">
        <div class="change-background-btn-extra">
            <img class="change-background-img" src="ASSETS/IMG/UI/ChangeWallpaper.png">
        </div>
    </div>

    <div id="footer-container">
        <?php include 'footer.html'; ?>
    </div>
    <script src="JS/home.js"></script>
</body>
</html>
