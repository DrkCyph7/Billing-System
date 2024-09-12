<?php
require 'db.php'; // Include database connection

// Initialize search query
$search = '';
if (isset($_GET['search'])) {
    $search = htmlspecialchars($_GET['search']);
}

// Prepare the SQL query with optional search filter
$sql = 'SELECT * FROM items WHERE name LIKE ? OR item_id LIKE ?';
$stmt = $pdo->prepare($sql);
$stmt->execute(["%$search%", "%$search%"]);
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <title>View Items - Pathirana Motors</title>
</head>
<body class="bg-blue-900 min-h-screen flex flex-col">
    <?php include 'navbar.php'; ?>

    <div class="container mx-auto mt-8 flex-grow">
        <h1 class="text-3xl font-semibold text-white mb-4">Available Items</h1>

        <form action="view_items.php" method="GET" class="mb-6">
            <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Search by item name or ID" class="p-2 border border-gray-600 rounded bg-gray-700 text-white w-full">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 mt-2">Search</button>
        </form>

        <table class="min-w-full bg-gray-800 text-white border border-gray-700 rounded-lg">
            <thead>
                <tr>
                    <th class="p-3 border-b">Item ID</th>
                    <th class="p-3 border-b">Name</th>
                    <th class="p-3 border-b">Quantity</th>
                    <th class="p-3 border-b">Price</th>
                    <th class="p-3 border-b">Description</th>
                    <th class="p-3 border-b">Image</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($items): ?>
                    <?php foreach ($items as $item): ?>
                        <tr>
                            <td class="p-3 border-b"><?php echo htmlspecialchars($item['item_id']); ?></td>
                            <td class="p-3 border-b"><?php echo htmlspecialchars($item['name']); ?></td>
                            <td class="p-3 border-b"><?php echo htmlspecialchars($item['quantity']); ?></td>
                            <td class="p-3 border-b"><?php echo htmlspecialchars(number_format($item['price'], 2)); ?></td>
                            <td class="p-3 border-b"><?php echo htmlspecialchars($item['description']); ?></td>
                            <td class="p-3 border-b">
                                <?php if ($item['image_url']): ?>
                                    <img src="<?php echo htmlspecialchars($item['image_url']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" class="w-16 h-16 object-cover">
                                <?php else: ?>
                                    No Image
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="p-3 border-b text-center">No items found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>
