<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>ZZZ Home Page</title>
  <link rel="stylesheet" href="CSS/index.css">
</head>
<body>
    <video autoplay muted loop id="DynamicWallpaper">
       <source src="ASSETS/DynamicWallpapers/WiseDynamicWallpaper.mp4" type="video/mp4">
    </video>
    <div class="page-wrapper">

      <?php include 'header.html' ?>

      <main class="content"></main>

      <div class="change-background-btn" onclick="window.location.pathname = 'ZZZ-Blog/ZZZ Blog/ChangeDynamicWallpaper.html';">
          <div class="change-background-btn-extra">
              <img class="change-background-img" src="ASSETS/IMG/UI/ChangeWallpaper.png">
          </div>
      </div>

      <?php include 'footer.html' ?>

  </div>
</body>
</html>
