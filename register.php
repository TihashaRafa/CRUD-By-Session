<?php
session_start();

function cleanInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function saveUserDetails($username, $email, $password) {
    $userDetails = "$username,$email,$password\n";
    file_put_contents('users.txt', $userDetails, FILE_APPEND | LOCK_EX);
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["register"])) {
    $username = cleanInput($_POST["username"]);
    $email = cleanInput($_POST["email"]);
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

    if (!empty($username) && !empty($email) && !empty($_POST["password"])) {
        saveUserDetails($username, $email, $password);
        $_SESSION["username"] = $username;  
        header("Location: index.php");  
        exit();
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
    <title>Registration</title>
</head>
<body>
    <h2>Registration Form</h2>
    <form method="post" action="register.php">
        Username: <input type="text" name="username" required><br>
        Email: <input type="email" name="email" required><br>
        Password: <input type="password" name="password" required><br>
        <input type="submit" name="register" value="Register">
    </form>
</body>
</html>
