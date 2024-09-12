<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

// Define custom links for the dashboard page
$customLinks = [
    'Add Item' => ['title' => 'Add Item', 'href' => 'add_item.php'],
    'about' => ['title' => 'About', 'href' => '#about'],
    'All Items' => ['title' => 'All Items', 'href' => 'view_items.php'],
    'logout' => ['title' => 'Logout', 'href' => 'logout.php']
];

// Show logout button
$showLogout = true;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <title>Dashboard - Pathirana Motors</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-blue-900 min-h-screen flex flex-col">
    <?php include 'navbar.php'; ?>

    <div class="container mx-auto mt-8 flex-grow">
        <h1 class="text-3xl font-semibold text-white mb-4">Welcome to the Dashboard</h1>

        <!-- Customer Information Form -->
        <form id="customer-form" class="bg-gray-800 p-6 rounded shadow-md mb-6">
            <h2 class="text-2xl font-semibold text-white mb-4">Customer Information</h2>
            <div class="mb-4">
                <label for="customer-name" class="block text-white">Customer Name</label>
                <input type="text" id="customer-name" name="customer-name" class="w-full p-2 border border-gray-600 rounded bg-gray-700 text-white" required>
            </div>
            <div class="mb-4">
                <label for="customer-phone" class="block text-white">Phone Number</label>
                <input type="text" id="customer-phone" name="customer-phone" class="w-full p-2 border border-gray-600 rounded bg-gray-700 text-white" required>
            </div>
        </form>

        <!-- Billing Page Link -->
        <a href="billing.php" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Go to Billing Page</a>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>
