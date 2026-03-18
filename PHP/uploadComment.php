<?php
$hostname = "localhost";
$username = "root";
$password = "";
$database = "zzz_2";

$conn = mysqli_connect($hostname, $username, "", $database);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

session_start();
$IDUsername = $_SESSION['IdUsername'];

$comment = addslashes($_POST['post_comment']);
$IDArticolo = $_POST['article_id'];

if ($comment !== "" && strlen($comment) <= 400) {
    $query = "INSERT INTO Commento (Content, IdUtente, IdArticolo) VALUES ('$comment','$IDUsername','$IDArticolo')";

    if ($conn->query($query) === TRUE) {
        header("Location: ../home.php#InterKnot/idArticle=" . $IDArticolo);
    } else {
        echo "Database error: " . $conn->error;
    }
}

else {
    header("Location: ../home.php#InterKnot/idArticle=" . $IDArticolo);
}

session_write_close();
$conn->close();

?>
