<?php
    $hostname = "localhost";
    $username = "root";
    $password = "";
    $database = "zzz_2";

    $conn = mysqli_connect($hostname, $username, "", $database);
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $query = "
    SELECT *
    FROM Articolo a
    JOIN Utente u ON a.IdUser = u.Id
    ORDER BY a.DataCreazione DESC LIMIT 10
    ";
    $result = mysqli_query($conn, $query);


    $counter = 0;

while ($row = mysqli_fetch_assoc($result)) {

    echo '
    <div class="post-container" id="post-' . $row['Id'] . '">
        <div class="post" onclick="openModal(this);">
            <div class="post-images"> 
                <img id="post-image-preview" src="' . $row['Img'] . '" alt="">
            </div>

            <div class="user-info">
                <img id="user-pfp" src="ASSETS/IMG/Avatars/Avatar' . $row['Avatar'] . '.png" alt="">
                <h4 id="user-name">' . $row['Username'] . '</h4>
            </div>

            <div class="post-content">
                <h3 class="post-title">' . $row['Title'] . '</h3>
                <p class="post-desc">' . $row['Descrizione'] . '</p>
            </div>
        </div>
    </div>
    ';

    }

    mysqli_close($conn);
?>
        

        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        <!--<div class="post-container" id="2">
            <div class="post" onclick="openModal(this);">
                <div class="post-images"> 
                    <img id="post-image-preview" src="ASSETS/IMG/LoadingScreens/2.jpg">
                </div>
                <div class="user-info">
                    <img id="user-pfp" src="ASSETS/IMG/Avatars/Avatar22.png">
                    <h4 id="user-name">Username 2</h4>
                </div>
                <div class="post-content">
                    <h3 class="post-title">The Proxy’s Invisible Burden</h3>
                    <p class="post-desc">Playing as a Proxy is less about heroics and more about responsibility. Unlike many action RPG protagonists, the Proxy rarely takes direct credit for victories. Instead, they coordinate, observe, and manage risk from the sidelines. This narrative choice reframes power as foresight rather than brute strength. Every decision about which agents to deploy, which commissions to accept, and how much danger to tolerate reflects the quiet pressure of leadership. Zenless Zone Zero uses this role to explore how influence often operates unseen. You are not the strongest fighter in the room, but you are the reason the fight happens at all. That subtle shift in perspective adds emotional weight to even routine missions.</p>
                </div>
            </div>
        </div>

        <div class="post-container" id="3">
            <div class="post" onclick="openModal(this);">
                <div class="post-images"> 
                    <img id="post-image-preview" src="ASSETS/IMG/LoadingScreens/3.jpg">
                </div>
                <div class="user-info">
                    <img id="user-pfp" src="ASSETS/IMG/Avatars/Avatar41.png">
                    <h4 id="user-name">Username 3</h4>
                </div>
                <div class="post-content">
                    <h3 class="post-title">Combat That Feels Like Conversation</h3>
                    <p class="post-desc">The flow of battle in Zenless Zone Zero resembles a dialogue more than a brawl. Enemy patterns speak first, agents respond, and perfect dodges or assists feel like well-timed replies. The system rewards attention and rhythm instead of memorization alone. What stands out is how readable the chaos remains, even when multiple effects overlap. Animations communicate intent clearly, and sound cues reinforce timing. This clarity encourages experimentation with team compositions, because success depends less on raw numbers and more on understanding interaction. Over time, combat becomes expressive, letting players develop a personal style that reflects how they read and answer the game’s challenges.</p>
                </div>
            </div>
        </div>

        <div class="post-container" id="4">
            <div class="post" onclick="openModal(this);">
                <div class="post-images"> 
                    <img id="post-image-preview" src="ASSETS/IMG/LoadingScreens/4.jpg">
                </div>
                <div class="user-info">
                    <img id="user-pfp" src="ASSETS/IMG/Avatars/Avatar14.png">
                    <h4 id="user-name">Username 4</h4>
                </div>
                <div class="post-content">
                    <h3 class="post-title">Factions as Living Ideologies</h3>
                    <p class="post-desc">Each faction in Zenless Zone Zero represents more than a combat role; they embody distinct philosophies about survival in a fractured world. Whether it is corporate pragmatism, street-level resilience, or idealistic protection, these groups shape how agents speak, fight, and relate to the city. The result is a narrative ecosystem where conflicts feel ideological as much as practical. When factions collide, it is not just about territory or profit, but about competing visions of what New Eridu should become. This layered design gives even side stories thematic resonance, encouraging players to pay attention to dialogue and background details that quietly define the world’s moral landscape.</p>
                </div>
            </div>
        </div>

        <div class="post-container" id="5">
            <div class="post" onclick="openModal(this);">
                <div class="post-images"> 
                    <img id="post-image-preview" src="ASSETS/IMG/LoadingScreens/5.jpg">
                </div>
                <div class="user-info">
                    <img id="user-pfp" src="ASSETS/IMG/Avatars/Avatar27.png">
                    <h4 id="user-name">Username 5</h4>
                </div>
                <div class="post-content">
                    <h3 class="post-title">Style as Storytelling</h3>
                    <p class="post-desc">Visual style in Zenless Zone Zero is not decoration; it is narrative shorthand. Bold outlines, exaggerated motion, and color-coded effects communicate personality and intent faster than exposition ever could. A character’s fighting stance reveals temperament, while idle animations hint at private habits. Even UI elements reinforce tone, blending urban grit with playful abstraction. This cohesion allows the game to move quickly between comedy and danger without tonal whiplash. Style becomes a language that tells you who to trust, what to fear, and when to relax. In this way, aesthetics do narrative work, making the world readable at a glance.</p>
                </div>
            </div>
        </div>

        <div class="post-container" id="6">
            <div class="post" onclick="openModal(this);">
                <div class="post-images"> 
                    <img id="post-image-preview" src="ASSETS/IMG/LoadingScreens/6.jpg">
                </div>
                <div class="user-info">
                    <img id="user-pfp" src="ASSETS/IMG/Avatars/Avatar17.png">
                    <h4 id="user-name">Username 6</h4>
                </div>
                <div class="post-content">
                    <h3 class="post-title">The Quiet Moments Between Commissions</h3>
                    <p class="post-desc">What lingers after a session is often not the boss fight, but the walk back through the city. Zenless Zone Zero excels at downtime: browsing shops, overhearing conversations, and checking messages from agents. These small interactions ground the high-stakes action in everyday routine. They also remind players that the city survives not because of grand victories, but because people keep opening stores, repairing signs, and telling jokes. This pacing prevents burnout and gives emotional context to danger. By valuing stillness as much as spectacle, the game builds a rhythm that feels sustainable and human.</p>
                </div>
            </div>
        </div>

        <div class="post-container" id="7" >
            <div class="post" onclick="openModal(this);">
                <div class="post-images"> 
                    <img id="post-image-preview" src="ASSETS/IMG/LoadingScreens/7.jpg">
                </div>
                <div class="user-info">
                    <img id="user-pfp" src="ASSETS/IMG/Avatars/Avatar34.png">
                    <h4 id="user-name">Username 7</h4>
                </div>
                <div class="post-content">
                    <h3 class="post-title">Why Progress Feels Meaningful</h3>
                    <p class="post-desc">Advancement in Zenless Zone Zero is structured around relationships as much as stats. Unlocking abilities often coincides with learning more about an agent’s past, motivations, or fears. This intertwining of growth and story makes upgrades feel earned rather than arbitrary. You are not simply optimizing numbers; you are investing in people. The result is a progression loop that reinforces attachment, encouraging players to care about team composition beyond efficiency. In a genre often dominated by abstraction, this approach gives development a narrative spine, turning mechanical improvement into a reflection of trust and shared experience.',0,126</p>
                </div>
            </div>
</div>
        <div class="post-container" id="1">
            <div class="post" onclick="openModal(this);">
                <div class="post-images"> 
                    <img id="post-image-preview" src="ASSETS/IMG/LoadingScreens/1.jpg">
                </div>
                <div class="user-info">
                    <img id="user-pfp" src="ASSETS/IMG/Avatars/Avatar35.png">
                    <h4 id="user-name">Username 1</h4>
                </div>
                <div class="post-content">
                    <h3 class="post-title">Life Inside the Hollow</h3>
                    <p class="post-desc">Zenless Zone Zero does an exceptional job of making New Eridu feel alive, even though the city exists on the edge of total collapse. Walking through Sixth Street, you can sense how people have adapted to a world where Hollows are not rare disasters but a constant background threat. Shops sell survival gear beside snacks, and conversations casually reference evacuations the way other cities might mention traffic. This setting quietly reinforces the game’s central tension: normal life persists, but only because people refuse to surrender to fear. The contrast between colorful storefronts and the looming danger of the Hollows creates a tone that is both playful and uneasy, reminding players that every commission is part of a much larger struggle for stability.</p>
                </div>
            </div>
        </div>

        <div class="post-container" id="2">
            <div class="post" onclick="openModal(this);">
                <div class="post-images"> 
                    <img id="post-image-preview" src="ASSETS/IMG/LoadingScreens/2.jpg">
                </div>
                <div class="user-info">
                    <img id="user-pfp" src="ASSETS/IMG/Avatars/Avatar22.png">
                    <h4 id="user-name">Username 2</h4>
                </div>
                <div class="post-content">
                    <h3 class="post-title">The Proxy’s Invisible Burden</h3>
                    <p class="post-desc">Playing as a Proxy is less about heroics and more about responsibility. Unlike many action RPG protagonists, the Proxy rarely takes direct credit for victories. Instead, they coordinate, observe, and manage risk from the sidelines. This narrative choice reframes power as foresight rather than brute strength. Every decision about which agents to deploy, which commissions to accept, and how much danger to tolerate reflects the quiet pressure of leadership. Zenless Zone Zero uses this role to explore how influence often operates unseen. You are not the strongest fighter in the room, but you are the reason the fight happens at all. That subtle shift in perspective adds emotional weight to even routine missions.</p>
                </div>
            </div>
        </div>

        <div class="post-container" id="3">
            <div class="post" onclick="openModal(this);">
                <div class="post-images"> 
                    <img id="post-image-preview" src="ASSETS/IMG/LoadingScreens/3.jpg">
                </div>
                <div class="user-info">
                    <img id="user-pfp" src="ASSETS/IMG/Avatars/Avatar41.png">
                    <h4 id="user-name">Username 3</h4>
                </div>
                <div class="post-content">
                    <h3 class="post-title">Combat That Feels Like Conversation</h3>
                    <p class="post-desc">The flow of battle in Zenless Zone Zero resembles a dialogue more than a brawl. Enemy patterns speak first, agents respond, and perfect dodges or assists feel like well-timed replies. The system rewards attention and rhythm instead of memorization alone. What stands out is how readable the chaos remains, even when multiple effects overlap. Animations communicate intent clearly, and sound cues reinforce timing. This clarity encourages experimentation with team compositions, because success depends less on raw numbers and more on understanding interaction. Over time, combat becomes expressive, letting players develop a personal style that reflects how they read and answer the game’s challenges.</p>
                </div>
            </div>
        </div>

        <div class="post-container" id="4">
            <div class="post" onclick="openModal(this);">
                <div class="post-images"> 
                    <img id="post-image-preview" src="ASSETS/IMG/LoadingScreens/4.jpg">
                </div>
                <div class="user-info">
                    <img id="user-pfp" src="ASSETS/IMG/Avatars/Avatar14.png">
                    <h4 id="user-name">Username 4</h4>
                </div>
                <div class="post-content">
                    <h3 class="post-title">Factions as Living Ideologies</h3>
                    <p class="post-desc">Each faction in Zenless Zone Zero represents more than a combat role; they embody distinct philosophies about survival in a fractured world. Whether it is corporate pragmatism, street-level resilience, or idealistic protection, these groups shape how agents speak, fight, and relate to the city. The result is a narrative ecosystem where conflicts feel ideological as much as practical. When factions collide, it is not just about territory or profit, but about competing visions of what New Eridu should become. This layered design gives even side stories thematic resonance, encouraging players to pay attention to dialogue and background details that quietly define the world’s moral landscape.</p>
                </div>
            </div>
        </div>

        <div class="post-container" id="5">
            <div class="post" onclick="openModal(this);">
                <div class="post-images"> 
                    <img id="post-image-preview" src="ASSETS/IMG/LoadingScreens/5.jpg">
                </div>
                <div class="user-info">
                    <img id="user-pfp" src="ASSETS/IMG/Avatars/Avatar27.png">
                    <h4 id="user-name">Username 5</h4>
                </div>
                <div class="post-content">
                    <h3 class="post-title">Style as Storytelling</h3>
                    <p class="post-desc">Visual style in Zenless Zone Zero is not decoration; it is narrative shorthand. Bold outlines, exaggerated motion, and color-coded effects communicate personality and intent faster than exposition ever could. A character’s fighting stance reveals temperament, while idle animations hint at private habits. Even UI elements reinforce tone, blending urban grit with playful abstraction. This cohesion allows the game to move quickly between comedy and danger without tonal whiplash. Style becomes a language that tells you who to trust, what to fear, and when to relax. In this way, aesthetics do narrative work, making the world readable at a glance.</p>
                </div>
            </div>
        </div>

        <div class="post-container" id="6">
            <div class="post" onclick="openModal(this);">
                <div class="post-images"> 
                    <img id="post-image-preview" src="ASSETS/IMG/LoadingScreens/6.jpg">
                </div>
                <div class="user-info">
                    <img id="user-pfp" src="ASSETS/IMG/Avatars/Avatar17.png">
                    <h4 id="user-name">Username 6</h4>
                </div>
                <div class="post-content">
                    <h3 class="post-title">The Quiet Moments Between Commissions</h3>
                    <p class="post-desc">What lingers after a session is often not the boss fight, but the walk back through the city. Zenless Zone Zero excels at downtime: browsing shops, overhearing conversations, and checking messages from agents. These small interactions ground the high-stakes action in everyday routine. They also remind players that the city survives not because of grand victories, but because people keep opening stores, repairing signs, and telling jokes. This pacing prevents burnout and gives emotional context to danger. By valuing stillness as much as spectacle, the game builds a rhythm that feels sustainable and human.</p>
                </div>
            </div>
        </div>

        <div class="post-container" id="7" >
            <div class="post" onclick="openModal(this);">
                <div class="post-images"> 
                    <img id="post-image-preview" src="ASSETS/IMG/LoadingScreens/7.jpg">
                </div>
                <div class="user-info">
                    <img id="user-pfp" src="ASSETS/IMG/Avatars/Avatar34.png">
                    <h4 id="user-name">Username 7</h4>
                </div>
                <div class="post-content">
                    <h3 class="post-title">Why Progress Feels Meaningful</h3>
                    <p class="post-desc">Advancement in Zenless Zone Zero is structured around relationships as much as stats. Unlocking abilities often coincides with learning more about an agent’s past, motivations, or fears. This intertwining of growth and story makes upgrades feel earned rather than arbitrary. You are not simply optimizing numbers; you are investing in people. The result is a progression loop that reinforces attachment, encouraging players to care about team composition beyond efficiency. In a genre often dominated by abstraction, this approach gives development a narrative spine, turning mechanical improvement into a reflection of trust and shared experience.',0,126</p>
                </div>
            </div>
</div>
        <div class="post-container" id="1">
            <div class="post" onclick="openModal(this);">
                <div class="post-images"> 
                    <img id="post-image-preview" src="ASSETS/IMG/LoadingScreens/1.jpg">
                </div>
                <div class="user-info">
                    <img id="user-pfp" src="ASSETS/IMG/Avatars/Avatar35.png">
                    <h4 id="user-name">Username 1</h4>
                </div>
                <div class="post-content">
                    <h3 class="post-title">Life Inside the Hollow</h3>
                    <p class="post-desc">Zenless Zone Zero does an exceptional job of making New Eridu feel alive, even though the city exists on the edge of total collapse. Walking through Sixth Street, you can sense how people have adapted to a world where Hollows are not rare disasters but a constant background threat. Shops sell survival gear beside snacks, and conversations casually reference evacuations the way other cities might mention traffic. This setting quietly reinforces the game’s central tension: normal life persists, but only because people refuse to surrender to fear. The contrast between colorful storefronts and the looming danger of the Hollows creates a tone that is both playful and uneasy, reminding players that every commission is part of a much larger struggle for stability.</p>
                </div>
            </div>
        </div>

        <div class="post-container" id="2">
            <div class="post" onclick="openModal(this);">
                <div class="post-images"> 
                    <img id="post-image-preview" src="ASSETS/IMG/LoadingScreens/2.jpg">
                </div>
                <div class="user-info">
                    <img id="user-pfp" src="ASSETS/IMG/Avatars/Avatar22.png">
                    <h4 id="user-name">Username 2</h4>
                </div>
                <div class="post-content">
                    <h3 class="post-title">The Proxy’s Invisible Burden</h3>
                    <p class="post-desc">Playing as a Proxy is less about heroics and more about responsibility. Unlike many action RPG protagonists, the Proxy rarely takes direct credit for victories. Instead, they coordinate, observe, and manage risk from the sidelines. This narrative choice reframes power as foresight rather than brute strength. Every decision about which agents to deploy, which commissions to accept, and how much danger to tolerate reflects the quiet pressure of leadership. Zenless Zone Zero uses this role to explore how influence often operates unseen. You are not the strongest fighter in the room, but you are the reason the fight happens at all. That subtle shift in perspective adds emotional weight to even routine missions.</p>
                </div>
            </div>
        </div>

        <div class="post-container" id="3">
            <div class="post" onclick="openModal(this);">
                <div class="post-images"> 
                    <img id="post-image-preview" src="ASSETS/IMG/LoadingScreens/3.jpg">
                </div>
                <div class="user-info">
                    <img id="user-pfp" src="ASSETS/IMG/Avatars/Avatar41.png">
                    <h4 id="user-name">Username 3</h4>
                </div>
                <div class="post-content">
                    <h3 class="post-title">Combat That Feels Like Conversation</h3>
                    <p class="post-desc">The flow of battle in Zenless Zone Zero resembles a dialogue more than a brawl. Enemy patterns speak first, agents respond, and perfect dodges or assists feel like well-timed replies. The system rewards attention and rhythm instead of memorization alone. What stands out is how readable the chaos remains, even when multiple effects overlap. Animations communicate intent clearly, and sound cues reinforce timing. This clarity encourages experimentation with team compositions, because success depends less on raw numbers and more on understanding interaction. Over time, combat becomes expressive, letting players develop a personal style that reflects how they read and answer the game’s challenges.</p>
                </div>
            </div>
        </div>

        <div class="post-container" id="4">
            <div class="post" onclick="openModal(this);">
                <div class="post-images"> 
                    <img id="post-image-preview" src="ASSETS/IMG/LoadingScreens/4.jpg">
                </div>
                <div class="user-info">
                    <img id="user-pfp" src="ASSETS/IMG/Avatars/Avatar14.png">
                    <h4 id="user-name">Username 4</h4>
                </div>
                <div class="post-content">
                    <h3 class="post-title">Factions as Living Ideologies</h3>
                    <p class="post-desc">Each faction in Zenless Zone Zero represents more than a combat role; they embody distinct philosophies about survival in a fractured world. Whether it is corporate pragmatism, street-level resilience, or idealistic protection, these groups shape how agents speak, fight, and relate to the city. The result is a narrative ecosystem where conflicts feel ideological as much as practical. When factions collide, it is not just about territory or profit, but about competing visions of what New Eridu should become. This layered design gives even side stories thematic resonance, encouraging players to pay attention to dialogue and background details that quietly define the world’s moral landscape.</p>
                </div>
            </div>
        </div>

        <div class="post-container" id="5">
            <div class="post" onclick="openModal(this);">
                <div class="post-images"> 
                    <img id="post-image-preview" src="ASSETS/IMG/LoadingScreens/5.jpg">
                </div>
                <div class="user-info">
                    <img id="user-pfp" src="ASSETS/IMG/Avatars/Avatar27.png">
                    <h4 id="user-name">Username 5</h4>
                </div>
                <div class="post-content">
                    <h3 class="post-title">Style as Storytelling</h3>
                    <p class="post-desc">Visual style in Zenless Zone Zero is not decoration; it is narrative shorthand. Bold outlines, exaggerated motion, and color-coded effects communicate personality and intent faster than exposition ever could. A character’s fighting stance reveals temperament, while idle animations hint at private habits. Even UI elements reinforce tone, blending urban grit with playful abstraction. This cohesion allows the game to move quickly between comedy and danger without tonal whiplash. Style becomes a language that tells you who to trust, what to fear, and when to relax. In this way, aesthetics do narrative work, making the world readable at a glance.</p>
                </div>
            </div>
        </div>

        <div class="post-container" id="6">
            <div class="post" onclick="openModal(this);">
                <div class="post-images"> 
                    <img id="post-image-preview" src="ASSETS/IMG/LoadingScreens/6.jpg">
                </div>
                <div class="user-info">
                    <img id="user-pfp" src="ASSETS/IMG/Avatars/Avatar17.png">
                    <h4 id="user-name">Username 6</h4>
                </div>
                <div class="post-content">
                    <h3 class="post-title">The Quiet Moments Between Commissions</h3>
                    <p class="post-desc">What lingers after a session is often not the boss fight, but the walk back through the city. Zenless Zone Zero excels at downtime: browsing shops, overhearing conversations, and checking messages from agents. These small interactions ground the high-stakes action in everyday routine. They also remind players that the city survives not because of grand victories, but because people keep opening stores, repairing signs, and telling jokes. This pacing prevents burnout and gives emotional context to danger. By valuing stillness as much as spectacle, the game builds a rhythm that feels sustainable and human.</p>
                </div>
            </div>
        </div>-->