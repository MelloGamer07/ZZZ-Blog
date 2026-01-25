function showIt(element) {
    var parent = element.parentNode;
    //alert(parent.id);
}

const modal = document.getElementById('modal-post');

function openModal(){
    document.body.style.overflow = "hidden";
}

modal.addEventListener('click', function () {
    modal.style.display = "none";
    document.body.style.overflow = "auto";
});

/* ================= Random Loading Screen Animation ================= */
const loadingScreen = document.getElementById('LoadingScreen');
const changeFooterBtn = document.getElementById('change-footer-btn');

function playLoadingAnimation() {
    loadingScreen.classList.remove('loading-in', 'loading-out');
    loadingScreen.style.display = 'block';

    void loadingScreen.offsetWidth;

    let id = Math.floor(Math.random() * 128);

    if( id == 36 || id == 95 || id == 54 || id == 124){
        let chance = Math.floor(Math.random() * 100) + 1;
        if( chance > 1 && chance < 99){
            let change = Math.floor(Math.random() * 100) + 1;
            if( change > 50 ){
                id = id + 1;
            } else {
                id = id - 1;
            }
            console.log("You lost the gamble ;/");
        } else {
            console.log("JACKPOT");
        }
    }

    loadingScreen.style.backgroundImage = `url("ASSETS/IMG/LoadingScreens/${id}.jpg")`;
    loadingScreen.style.backgroundSize = "100% auto";
    loadingScreen.style.backgroundPosition = "center";

    loadingScreen.classList.add('loading-in');

    setTimeout(() => {
        loadingScreen.classList.remove('loading-in');
        loadingScreen.classList.add('loading-out');
    }, 1150);

    setTimeout(() => {
        loadingScreen.style.display = 'none';
        loadingScreen.classList.remove('loading-out');
    }, 1250);
}

/* ================ fixing the damn footer ================== */
function enableHorizontalScroll() {
    const footerScroll = document.querySelector('.footer-scroll');
    if (!footerScroll) return;

    // Prevent adding multiple listeners if called multiple times
    if (!footerScroll.dataset.scrollEnabled) {
        footerScroll.addEventListener('wheel', (e) => {
            e.preventDefault();
            footerScroll.scrollLeft += e.deltaY;
        });
        footerScroll.dataset.scrollEnabled = 'true';
    }
}


/* ================= Swapp Footer ================= */
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

            // Updated fetch cuz the broser is a bitch ahh and wont load new footer
            fetch('CDWfooter.html?t=' + new Date().getTime())
                .then(response => response.text())
                .then(html => {
                    
                    footerContainer.innerHTML = html;
                    isDynamicFooter = true;

                    enableHorizontalScroll();

                    const buttons = footerContainer.querySelectorAll('.footer-scroll .btn');
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

const interKnot = document.getElementById('interKnot');
const posts = document.getElementById('posts');
let isInterKnot = false;
interKnot.addEventListener('click', function () {
    setTimeout(() => {
        if(isInterKnot == false){
            posts.style.display = "grid";
            isInterKnot  = true;
        } else {
            posts.style.display = "none";
            isInterKnot  = false;
        }
    }, 500);
    
    playLoadingAnimation();  
});