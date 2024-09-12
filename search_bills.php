<?php
include 'db.php'; // Include your database connection

$search = isset($_POST['search']) ? htmlspecialchars($_POST['search']) : '';

$query = 'SELECT * FROM bills WHERE customer_name LIKE ? OR customer_phone LIKE ?';
$stmt = $pdo->prepare($query);
$stmt->execute(["%$search%", "%$search%"]);

$bills = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <title>Search Bills - Pathirana Motors</title>
</head>
<body class="bg-blue-900 min-h-screen flex flex-col">
    <?php include 'navbar.php'; ?>

    <div class="container mx-auto mt-8 flex-grow">
        <h1 class="text-3xl font-semibold text-white mb-4">Search Bills</h1>

        <form action="search_bills.php" method="POST" class="bg-gray-800 p-4 rounded shadow-md mb-6">
            <div class="mb-4">
                <label for="search" class="block text-white">Search by Customer Name or Phone Number</label>
                <input type="text" name="search" id="search" class="w-full p-2 border border-gray-600 rounded bg-gray-700 text-white" value="<?php echo htmlspecialchars($search); ?>">
            </div>
            <button type="submit" class="bg-yellow-400 text-gray-800 px-4 py-2 rounded hover:bg-yellow-500">Search</button>
        </form>

        <?php if (!empty($bills)): ?>
            <table class="w-full bg-gray-700 rounded text-white">
                <thead>
                    <tr>
                        <th class="p-2">Bill Number</th>
                        <th class="p-2">Customer Name</th>
                        <th class="p-2">Customer Phone</th>
                        <th class="p-2">Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($bills as $bill): ?>
                        <tr>
                            <td class="p-2"><?php echo htmlspecialchars($bill['bill_number']); ?></td>
                            <td class="p-2"><?php echo htmlspecialchars($bill['customer_name']); ?></td>
                            <td class="p-2"><?php echo htmlspecialchars($bill['customer_phone']); ?></td>
                            <td class="p-2"><?php echo htmlspecialchars($bill['date']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="text-white">No bills found for the given search criteria.</p>
        <?php endif; ?>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>
