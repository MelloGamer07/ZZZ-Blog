<?php
    $hostname = "localhost";
    $username = "root";
    $password = "";
    $database = "zzz_2";

    $conn = mysqli_connect($hostname, $username, $password, $database);
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Pagination settings
    $articlesPerPage = 20;
    $currentPage = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
    $offset = ($currentPage - 1) * $articlesPerPage;

    // Get total article count
    $countResult = mysqli_query($conn, "SELECT COUNT(*) AS total FROM Articolo");
    $countRow = mysqli_fetch_assoc($countResult);
    $totalArticles = $countRow['total'];
    $totalPages = ceil($totalArticles / $articlesPerPage);

    // Clamp currentPage to valid range
    $currentPage = min($currentPage, max(1, $totalPages));
    $offset = ($currentPage - 1) * $articlesPerPage;

    $query = "
        SELECT 
            a.Id AS ArticoloId,
            u.Id AS UtenteId,
            a.Img,
            a.Title,
            a.Descrizione,
            a.DataCreazione,
            u.Username,
            u.Avatar
        FROM Articolo a
        JOIN Utente u ON a.IdUtente = u.Id
        ORDER BY a.DataCreazione DESC
        LIMIT $articlesPerPage OFFSET $offset
    ";

    $result = mysqli_query($conn, $query);

    function renderNavbar($currentPage, $totalPages) {
        if ($totalPages <= 0) return;

        $prevPage = $currentPage - 1;
        $nextPage = $currentPage + 1;

        $window = 2;
        $startPage = max(1, $currentPage - $window);
        $endPage   = min($totalPages, $currentPage + $window);

        echo '<nav class="pagination-nav">';

        $hash = '#InterKnot/';

        if ($currentPage > 1) {
            echo '<a class="page-btn" href="?page=' . $prevPage . $hash . '">&#8592; Prev</a>';
        } else {
            echo '<span class="page-btn disabled">&#8592; Prev</span>';
        }

        if ($startPage > 1) {
            echo '<a class="page-btn" href="?page=1' . $hash . '">1</a>';
            if ($startPage > 2) {
                echo '<span class="page-ellipsis">…</span>';
            }
        }

        for ($i = $startPage; $i <= $endPage; $i++) {
            $activeClass = ($i === $currentPage) ? ' active' : '';
            echo '<a class="page-btn' . $activeClass . '" href="?page=' . $i . $hash . '">' . $i . '</a>';
        }

        if ($endPage < $totalPages) {
            if ($endPage < $totalPages - 1) {
                echo '<span class="page-ellipsis">…</span>';
            }
            echo '<a class="page-btn" href="?page=' . $totalPages . $hash . '">' . $totalPages . '</a>';
        }

        if ($currentPage < $totalPages) {
            echo '<a class="page-btn" href="?page=' . $nextPage . $hash . '">Next &#8594;</a>';
        } else {
            echo '<span class="page-btn disabled">Next &#8594;</span>';
        }

        echo '</nav>';
    }
?>

<?php renderNavbar($currentPage, $totalPages); ?>

<div class="posts-container">
<?php
    while ($row = mysqli_fetch_assoc($result)) {
        $imgPath = __DIR__ . '/' . $row['Img'];

        if (!file_exists($imgPath) || empty($row['Img'])) {
            $row['Img'] = 'ASSETS/IMG/UI/plus.png';
        }

        echo '
        <div class="post-container" id="' . $row['ArticoloId'] . '">
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
</div>

<?php renderNavbar($currentPage, $totalPages); ?>
