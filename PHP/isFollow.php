<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

if (!isset($_SESSION['IdUsername'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Not logged in']);
    exit;
}

$IDUser       = intval($_SESSION['IdUsername']);
$targetUserId = intval($_POST['user_id'] ?? 0);

if ($targetUserId <= 0 || $targetUserId === $IDUser) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid target user']);
    exit;
}

$hostname = "localhost";
$username = "root";
$password = "";
$database = "zzz_2";

$conn = mysqli_connect($hostname, $username, $password, $database);
if (!$conn) {
    http_response_code(500);
    echo json_encode(['error' => 'DB connection failed']);
    exit;
}

$stmt = $conn->prepare("SELECT 1 FROM Follow WHERE IdUtente = ? AND IDUtenteFollow = ?");
$stmt->bind_param("ii", $IDUser, $targetUserId);
$stmt->execute();
$stmt->store_result();
$alreadyFollowing = $stmt->num_rows > 0;
$stmt->close();

if ($alreadyFollowing) {
    $stmt = $conn->prepare("DELETE FROM Follow WHERE IdUtente = ? AND IDUtenteFollow = ?");
    $stmt->bind_param("ii", $IDUser, $targetUserId);
    $stmt->execute();
    $stmt->close();
    echo json_encode(['following' => false]);
} else {
    $stmt = $conn->prepare("INSERT INTO Follow (IdUtente, IDUtenteFollow) VALUES (?, ?)");
    $stmt->bind_param("ii", $IDUser, $targetUserId);
    $stmt->execute();
    $stmt->close();
    echo json_encode(['following' => true]);
}

mysqli_close($conn);
?>