<?php
session_start();

if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION["username"];
$isAdmin = ($username === "Rafa");

if (!$isAdmin) {
    header("Location: index.php");
    exit();
}

$users = file('users.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

$editUsername = isset($_GET['username']) ? $_GET['username'] : null;

// Find the user details to edit
$editUserDetails = null;
foreach ($users as &$user) {
    list($storedUsername, $storedEmail, $storedPassword) = explode(',', $user);
    if ($storedUsername === $editUsername) {
        $editUserDetails = compact('storedUsername', 'storedEmail', 'storedPassword');
        break;
    }
}

if (!$editUserDetails) {
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["update"])) {
    $newEmail = $_POST['email'];
    $newUsername = $_POST['username'];
    $newPassword = $_POST['password'];

    // Update user details
    $userIndex = array_search("$editUserDetails[storedUsername],$editUserDetails[storedEmail],$editUserDetails[storedPassword]", $users);
    if ($userIndex !== false) {
        unset($users[$userIndex]);
        $users[] = "$newUsername,$newEmail,$newPassword";
        file_put_contents('users.txt', implode("\n", $users));

        $_SESSION['alert_message'] = "User $newUsername updated successfully!";
        header("Location: index.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>

    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="row">
                <div class="col-md-6 offset-md-3">
                    <div class="card">
<div class="card-header bg-primary text-white">
<h2>Edit User: <?php echo $editUserDetails['storedUsername']; ?></h2>
                        </div>
                        <div class="card-body">
    <form method="post" action="edit-user.php?username=<?php echo $editUserDetails['storedUsername']; ?>">
    <div class="form-group">
                                    <label for="new_username">Email:</label>
                                    <input type="email" name="email" class="form-control"  value="<?php echo $editUserDetails['storedEmail']; ?>">
                                </div>


                                <div class="form-group">
                                    <label for="new_username">Username:</label>
                                    <input type="text" name="username" class="form-control" value="<?php echo $editUserDetails['storedUsername']; ?>">
                                </div>

                                <div class="form-group">
                                    <label for="new_username">Password:</label>
                                    <input type="password" name="password"  class="form-control" value="<?php echo $editUserDetails['storedPassword']; ?>">
                                </div>
      
       
        <input type="submit" name="update" value="Update">
    </form>
    <a href="index.php">Back to User List</a>

    </div>
    </div>
    </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
