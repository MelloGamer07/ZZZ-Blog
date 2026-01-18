<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>ZZZ Home Page</title>
  <link rel="stylesheet" href="CSS/index.css">
</head>
<body>
    <video autoplay muted loop id="DynamicWallpaper">
       <source src="ASSETS/DynamicWallpapers/DynamicWallpaper1.mp4" type="video/mp4">
    </video>
    <div class="page-wrapper">

      <?php include 'header.html' ?>

      <main class="content"></main>

      <!-- Button to swap footer -->
      <div class="change-background-btn" id="change-footer-btn">
          <div class="change-background-btn-extra">
              <img class="change-background-img" src="ASSETS/IMG/UI/ChangeWallpaper.png">
          </div>
      </div>

      <!-- Footer container -->
      <div id="footer-container">
          <?php include 'footer.html'; ?>
      </div>
  </div>

  <script>
  // Keep track of current footer state
  let isDynamicFooter = false;

  // Cache main footer HTML
  const footerContainer = document.getElementById('footer-container');
  const mainFooterHTML = footerContainer.innerHTML;

  const video = document.getElementById('DynamicWallpaper');
  const source = video.querySelector('source');

  document.getElementById('change-footer-btn').addEventListener('click', function() {
      if (!isDynamicFooter) {
          // Load dynamic footer
          fetch('CDWfooter.html')
              .then(response => response.text())
              .then(html => {
                  footerContainer.innerHTML = html;
                  isDynamicFooter = true;

                  // Attach video-changing listeners
                  const buttons = document.querySelectorAll('#footer-container .footer-scroll .btn');
                  buttons.forEach(btn => {
                      btn.addEventListener('click', () => {
                          const id = btn.id;
                          source.src = `ASSETS/DynamicWallpapers/DynamicWallpaper${id}.mp4`;
                          video.load();
                          video.play();
                      });
                  });
              })
              .catch(err => console.error('Failed to load footer:', err));
      } else {
          // Switch back to main footer
          footerContainer.innerHTML = mainFooterHTML;
          isDynamicFooter = false;
      }
  });
  </script>
</body>
</html>
