<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>ZZZ Home Page</title>
  <style>
    body{
      margin:0;
      background-image: url('ASSETS/IMG/hq720.jpg');
      background-size: 100%;
    }

    #DynamicWallpaper {
      position: fixed;
      right: 0;
      bottom: 0;
      min-width: 100%; 
      min-height: 100%;
      max-width: 100%;
      z-index: -1;
    }

  </style>
</head>
<body>
    <video autoplay muted loop id="DynamicWallpaper">
      <source src="ASSETS/DynamicWallpapers/WiseDynamicWallpaper.mp4" type="video/mp4">
    </video>
    <?php include 'header.html' ?>
    <?php include 'footer.html' ?>
</body>
</html>
