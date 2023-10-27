<?php
session_start();

if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION["username"];
$isAdmin = ($username === "Rafa");

$users = file('users.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
</head>
<body>
    <h2>Welcome, <?php echo $username; ?>!</h2>
    <p>This is the index page. You are logged in.</p>

    <h3>User List:</h3>
    <table border="1">
        <tr>
            <th>Username</th>
            <th>Email</th>
            <?php if ($isAdmin): ?>
                <th>Action</th>
            <?php endif; ?>
        </tr>
        <?php
        foreach ($users as $user) {
            list($displayedUsername, $displayedEmail, $displayedPassword) = explode(',', $user);
            echo "<tr>";
            echo "<td>$displayedUsername</td>";
            echo "<td>$displayedEmail</td>";
            if ($isAdmin) {
                echo "<td>";
                echo "<a href='edit-user.php?username=$displayedUsername'>Edit </a>";
                echo "<a href='create-user.php?username=$displayedUsername'>Create User </a>";
                echo "<a href='delete.php?username=$displayedUsername'>Delete</a> | ";
                echo "</td>";
            }
            echo "</tr>";
        }
        ?>
    </table>

    <a href="logout.php">Logout</a>

</body>
</html>
