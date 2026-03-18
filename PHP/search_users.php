<?php
header('Content-Type: application/json');

$hostname = "localhost";
$username = "root";
$password = "";
$database = "zzz_2";

$conn = mysqli_connect($hostname, $username, $password, $database);
if (!$conn) { echo json_encode([]); exit; }

$q = isset($_GET['q']) ? mysqli_real_escape_string($conn, trim($_GET['q'])) : '';

if (strlen($q) < 1) {
    echo json_encode([]);
    exit;
}

$result = mysqli_query($conn, "
    SELECT Id, Username, Avatar 
    FROM Utente 
    WHERE Username LIKE '%$q%' 
    ORDER BY Username ASC 
    LIMIT 8
");

$users = [];
while ($row = mysqli_fetch_assoc($result)) {
    $users[] = $row;
}

echo json_encode($users);
mysqli_close($conn);
?>