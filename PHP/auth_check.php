<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!empty($_SESSION['IdUsername'])) {
    return;
}

if (!empty($_COOKIE['remember_token'])) {
    $token = $_COOKIE['remember_token'];

    $hostname = "localhost";
    $dbuser   = "root";
    $database = "zzz_2";

    $conn = mysqli_connect($hostname, $dbuser, "", $database);
    if ($conn) {
        $stmt = $conn->prepare("
            SELECT u.Id, u.Username, u.Avatar
            FROM RememberTokens rt
            JOIN Utente u ON u.Id = rt.IdUtente
            WHERE rt.Token = ? AND rt.DataScadenza > NOW()
            LIMIT 1
        ");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $stmt->bind_result($id, $username, $avatar);

        if ($stmt->fetch()) {
            // Valid token — restore session
            $_SESSION['IdUsername'] = $id;
            $_SESSION['Username']   = $username;
            $_SESSION['IdAvatar']   = $avatar;

            // Roll the token (issue a fresh one to prevent reuse)
            $stmt->close();
            $newToken = bin2hex(random_bytes(32));
            $expires  = date('Y-m-d H:i:s', strtotime('+30 days'));

            $upd = $conn->prepare("UPDATE RememberTokens SET Token = ?, DataScadenza = ? WHERE Token = ?");
            $upd->bind_param("sss", $newToken, $expires, $token);
            $upd->execute();
            $upd->close();

            setcookie('remember_token', $newToken, [
                'expires'  => time() + (30 * 24 * 60 * 60),
                'path'     => '/',
                'httponly' => true,
                'samesite' => 'Lax'
            ]);
        } else {
            // Token expired or invalid — clear the cookie
            $stmt->close();
            setcookie('remember_token', '', ['expires' => time() - 3600, 'path' => '/']);
        }

        mysqli_close($conn);
    }
}