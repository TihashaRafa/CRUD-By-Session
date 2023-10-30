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

    // Check admin
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
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container">
    <div class="row justify-content-center mt-5">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0">Login Form</h3>

                </div>
                <div class="card-body">
                    <form method="post" action="login.php">
                        <div class="form-group">
                            <label for="email">Email:</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Password:</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <input type="submit" class="btn btn-primary btn-block" name="login" value="Login">
                    </form>
                </div>
                <div class="card-footer text-right">
                    <a href="register.php" class="text-muted">Registration Here</a>
                </div>

                
                <h5>Admin Email: rafa@gmail.com</h5>
                <h5>Admin Password: Rafa.123</h5>
            </div>
        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>