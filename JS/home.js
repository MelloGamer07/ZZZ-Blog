/* ================= Element References ================= */

const loadingScreen = document.getElementById('LoadingScreen');
const changeFooterBtn = document.getElementById('change-footer-btn');

const footerContainer = document.getElementById('footer-container');
const mainFooterHTML = footerContainer?.innerHTML || '';

const video = document.getElementById('DynamicWallpaper');
const source = video?.querySelector('source');
const title = document.getElementById('title');

const interKnot = document.getElementById('interKnot');
const adminBtn = document.getElementById('adminBtn');
const posts = document.getElementById('posts');

const profilePage = document.getElementById('profile-btn');

let isDynamicFooter = false;

/* ================= Router (History API) ================= */

/**
 * Centralized navigation. Updates history and triggers the appropriate view.
 * @param {'home'|'interknot'|'article'} view
 * @param {string|null} articleId  – only required when view === 'article'
 * @param {boolean} replace        – use replaceState instead of pushState
 */
function navigateTo(view, articleId = null, replace = false) {
    let url = window.location.pathname;
    const state = { view, articleId };

    if (view === 'interknot') url += '?view=interknot';
    if (view === 'article')   url += `?article=${articleId}`;

    replace
        ? history.replaceState(state, '', url)
        : history.pushState(state, '', url);

    applyRoute(state);
}

/** Applies a route state to the DOM — called by navigateTo and popstate. */
function applyRoute({ view, articleId }) {
    switch (view) {
        case 'article':
            posts.style.display = 'grid';
            loadArticleModal(articleId, false);
            break;

        case 'interknot':
            posts.style.display = 'grid';
            closeModalSilently();
            break;

        case 'home':
        default:
            posts.style.display = 'none';
            closeModalSilently();
            break;
    }
}

/** Reads the current URL on first load and routes accordingly. */
function initRoute() {
    const params = new URLSearchParams(window.location.search);
    const articleId = params.get('article');
    const view = params.get('view');

    if (articleId) {
        // Replace so the entry point URL stays clean in history
        history.replaceState({ view: 'article', articleId }, '', window.location.href);
        posts.style.display = 'grid';
        loadArticleModal(articleId, false);
    } else if (view === 'interknot') {
        history.replaceState({ view: 'interknot', articleId: null }, '', window.location.href);
        posts.style.display = 'grid';
    } else {
        history.replaceState({ view: 'home', articleId: null }, '', window.location.pathname);
        posts.style.display = 'none';
    }
}

/** Handle browser back / forward. */
window.addEventListener('popstate', (e) => {
    applyRoute(e.state ?? { view: 'home', articleId: null });
});

window.addEventListener('load', initRoute);

/* ================= Modal ================= */

window.openModal = function (element) {
    const parent = element.closest('.post-container');
    if (!parent) return;

    // Push state manually so we can control animation independently of the router
    history.pushState({ view: 'article', articleId: parent.id }, '', `?article=${parent.id}`);
    loadArticleModal(parent.id, true); // animated
    document.body.style.overflow = 'hidden';
};

function openModalById(articleId) {
    // Called from a direct URL on page load — no animation, just restore state
    history.replaceState({ view: 'article', articleId }, '', `?article=${articleId}`);
    loadArticleModal(articleId, false);
    document.body.style.overflow = 'hidden';
}

window.closeModal = function () {
    const modal = document.getElementById('modal-post');
    if (!modal) return;

    modal.classList.add('close-modal');
    setTimeout(() => {
        modal.style.display = 'none';
        modal.remove();
        document.body.style.overflow = 'auto';
        posts.style.display = 'grid'; // explicitly restore InterKnot
        navigateTo('interknot');
    }, 1000);
};

/** Removes a modal from the DOM without touching history (used by the router). */
function closeModalSilently() {
    const modal = document.getElementById('modal-post');
    if (!modal) return;
    modal.remove();
    document.body.style.overflow = 'auto';
}

/** Fetches and renders the modal for a given post ID. */
function loadArticleModal(id, animated = true) {
    fetch('PHP/addPost.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `postId=${id}`
    })
    .then(res => res.text())
    .then(html => {
        const oldModal = document.getElementById('modal-post');
        if (oldModal) oldModal.remove();

        document.body.insertAdjacentHTML('afterbegin', html);

        const modal                      = document.getElementById('modal-post');
        const postImage                  = document.getElementById('post-image');
        const IMGModalContainerBackground = document.getElementById('IMG-Modal-Container-Background');
        const IMGModalContainer          = document.getElementById('IMG-Modal-Container');
        const IMGModal                   = document.getElementById('IMG-Modal');

        if (postImage && IMGModal && IMGModalContainer && IMGModalContainerBackground) {
            postImage.addEventListener('click', () => {
                IMGModal.src = postImage.src;
                IMGModalContainerBackground.style.display = 'flex';
                IMGModalContainer.style.display = 'flex';
                setTimeout(() => {
                    IMGModalContainerBackground.style.opacity = 0.9;
                    IMGModalContainer.style.opacity = 1;
                }, 200);
            });

            const closeIMGModal = () => {
                IMGModalContainerBackground.style.opacity = 0;
                IMGModalContainer.style.opacity = 0;
                setTimeout(() => {
                    IMGModalContainerBackground.style.display = 'none';
                    IMGModalContainer.style.display = 'none';
                }, 200);
            };

            IMGModalContainerBackground.addEventListener('click', closeIMGModal);
            IMGModalContainer.addEventListener('click', closeIMGModal);
        }

        modal.style.display = 'flex';
        if (animated) modal.classList.add('open-modal');

        modal.addEventListener('click', e => {
            if (e.target === modal) closeModal();
        });

        const formComment = document.getElementById('comment-form');
        if (formComment) {
            formComment.addEventListener('submit', e => {
                e.preventDefault();
                const comment = document.getElementById('add-post-comment');
                if (!comment.value.trim()) return;
                e.target.submit();
            });
        }
    });
}

// Expose editModal as an alias so any existing PHP-rendered HTML that calls it still works.
window.editModal = function (id, state) {
    loadArticleModal(id, state === 'clicked');
};

/* ================= Random Loading Screen Animation ================= */

function playLoadingAnimation() {
    loadingScreen.classList.remove('loading-in', 'loading-out');
    loadingScreen.style.display = 'block';

    void loadingScreen.offsetWidth;

    let id = Math.floor(Math.random() * 128);
    if ([36, 54, 95, 124].includes(id)) {
        if (Math.random() * 100 < 99) id += Math.random() > 0.5 ? 1 : -1;
    }

    loadingScreen.style.backgroundImage = `url("ASSETS/IMG/LoadingScreens/${id}.jpg")`;
    loadingScreen.style.backgroundSize = '100% auto';
    loadingScreen.style.backgroundPosition = 'center';
    loadingScreen.classList.add('loading-in');

    setTimeout(() => loadingScreen.classList.replace('loading-in', 'loading-out'), 1150);
    setTimeout(() => {
        loadingScreen.style.display = 'none';
        loadingScreen.classList.remove('loading-out');
    }, 1250);
}

/* ================= Footer Scroll Fix ================= */

function enableHorizontalScroll() {
    const footerScroll = document.querySelector('.footer-scroll');
    if (!footerScroll || footerScroll.dataset.scrollEnabled) return;

    footerScroll.addEventListener('wheel', e => {
        e.preventDefault();
        footerScroll.scrollLeft += e.deltaY;
    });

    footerScroll.dataset.scrollEnabled = 'true';
}

/* ================= Footer Swap ================= */

changeFooterBtn?.addEventListener('click', () => {
    playLoadingAnimation();

    setTimeout(() => {
        if (!isDynamicFooter) {
            title.innerHTML = 'Dynamic Wallpapers';
            title.dataset.text = 'Dynamic Wallpapers';

            fetch('CDWfooter.html?t=' + Date.now())
                .then(res => res.text())
                .then(html => {
                    footerContainer.innerHTML = html;
                    isDynamicFooter = true;

                    enableHorizontalScroll();
                    bindInterKnot();

                    footerContainer
                        .querySelectorAll('.footer-scroll .btn')
                        .forEach(btn => {
                            btn.addEventListener('click', () => {
                                fetch('PHP/backgroundPreference.php?t=' + Date.now(), {
                                    method: 'POST',
                                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                                    body: `backgroundPreference=${btn.id}`
                                });

                                playLoadingAnimation();
                                setTimeout(() => {
                                    source.src = `ASSETS/DynamicWallpapers/DynamicWallpaper${btn.id}.mp4`;
                                    video.load();
                                    video.play();
                                }, 500);
                            });
                        });
                });
        } else {
            footerContainer.innerHTML = mainFooterHTML;
            title.innerHTML = 'Home page';
            title.dataset.text = 'Home page';
            isDynamicFooter = false;
            bindInterKnot();
        }
    }, 500);
});

/* ================= InterKnot Toggle ================= */

function bindInterKnot() {
    const knot = document.getElementById('interKnot');
    if (!knot) return;

    knot.onclick = () => {
        const currentState = history.state ?? {};
        const isCurrentlyInterKnot = currentState.view === 'interknot' || currentState.view === 'article';

        playLoadingAnimation();
        setTimeout(() => {
            if (isCurrentlyInterKnot) {
                posts.style.display = 'none';
                navigateTo('home');
            } else {
                posts.style.display = 'grid';
                navigateTo('interknot');
            }
        }, 500);
    };
}

bindInterKnot();

/* ================= Add-Post Modal Support ================= */

function bindAddPostTags() {
    const fileDiv      = document.getElementById('fileDiv');
    const addPostTitle = document.getElementById('add-post-title');
    const addPostText  = document.getElementById('add-post-text');
    const fileInput    = document.getElementById('add-post-img');

    if (!fileDiv || !addPostTitle || !addPostText || !fileInput) return;

    fileDiv.addEventListener('click', () => fileInput.click());

    fileInput.addEventListener('change', e => {
        if (fileInput.files.length > 0) {
            const imageUrl = URL.createObjectURL(fileInput.files[0]);
            fileDiv.style.backgroundImage    = `url(${imageUrl})`;
            fileDiv.style.backgroundSize     = 'cover';
            fileDiv.style.backgroundPosition = 'center';
            fileInput.style.opacity = 0;
        }
    });
}

/* ================= Auth Modal ================= */

function openAuthModal() {
    document.getElementById('authModalOverlay')?.classList.add('auth-modal-open');
}

function closeAuthModal() {
    const overlay = document.getElementById('authModalOverlay');
    if (!overlay) return;
    overlay.classList.remove('auth-modal-open');
    overlay.classList.add('auth-modal-closing');
    setTimeout(() => overlay.classList.remove('auth-modal-closing'), 350);
}

document.getElementById('authModalOverlay')?.addEventListener('click', function (e) {
    if (e.target === this) closeAuthModal();
});

profilePage.addEventListener('click', () => {
    if (!isLoggedIn) {
        openAuthModal();
        return;
    }
    window.location.href = 'ProfilePage.php';
});

/* ================= Admin Button ================= */

function bindadminBtn() {
    const admin = document.getElementById('adminBtn');
    if (!admin) return;
    admin.onclick = () => { window.location.href = 'AdminDashboard.php'; };
}

bindadminBtn();