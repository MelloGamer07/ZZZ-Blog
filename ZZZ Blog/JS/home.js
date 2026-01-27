/* ================= Element References ================= */

const modal = document.getElementById('modal-post');

const postTitle = document.getElementById('post-title');
const postText = document.getElementById('post-text');
const postUsername = document.getElementById('post-user-name');
const postUserPfp = document.getElementById('post-user-pfp');
const postImage = document.getElementById('post-image');

const comment1 = document.getElementById('comment1');
const comment2 = document.getElementById('comment2');

const loadingScreen = document.getElementById('LoadingScreen');
const changeFooterBtn = document.getElementById('change-footer-btn');
const plusBtn = document.getElementById('plus-btn');

const footerContainer = document.getElementById('footer-container');
const mainFooterHTML = footerContainer.innerHTML;

const video = document.getElementById('DynamicWallpaper');
const source = video.querySelector('source');
const title = document.getElementById('title');

const interKnot = document.getElementById('interKnot');
const posts = document.getElementById('posts');

const fileDiv = document.getElementById('fileDiv');
const addPostTitle = document.getElementById('add-post-title');
const addPostText = document.getElementById('add-post-text');
const fileInput = document.getElementById('add-post-img');
const postImg = document.getElementById('post-image');
const postLikes = document.getElementById('num-likes');


/* ================= Modal Functions ================= */

function openModal(element){
    const parent = element.parentNode;
    const postId = parent.id;

    switchToPostModal();
    editModal(postId);

    document.body.style.overflow = "hidden";
    modal.style.display = "block";
    modal.classList.add("open-modal");

    setTimeout(() => {
        loadingScreen.classList.remove('open-modal');
    }, 1150);
}

plusBtn.addEventListener('click', function() {

    switchToAddPostModal();
    editModal(parent.id);

    document.body.style.overflow = "hidden";
    modal.style.display = "block";

    modal.classList.add('open-modal');

    setTimeout(() => {
        loadingScreen.classList.remove('open-modal');
    }, 1150);
});

function closeModal(){
    modal.classList.add('close-modal');
    setTimeout(() => {
        modal.classList.remove('close-modal');
        modal.style.display = "none";
    }, 1000);
    document.body.style.overflow = "auto";
}

modal.addEventListener('click', function (e) {
    if (e.target === modal) {
        modal.classList.add('close-modal');
        setTimeout(() => {
        modal.classList.remove('close-modal');
        modal.style.display = "none";
    }, 1000);
        document.body.style.overflow = "auto";
    }
});



/* ================= Random Loading Screen Animation ================= */

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
                    bindInterKnot();
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
            bindInterKnot();
        }
    }, 500);
});


/* ================= InterKnot Toggle ================= */

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

function bindInterKnot() {
    const interKnot = document.getElementById('interKnot');
    if (!interKnot) return;

    interKnot.addEventListener('click', function () {
        setTimeout(() => {
            if (isInterKnot == false) {
                posts.style.display = "grid";
                isInterKnot = true;
            } else {
                posts.style.display = "none";
                isInterKnot = false;
            }
        }, 500);

        playLoadingAnimation();
    });
}

    function loadModal(fileName) {
        fetch(fileName + '?t=' + new Date().getTime())
            .then(response => response.text())
            .then(html => {
                modal.innerHTML = html;
                bindAddPostTags();
                bindPostTags();
            })
            .catch(err => console.error('Error loading modal:', err));
    }

    function switchToAddPostModal() {
        loadModal('addPostModal.html');
    }

    function switchToPostModal() {
        loadModal('postModal.html');
    }

/*=====================================================================*/

    function bindAddPostTags() {
        const fileDiv = document.getElementById('fileDiv');
        const addPostTitle = document.getElementById('add-post-title');
        const addPostText = document.getElementById('add-post-text');
        const fileInput = document.getElementById('add-post-img');

        if (!fileDiv || !addPostTitle || !addPostText || !fileInput) return;

        fileDiv.addEventListener('click', function () {
            fileInput.click();
        });

        fileInput.addEventListener('change', (e) => {
            if (e.target === fileInput) {
                if (fileInput.files.length > 0) {
                    const file = fileInput.files[0];
                    const imageUrl = URL.createObjectURL(file);
                    fileDiv.style.backgroundImage = `url(${imageUrl})`;
                    fileDiv.style.backgroundSize = 'cover';      
                    fileDiv.style.backgroundPosition = 'center'; 
                    fileInput.style.opacity = 0;
                }
            }
        });
    }

    function bindPostTags() {
        const postTitle = document.getElementById('post-title');
        const postText = document.getElementById('post-text');
        const postUsername = document.getElementById('post-user-name');
        const postUserPfp = document.getElementById('post-user-pfp');
        const postImg = document.getElementById('post-image');
        const postLikes = document.getElementById('num-likes');

        if (!postTitle || !postText || !postUsername || !postUserPfp || !postImg || !postLikes) return;
    }



/* ================= Modal Content ================= */

function editModal(postId) {
    const post = document.getElementById(postId);
    if (!post) return;

    const modalCheck = setInterval(() => {
        const postTitle = document.getElementById('post-title');
        const postText = document.getElementById('post-text');
        const postUsername = document.getElementById('post-user-name');
        const postUserPfp = document.getElementById('post-user-pfp');
        const postImg = document.getElementById('post-image');
        const postLikes = document.getElementById('num-likes');


        if (!postTitle || !postText || !postUsername || !postUserPfp || !postImg || !postLikes) return;

        clearInterval(modalCheck);

        postTitle.textContent = post.dataset.title;
        postText.textContent = post.dataset.desc;
        postUsername.textContent = post.dataset.user;
        postLikes.textContent = post.dataset.likes;
        postUserPfp.src = "ASSETS/IMG/Avatars/Avatar" + post.dataset.avatar + ".png";
        postImg.src = post.dataset.img;
    }, 50);
}