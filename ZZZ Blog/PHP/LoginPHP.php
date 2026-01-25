<?php 
    $Username = $_POST["Username1"];
    $Password = $_POST["Password1"];

    $hostname = "localhost";
    $username = "root";
    $password = "";
    $database = "zzz_2";

    $usernameExists = false;
    $passwordExists = false;

    $conn = mysqli_connect($hostname, $username, "", $database);
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $query = "SELECT * FROM utente";
    $result = mysqli_query($conn, $query);

    while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
        if($row["Username"] === $Username){
            $usernameExists = true;
        }
        if($row["PasswordHash"] === $Password){
            $passwordExists = true;
        }
    }

    if (!$usernameExists || !$passwordExists) {
        $params = [];
        if (!$usernameExists) $params[] = "usernameError=1";
        if (!$passwordExists) $params[] = "passwordError=1";
        $queryString = implode("&", $params);
        header("Location: ../home.php?" . $queryString); 
        exit;
    }

    mysqli_close($conn);

    header("Location: ../home.php");
    exit;
?>