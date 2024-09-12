<?php
session_start();
$error = '';

// Check if the user is already logged in via session
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header('Location: dashboard.php');
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // Validate credentials
    if ($username === 'pathirana-motors' && $password === 'pm711217') {
        $_SESSION['loggedin'] = true;

        // Set cookies to remember the login for 7 days
        setcookie('username', $username, time() + (86400 * 7), "/"); // 86400 = 1 day
        setcookie('password', hash('sha256', $password), time() + (86400 * 7), "/");

        header('Location: dashboard.php');
        exit;
    } else {
        $error = 'Invalid username or password!';
    }
}

// Check if the user is already logged in via cookies
if (isset($_COOKIE['username']) && isset($_COOKIE['password'])) {
    if ($_COOKIE['username'] === 'pathirana-motors' && $_COOKIE['password'] === hash('sha256', 'pm711217')) {
        $_SESSION['loggedin'] = true;
        header('Location: dashboard.php');
        exit;
    }
}

// Define custom links for the login page
$customLinks = [
    'contact' => ['title' => 'Contact', 'href' => '#contact'],
    'about' => ['title' => 'About', 'href' => '#about']
];

// Set the current page
$page = 'login';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <title>Login - Pathirana Motors</title>
</head>
<body class="bg-blue-900 min-h-screen flex flex-col">
    <?php include 'navbar.php'; ?>

    <div class="flex-grow flex justify-center items-center">
        <div class="bg-gray-800 p-8 rounded shadow-md w-96">
            <h2 class="text-2xl font-semibold text-white mb-4">Login</h2>
            <?php if ($error): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>
            <form action="login.php" method="POST">
                <div class="mb-4">
                    <label for="username" class="block text-white">Username</label>
                    <input type="text" name="username" id="username" class="w-full p-2 border border-gray-600 rounded bg-gray-700 text-white" required>
                </div>
                <div class="mb-4">
                    <label for="password" class="block text-white">Password</label>
                    <input type="password" name="password" id="password" class="w-full p-2 border border-gray-600 rounded bg-gray-700 text-white" required>
                </div>
                <button type="submit" class="w-full bg-yellow-400 text-gray-800 py-2 rounded hover:bg-yellow-500">Login</button>
            </form>
        </div>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>
