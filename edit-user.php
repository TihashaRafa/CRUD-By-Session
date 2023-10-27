<?php
session_start();

if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION["username"];
$isAdmin = ($username === "Rafa");

if (!$isAdmin) {
    // Redirect non-admin users to index.php
    header("Location: index.php");
    exit();
}

// Read user details from the file
$users = file('users.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

// Get the username from the URL parameter
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
    // If user not found, redirect to index.php
    header("Location: index.php");
    exit();
}

// Handle form submission to update user details
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["update"])) {
    // Retrieve updated details from the form
    $newEmail = $_POST['email'];

    // Update the user details in the array
    $userIndex = array_search("$editUserDetails[storedUsername],$editUserDetails[storedEmail],$editUserDetails[storedPassword]", $users);
    if ($userIndex !== false) {
        $users[$userIndex] = "$editUserDetails[storedUsername],$newEmail,$editUserDetails[storedPassword]";
    }

    // Save the updated user details back to the file
    file_put_contents('users.txt', implode("\n", $users));

    // Redirect back to the index page
    header("Location: index.php");
    exit();
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
