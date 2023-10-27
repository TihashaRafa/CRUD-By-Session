<?php
session_start();

function cleanInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function authenticateUser($email, $password) {
    $users = file('users.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    // Check if the user is the admin
    if ($email === "rafa@gmail.com" && $password === "Rafa.123") {
        return "Rafa";
    }

    foreach ($users as $user) {
        list($username, $storedEmail, $storedPassword) = explode(',', $user);
        if ($email === $storedEmail && password_verify($password, trim($storedPassword))) {
            return $username;
        }
    }
    return false;
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["login"])) {
    $email = cleanInput($_POST["email"]);
    $password = cleanInput($_POST["password"]);

    if (!empty($email) && !empty($password)) {
        $username = authenticateUser($email, $password);
        if ($username) {
            $_SESSION["username"] = $username;
            header("Location: index.php");
            exit();
        } else {
            echo "Invalid email or password.";
        }
    } else {
        echo "Please fill in all the fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <h2>Login Form</h2>
    <form method="post" action="login.php">
        Email: <input type="email" name="email" required><br>
        Password: <input type="password" name="password" required><br>
        <input type="submit" name="login" value="Login">
    </form>
</body>
</html>
