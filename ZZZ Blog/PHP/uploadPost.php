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

// Check if form submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $conn->real_escape_string($_POST['post_title']);
    $text = $conn->real_escape_string($_POST['post_text']);

    // File upload
    if (isset($_FILES['post_image']) && $_FILES['post_image']['error'] === 0) {
        $fileTmp = $_FILES['post_image']['tmp_name'];
        $fileName = time() . "_" . $_FILES['post_image']['name']; // unique name
        $filePath = "ASSETS/uploads/" . $fileName;

        // Create uploads folder if it doesn't exist
        if (!is_dir('../ASSETS/uploads')) {
            mkdir('../ASSETS/uploads', 0777, true);
        }

        if (move_uploaded_file($fileTmp, $filePath)) {
            // Save data in database
            $query = "INSERT INTO articolo (IDUser, Title, Descrizione, Img) VALUES ('$IDUsername','$title', '$text', '$filePath')";
            if ($conn->query($query) === TRUE) {
                header("Location: ../home.php?");
            } else {
                echo "Database error: " . $conn->error;
            }
        } else {
            echo "Failed to upload file.";
        }
    } else {
        echo "No file uploaded or upload error.";
    }
}

$conn->close();
?>
