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
            session_write_close();
            break;
        }
    }
}

if (!$usernameExists || !$passwordExists) {
    $params = [];
    if (!$usernameExists) $params[] = "usernameError=1";
    if (!$passwordExists) $params[] = "passwordError=1";
    $queryString = implode("&", $params);
    header("Location: ../loginIndex.php?" . $queryString); 
    exit;
}

mysqli_close($conn);

header("Location: ../home.php");
exit;

?>
