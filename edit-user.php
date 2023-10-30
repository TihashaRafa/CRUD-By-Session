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
</head>
<body>
    <h2>Edit User: <?php echo $editUserDetails['storedUsername']; ?></h2>
    <form method="post" action="edit-user.php?username=<?php echo $editUserDetails['storedUsername']; ?>">
        Email: <input type="email" name="email" value="<?php echo $editUserDetails['storedEmail']; ?>"><br>
        Username: <input type="text" name="username" value="<?php echo $editUserDetails['storedUsername']; ?>"><br>
        Password: <input type="password" name="password" value="<?php echo $editUserDetails['storedPassword']; ?>"><br>
        <input type="submit" name="update" value="Update">
    </form>
    <a href="index.php">Back to User List</a>
</body>
</html>
