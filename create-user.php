<?php
session_start();

if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION["username"];
$isAdmin = ($username === "Rafa");

if ($isAdmin && isset($_POST['create_user'])) {
    $newUsername = $_POST['new_username'];
    $newEmail = $_POST['new_email'];
    $newPassword = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

    file_put_contents('users.txt', "$newUsername,$newEmail,$newPassword\n", FILE_APPEND);

    $_SESSION['message'] = "New user created successfully!";
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create User</title>

    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container mt-5">
        <?php if ($isAdmin) : ?>
            <div class="row">
                <div class="col-md-6 offset-md-3">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h3 class="mb-0">Create New User</h3>
                        </div>
                        <div class="card-body">
                            <form action="create-user.php" method="post">
                                <div class="form-group">
                                    <label for="new_username">Username:</label>
                                    <input type="text" class="form-control" id="new_username" name="new_username" required>
                                </div>
                                <div class="form-group">
                                    <label for="new_email">Email:</label>
                                    <input type="email" class="form-control" id="new_email" name="new_email" required>
                                </div>
                                <div class="form-group">
                                    <label for="new_password">Password:</label>
                                    <input type="password" class="form-control" id="new_password" name="new_password" required>
                                </div>
                                <input type="submit" class="btn btn-primary btn-block" name="create_user" value="Create User">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <div class="row mt-3">
            <div class="col">
                <a href="index.php" class="btn btn-secondary">Back to User List</a>
            </div>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>

</html>