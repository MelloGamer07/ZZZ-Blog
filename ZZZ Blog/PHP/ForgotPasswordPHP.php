<?php 
    $Email = $_POST["Email1"];
    $Password = $_POST["Password1"];

    $hostname = "localhost";
    $username = "root";
    $password = "";
    $database = "zzz_2";

    $emailExists = false;
    $passwordMatches = false; 

    $conn = mysqli_connect($hostname, $username, $password, $database);
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $query = "SELECT * FROM utente";
    $result = mysqli_query($conn, $query);

    while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
        if($row["Email"] === $Email){
            $emailExists = true;
            if ($row["PasswordHash"] === $Password) {
                $passwordMatches = true;  
            }
            break; 
        }
    }

    if (!$emailExists || $passwordMatches) {
        $params = [];
        if (!$emailExists) $params[] = "emailError=1";
        if ($passwordMatches) $params[] = "passwordError=1";
        $queryString = implode("&", $params);
        header("Location: ../ForgotPassword.php?" . $queryString);
        exit;
    }

    $sql = "UPDATE utente SET PasswordHash='$Password' WHERE Email='$Email'";
    mysqli_query($conn, $sql);

    if (!mysqli_query($conn, $sql)) {
        echo "Errore: " . mysqli_error($conn);
    }

    mysqli_close($conn);
    header("Location: ../index.php");
    exit;
?>