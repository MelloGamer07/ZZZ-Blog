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
      cursor: url("ASSETS/cursor.cur"),auto;
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

    html, body {
      height: 100%; 
    }

    .page-wrapper {
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    .content {
      flex: 1;
    }


  </style>
</head>
<body>
    <div class="page-wrapper">

      <?php include 'header.html' ?>

      <main class="content"></main>

      <?php include 'footer.html' ?>

  </div>
</body>
</html>
