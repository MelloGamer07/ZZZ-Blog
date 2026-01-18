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

    <div id="LoadingScreen"></div>

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
    /* ================= Loading Screen Animation ================= */
    const loadingScreen = document.getElementById('LoadingScreen');
    const changeFooterBtn = document.getElementById('change-footer-btn');

    function playLoadingAnimation() {
        loadingScreen.classList.remove('loading-in', 'loading-out');
        loadingScreen.style.display = 'block';

        void loadingScreen.offsetWidth;

        loadingScreen.classList.add('loading-in');

        setTimeout(() => {
            loadingScreen.classList.remove('loading-in');
            loadingScreen.classList.add('loading-out');
        }, 1150);

        setTimeout(() => {
            loadingScreen.style.display = 'none';
            loadingScreen.classList.remove('loading-out');
        }, 2000);
    }

    /* ================= Footer Swap Logic ================= */
    let isDynamicFooter = false;

    const footerContainer = document.getElementById('footer-container');
    const mainFooterHTML = footerContainer.innerHTML;

    const video = document.getElementById('DynamicWallpaper');
    const source = video.querySelector('source');
    const title = document.getElementById('title');

    changeFooterBtn.addEventListener('click', function () {
        playLoadingAnimation();
        setTimeout(() => {
        if (!isDynamicFooter) {
            title.innerHTML = "Dynamic Wallpapers";
            title.dataset.text = "Dynamic Wallpapers";

            fetch('CDWfooter.html')
            .then(response => response.text())
            .then(html => {
                footerContainer.innerHTML = html;
                isDynamicFooter = true;

                const buttons = document.querySelectorAll('#footer-container .footer-scroll .btn');

                buttons.forEach(btn => {
                    btn.addEventListener('click', () => {
                        playLoadingAnimation();
                        setTimeout(() => {
                            const id = btn.id;
                            source.src = `ASSETS/DynamicWallpapers/DynamicWallpaper${id}.mp4`;
                            video.load();
                            video.play();
                        }, 500);
                    });
                });
            })
            .catch(err => console.error('Failed to load footer:', err));
        } else {
            footerContainer.innerHTML = mainFooterHTML;
            title.innerHTML = "Home page";
            title.dataset.text = "Home page";
            isDynamicFooter = false;
        }
        }, 500);
    });
    </script>


</body>
</html>
