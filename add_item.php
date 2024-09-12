<?php
require 'db.php'; // Include database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize form inputs
    $name = htmlspecialchars($_POST['name']);
    $item_id = htmlspecialchars($_POST['item_id']);
    $quantity = intval($_POST['quantity']);
    $price = floatval($_POST['price']);
    $description = isset($_POST['description']) ? htmlspecialchars($_POST['description']) : '';
    $image_url = isset($_POST['image_url']) ? htmlspecialchars($_POST['image_url']) : '';

    // Insert item into the database
    $stmt = $pdo->prepare('INSERT INTO items (name, item_id, quantity, price, description, image_url) VALUES (?, ?, ?, ?, ?, ?)');
    $stmt->execute([$name, $item_id, $quantity, $price, $description, $image_url]);

    // Redirect to the dashboard after successful insertion
    header('Location: dashboard.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <title>Add Item - Pathirana Motors</title>
</head>
<body class="bg-blue-900 min-h-screen flex flex-col">
    <?php include 'navbar.php'; ?>

    <div class="container mx-auto mt-8 flex-grow">
        <h1 class="text-3xl font-semibold text-white mb-4">Add New Item</h1>

        <form action="add_item.php" method="POST" class="bg-gray-800 p-8 rounded shadow-md">
            <div class="mb-4">
                <label for="name" class="block text-white">Item Name</label>
                <input type="text" name="name" id="name" class="w-full p-2 border border-gray-600 rounded bg-gray-700 text-white" required>
            </div>
            <div class="mb-4">
                <label for="item_id" class="block text-white">Item ID</label>
                <input type="text" name="item_id" id="item_id" class="w-full p-2 border border-gray-600 rounded bg-gray-700 text-white" required>
            </div>
            <div class="mb-4">
                <label for="quantity" class="block text-white">Quantity</label>
                <input type="number" name="quantity" id="quantity" class="w-full p-2 border border-gray-600 rounded bg-gray-700 text-white" required>
            </div>
            <div class="mb-4">
                <label for="price" class="block text-white">Price (per unit)</label>
                <input type="number" step="0.01" name="price" id="price" class="w-full p-2 border border-gray-600 rounded bg-gray-700 text-white" required>
            </div>
            <div class="mb-4">
                <label for="description" class="block text-white">Description (optional)</label>
                <textarea name="description" id="description" class="w-full p-2 border border-gray-600 rounded bg-gray-700 text-white"></textarea>
            </div>
            <div class="mb-4">
                <label for="image_url" class="block text-white">Image URL (optional)</label>
                <input type="text" name="image_url" id="image_url" class="w-full p-2 border border-gray-600 rounded bg-gray-700 text-white">
            </div>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Add Item</button>
        </form>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>
