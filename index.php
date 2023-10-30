<?php
session_start();

if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION["username"];
$isAdmin = ($username === "Rafa");

$users = file('users.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

// Delete operation
if ($isAdmin && isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['username'])) {
    $deletedUsername = $_GET['username'];

    $users = array_filter($users, function ($user) use ($deletedUsername) {
        list($u,) = explode(',', $user);
        return trim($u) !== trim($deletedUsername);
    });
    file_put_contents('users.txt', implode("\n", $users));

    $_SESSION['alert_message'] = "User $deletedUsername deleted successfully!";
    header("Location: index.php");
    exit();
}


if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["update"])) {
    $newEmail = $_POST['email'];
    $userIndex = array_search("$editUserDetails[storedUsername],$editUserDetails[storedEmail],$editUserDetails[storedPassword]", $users);

    if ($userIndex !== false) {
        $users[$userIndex] = "$editUserDetails[storedUsername],$newEmail,$editUserDetails[storedPassword]";
        file_put_contents('users.txt', implode("\n", $users));

        // Add a success message
        $_SESSION['alert_message'] = "User $editUserDetails[storedUsername] updated successfully!";

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
    <title>Welcome</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container mt-5">
        <?php
        if (isset($_SESSION['alert_message'])) {
            echo "<div class='alert alert-success'>";
            echo $_SESSION['alert_message'];
            echo "</div>";
            unset($_SESSION['alert_message']);
        }
        ?>
        <div class="row">
            <div class="col">
                <h2>Welcome, <?php echo $username; ?>!</h2>
                <p>This is the index page. You are logged in.</p>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col">
                <h3>User List:</h3>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Username</th>
                            <th>Email</th>
                            <?php if ($isAdmin) : ?>
                                <th>Action</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($users as $user) {
                            list($displayedUsername, $displayedEmail, $displayedPassword) = explode(',', $user);
                            echo "<tr>";
                            echo "<td>$displayedUsername</td>";
                            echo "<td>$displayedEmail</td>";
                            if ($isAdmin) {
                                echo "<td>";
                                echo "<a href='edit-user.php?username=$displayedUsername' class='btn btn-primary btn-sm mr-2'>Edit</a>";
                                echo "<a href='index.php?action=delete&username=$displayedUsername' class='btn btn-danger btn-sm' onclick=\"return confirm('Are you sure you want to delete this user?')\">Delete</a>";
                                echo "</td>";
                            }
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <!-- New column for the "Create User" link on the right side -->
            <?php if ($isAdmin) : ?>
                <div class="col-md-3">
                    <a href="create-user.php" class="btn btn-success btn-block">Create User</a>
                </div>
            <?php endif; ?>
        </div>

        <div class="row mt-3">
            <div class="col">
                <a href="logout.php" class="btn btn-warning">Logout</a>
            </div>
        </div>
    </div>

    <!-- Include Bootstrap JS and jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
