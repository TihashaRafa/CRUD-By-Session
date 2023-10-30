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

    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container">
    <div class="row justify-content-center mt-5">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0">Registration Form</h3>
                </div>
                <div class="card-body">
                    <form method="post" action="register.php">
                        <div class="form-group">
                            <label for="username">Username:</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email:</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Password:</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <input type="submit" class="btn btn-primary btn-block" name="register" value="Register">
                    </form>
                </div>
                <div class="card-footer text-right">
                    <a href="login.php" class="text-muted">Login Here</a>
                </div>
            </div>
        </div>
    </div>
</div>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>

