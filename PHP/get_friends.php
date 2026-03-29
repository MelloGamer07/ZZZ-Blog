<?php
/**
 * PHP/get_friends.php
 * Returns a paginated, newest-first list of friends for a given user.
 *
 * GET params:
 *   id       – profile user ID (required)
 *   page     – page number, 1-based (default 1)
 *   per_page – items per page, max 50 (default 15)
 *
 * Response JSON:
 *   { friends: [{Id, Username, Avatar}, …], total, pages, page }
 */

session_start();
header('Content-Type: application/json');

$hostname = "localhost";
$username = "root";
$password = "";
$database = "zzz_2";

$conn = mysqli_connect($hostname, $username, $password, $database);
if (!$conn) {
    echo json_encode(['error' => 'DB connection failed']);
    exit;
}

$profileId = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($profileId <= 0) {
    echo json_encode(['error' => 'Invalid user ID']);
    exit;
}

$perPage = min(50, max(1, intval($_GET['per_page'] ?? 15)));
$page    = max(1, intval($_GET['page'] ?? 1));
$offset  = ($page - 1) * $perPage;

// Total count
$countResult = mysqli_query($conn, "
    SELECT COUNT(*) AS cnt
    FROM Follow
    WHERE IdUtente = $profileId
");
$total = (int)(mysqli_fetch_assoc($countResult)['cnt'] ?? 0);
$pages = max(1, (int)ceil($total / $perPage));

// Friends for this page, newest first
$result = mysqli_query($conn, "
    SELECT u.Id, u.Username, u.Avatar
    FROM Follow f
    JOIN Utente u ON u.Id = f.IDUtenteFollow
    WHERE f.IdUtente = $profileId
    ORDER BY u.Username ASC
    LIMIT $perPage OFFSET $offset
");

$friends = [];
while ($row = mysqli_fetch_assoc($result)) {
    $friends[] = $row;
}

mysqli_close($conn);

echo json_encode([
    'friends' => $friends,
    'total'   => $total,
    'pages'   => $pages,
    'page'    => $page,
]);