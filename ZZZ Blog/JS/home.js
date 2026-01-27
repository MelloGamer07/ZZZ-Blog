/* ================= Element References ================= */

const modal = document.getElementById('modal-post');

const postUsername = document.getElementById('post-user-name');
const postImage = document.getElementById('post-image');
const postTitle = document.getElementById('post-title');
const postText = document.getElementById('post-text');
const comment1 = document.getElementById('comment1');
const comment2 = document.getElementById('comment2');

const loadingScreen = document.getElementById('LoadingScreen');
const changeFooterBtn = document.getElementById('change-footer-btn');

const footerContainer = document.getElementById('footer-container');
const mainFooterHTML = footerContainer.innerHTML;
const video = document.getElementById('DynamicWallpaper');
const source = video.querySelector('source');
const title = document.getElementById('title');

const interKnot = document.getElementById('interKnot');
const posts = document.getElementById('posts');


/* ================= Modal Functions ================= */

function openModal(element){
    var parent = element.parentNode;
    //parent.id
    editModal(parseInt(parent.id));
    document.body.style.overflow = "hidden";
    modal.style.display = "block";

    modal.classList.add('open-modal');

    setTimeout(() => {
        loadingScreen.classList.remove('open-modal');
    }, 1150);
}

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


/* ================= Modal Content ================= */

function editModal(id){
    if(id == 1){
        postUsername.innerHTML = "Username 1";
        postImage.src = "ASSETS/IMG/LoadingScreens/1.jpg";
        postTitle.innerHTML = "Life Inside the Hollow";
        postText.innerHTML = 
        "Zenless Zone Zero does an exceptional job of making New Eridu feel alive, even though the city exists on the edge of total collapse. Walking through Sixth Street, you can sense how people have adapted to a world where Hollows are not rare disasters but a constant background threat. Shops sell survival gear beside snacks, and conversations casually reference evacuations the way other cities might mention traffic. This setting quietly reinforces the game’s central tension: normal life persists, but only because people refuse to surrender to fear. The contrast between colorful storefronts and the looming danger of the Hollows creates a tone that is both playful and uneasy, reminding players that every commission is part of a much larger struggle for stability."
        ;
        comment1.innerHTML = 
        "This really captures what I love about New Eridu. The way everyday life continues next to constant danger is something I didn’t fully appreciate until reading this. I always rush through Sixth Street, but now I’m realizing how much atmosphere is packed into those small details. It makes the city feel less like a hub and more like a place people actually live in."
        ;
        comment2.innerHTML =
        "I’ve always liked the setting, but I never stopped to think about how normalized disaster has become for the people in New Eridu. The comparison to traffic really landed for me. It’s unsettling in a subtle way, and it explains why the city feels both cozy and tense at the same time."
        ;
    }
    if(id == 2){
        postUsername.innerHTML = "Username 2";
        postImage.src = "ASSETS/IMG/LoadingScreens/2.jpg";
        postTitle.innerHTML = "The Proxy’s Invisible Burden";
        postText.innerHTML = 
        "Playing as a Proxy is less about heroics and more about responsibility. Unlike many action RPG protagonists, the Proxy rarely takes direct credit for victories. Instead, they coordinate, observe, and manage risk from the sidelines. This narrative choice reframes power as foresight rather than brute strength. Every decision about which agents to deploy, which commissions to accept, and how much danger to tolerate reflects the quiet pressure of leadership. Zenless Zone Zero uses this role to explore how influence often operates unseen. You are not the strongest fighter in the room, but you are the reason the fight happens at all. That subtle shift in perspective adds emotional weight to even routine missions."
        ;
        comment1.innerHTML = 
        "I like how you framed the Proxy as a leader rather than a hero. That perspective explains why the role feels different from most RPG protagonists. I hadn’t thought about how much pressure comes from simply choosing who goes into danger. It makes even simple commissions feel heavier in a good way."
        ;
        comment2.innerHTML =
        "This post made me appreciate the narrative design a lot more. I used to think the Proxy felt passive compared to other protagonists, but framing it as “invisible influence” makes it click. It actually feels more realistic than being the strongest fighter everywhere you go."
        ;
    }
    if(id == 3){
        postUsername.innerHTML = "Username 3";
        postImage.src = "ASSETS/IMG/LoadingScreens/3.jpg";
        postTitle.innerHTML = "Combat That Feels Like Conversation";
        postText.innerHTML = 
        "The flow of battle in Zenless Zone Zero resembles a dialogue more than a brawl. Enemy patterns speak first, agents respond, and perfect dodges or assists feel like well-timed replies. The system rewards attention and rhythm instead of memorization alone. What stands out is how readable the chaos remains, even when multiple effects overlap. Animations communicate intent clearly, and sound cues reinforce timing. This clarity encourages experimentation with team compositions, because success depends less on raw numbers and more on understanding interaction. Over time, combat becomes expressive, letting players develop a personal style that reflects how they read and answer the game’s challenges."
        ;
        comment1.innerHTML = 
        "The idea of combat as a dialogue is a great way to describe it. That’s exactly how it feels when dodges and assists line up perfectly. I’ve noticed that the game rewards attention more than grinding, and this post explains why the system feels so satisfying without being overwhelming."
        ;
        comment2.innerHTML =
        "Great breakdown of why the combat stays readable even when things get hectic. I’ve played other action games where effects just blur together, but here I always know why I got hit or succeeded. That clarity really does encourage experimenting with different teams."
        ;
    }
    if(id == 4){
        postUsername.innerHTML = "Username 4";
        postImage.src = "ASSETS/IMG/LoadingScreens/4.jpg";
        postTitle.innerHTML = "Factions as Living Ideologies";
        postText.innerHTML = 
        "Each faction in Zenless Zone Zero represents more than a combat role; they embody distinct philosophies about survival in a fractured world. Whether it is corporate pragmatism, street-level resilience, or idealistic protection, these groups shape how agents speak, fight, and relate to the city. The result is a narrative ecosystem where conflicts feel ideological as much as practical. When factions collide, it is not just about territory or profit, but about competing visions of what New Eridu should become. This layered design gives even side stories thematic resonance, encouraging players to pay attention to dialogue and background details that quietly define the world’s moral landscape."
        ;
        comment1.innerHTML = 
        "This made me rethink how I view the factions. I usually just see them as banners and team bonuses, but you’re right that they represent different beliefs about survival. Now I’m more interested in paying attention to side stories and dialogue to see how those philosophies actually play out."
        ;
        comment2.innerHTML =
        "I’m glad you pointed out the ideological side of the factions. It makes their rivalries more interesting than simple good-versus-evil. Now I’m curious how future updates will deepen those conflicts and whether any faction will seriously challenge the city’s direction."
        ;
    }
    if(id == 5){
        postUsername.innerHTML = "Username 5";
        postImage.src = "ASSETS/IMG/LoadingScreens/5.jpg";
        postTitle.innerHTML = "Style as Storytelling";
        postText.innerHTML = 
        "Visual style in Zenless Zone Zero is not decoration; it is narrative shorthand. Bold outlines, exaggerated motion, and color-coded effects communicate personality and intent faster than exposition ever could. A character’s fighting stance reveals temperament, while idle animations hint at private habits. Even UI elements reinforce tone, blending urban grit with playful abstraction. This cohesion allows the game to move quickly between comedy and danger without tonal whiplash. Style becomes a language that tells you who to trust, what to fear, and when to relax. In this way, aesthetics do narrative work, making the world readable at a glance."
        ;
        comment1.innerHTML = 
        "The connection between art style and narrative is something I hadn’t consciously noticed, but now it seems obvious. The animations really do say a lot about each character without any words. This post helped me understand why the game’s tone feels so consistent even when it shifts between humor and danger."
        ;
        comment2.innerHTML =
        "The way you described aesthetics as a language is spot on. I think that’s why new characters feel instantly recognizable even before you learn their backstory. The UI and motion design really do a lot of storytelling work behind the scenes."
        ;
    }
    if(id == 6){
        postUsername.innerHTML = "Username 6";
        postImage.src = "ASSETS/IMG/LoadingScreens/6.jpg";
        postTitle.innerHTML = "The Quiet Moments Between Commissions";
        postText.innerHTML = 
        "What lingers after a session is often not the boss fight, but the walk back through the city. Zenless Zone Zero excels at downtime: browsing shops, overhearing conversations, and checking messages from agents. These small interactions ground the high-stakes action in everyday routine. They also remind players that the city survives not because of grand victories, but because people keep opening stores, repairing signs, and telling jokes. This pacing prevents burnout and gives emotional context to danger. By valuing stillness as much as spectacle, the game builds a rhythm that feels sustainable and human."
        ;
        comment1.innerHTML = 
        "I’m glad someone highlighted the downtime. Those walks through the city are some of my favorite parts, even though they seem minor. They make the world feel grounded and give me a breather after intense fights. It’s nice to see that aspect appreciated."
        ;
        comment2.innerHTML =
        "Those slower sections are exactly what keep me from burning out. I like checking messages and wandering around almost as much as fighting. It makes the action feel earned instead of nonstop noise, which is rare for this kind of game."
        ;
    }
    if(id == 7){
        postUsername.innerHTML = "Username 7";
        postImage.src = "ASSETS/IMG/LoadingScreens/7.jpg";
        postTitle.innerHTML = "Why Progress Feels Meaningful";
        postText.innerHTML = 
        "Advancement in Zenless Zone Zero is structured around relationships as much as stats. Unlocking abilities often coincides with learning more about an agent’s past, motivations, or fears. This intertwining of growth and story makes upgrades feel earned rather than arbitrary. You are not simply optimizing numbers; you are investing in people. The result is a progression loop that reinforces attachment, encouraging players to care about team composition beyond efficiency. In a genre often dominated by abstraction, this approach gives development a narrative spine, turning mechanical improvement into a reflection of trust and shared experience."
        ;
        comment1.innerHTML = 
        "Linking progression to relationships is such a strong point, and you explained it really well. I definitely care more about agents whose stories I’ve unlocked, not just their stats. This makes me more patient with building teams slowly instead of rushing toward efficiency."
        ;
        comment2.innerHTML =
        "I agree that tying upgrades to story makes progression feel less mechanical. It also motivates me to try agents I might otherwise ignore. When I unlock more of their background, I end up caring about team balance in a more personal way."
        ;
    }
}


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
