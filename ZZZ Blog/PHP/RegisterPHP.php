<?php 
    session_start();
    
    $Email = $_POST["Email1"];
    $Username = $_POST["Username1"];
    $Password = $_POST["Password1"];

    $hostname = "localhost";
    $username = "root";
    $password = "";
    $database = "zzz_2";

    $emailExists = false;
    $usernameExists = false;

    $conn = mysqli_connect($hostname, $username, "", $database);
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $query = "SELECT * FROM utente";
    $result = mysqli_query($conn, $query);

    while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
        if($row["Email"] === $Email){
            $emailExists = true;
        }
        if($row["Username"] === $Username){
            $usernameExists = true;
        }
    }

    if ($emailExists || $usernameExists) {
        $params = [];
        if ($emailExists) $params[] = "emailError=1";
        if ($usernameExists) $params[] = "usernameError=1";
        $queryString = implode("&", $params);
        header("Location: ../Register.php?" . $queryString);  
        exit;
    }

    $sql = "INSERT INTO utente (Email, Username, PasswordHash)
        VALUES ('$Email', '$Username', '$Password')";
    mysqli_query($conn, $sql);

    if (!mysqli_query($conn, $sql)) {
        echo "Errore: " . mysqli_error($conn);
    }

    mysqli_close($conn);

    header("Location: ../index.php");
    exit;
?>