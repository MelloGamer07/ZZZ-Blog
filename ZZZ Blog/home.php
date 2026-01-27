<?php
    session_start();
    $Username = $_SESSION['Username'] ?? "Guest";
    $IDUsername = $_SESSION['IdUsername'];
    $AvatarID = $_SESSION['Avatar'];

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
        <!--?php include 'postModal.html' ?-->
    </div>

    <div class="change-background-btn" id="change-footer-btn">
        <div class="change-background-btn-extra">
            <img class="change-background-img" src="ASSETS/IMG/UI/ChangeWallpaper.png">
        </div>
    </div>

    <div class="plus-btn" id="plus-btn">
        <div class="plus-btn-extra">
            <img class="plus-img" src="ASSETS/IMG/UI/plus.png">
        </div>
    </div>

    <div id="footer-container">
        <?php include 'footer.html'; ?>
    </div>
    <script src="JS/home.js"></script>
</body>
</html>
