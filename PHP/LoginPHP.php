<?php 

function passwordChecks($Password){
    return (
        strlen($Password) >= 8 &&
        preg_match('/[A-Z]/', $Password) &&
        preg_match('/[a-z]/', $Password) &&
        preg_match('/\d/', $Password) &&
        preg_match('/[!@#$%^&*]/', $Password) &&
        !preg_match('/\s/', $Password)
    );
}

$Username = $_POST["Username1"];
$Password = $_POST["Password1"];

$usernameExists = false;
$passwordExists = false;

session_start();

$hostname = "localhost";
$username = "root";
$database = "zzz_2";

$conn = mysqli_connect($hostname, $username, "", $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$query = "SELECT * FROM utente";
$result = mysqli_query($conn, $query);

while($row = mysqli_fetch_assoc($result)){

    if($row["Username"] === $Username){
        $usernameExists = true;

        if(password_verify($Password, $row["PasswordHash"]) && passwordChecks($Password)) {
            $_SESSION['Username'] = $Username;
            $_SESSION['IdUsername'] = $row['Id'];
            $_SESSION['IdAvatar'] = $row['Avatar'];

            $passwordExists = true;

            // --- Persistent login: store a secure token in DB and cookie ---
            $token   = bin2hex(random_bytes(32)); // 64-char hex token
            $expires = date('Y-m-d H:i:s', strtotime('+30 days'));
            $userId  = intval($row['Id']);

            // Clean up any old tokens for this user
            $stmt = $conn->prepare("DELETE FROM RememberTokens WHERE IdUtente = ?");
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $stmt->close();

            // Insert new token
            $stmt = $conn->prepare("INSERT INTO RememberTokens (IdUtente, Token, DataScadenza) VALUES (?, ?, ?)");
            $stmt->bind_param("iss", $userId, $token, $expires);
            $stmt->execute();
            $stmt->close();

            // Set cookie for 30 days (httponly + samesite for security)
            $cookieExpiry = time() + (30 * 24 * 60 * 60);
            setcookie('remember_token', $token, [
                'expires'  => $cookieExpiry,
                'path'     => '/',
                'httponly' => true,
                'samesite' => 'Lax'
            ]);

            session_write_close();
            break;
        }
    }
}

mysqli_close($conn);

if (!$usernameExists || !$passwordExists) {
    $params = [];
    if (!$usernameExists) $params[] = "usernameError=1";
    if (!$passwordExists) $params[] = "passwordError=1";
    $queryString = implode("&", $params);
    header("Location: ../loginIndex.php?" . $queryString); 
    exit;
}

header("Location: ../home.php");
exit;

?>