<?php
session_start();
require 'db.php'; // Include your database connection

// Error holder
$error = '';

// Initialize session items if not set
if (!isset($_SESSION['items'])) {
    $_SESSION['items'] = [];
}

// Custom navbar links
$customLinks = [
    'dashboard' => ['title' => 'Dashboard', 'href' => 'dashboard.php'],
    'billing' => ['title' => 'Billing', 'href' => 'billing.php'],
    'Add Item' => ['title' => 'Add Item', 'href' => 'add_item.php'],
    'about' => ['title' => 'About', 'href' => '#about'],
    'All Items' => ['title' => 'All Items', 'href' => 'view_items.php']
];

// Handle form submission for adding items to the session
if (isset($_POST['add_item'])) {
    $item_id = htmlspecialchars($_POST['item_id']);
    $quantity = intval($_POST['quantity']);

    if ($quantity <= 0) {
        $error = 'Invalid quantity. Please enter a valid quantity.';
    } else {
        // Fetch item details from the database
        $stmt = $pdo->prepare('SELECT * FROM items WHERE item_id = ?');
        $stmt->execute([$item_id]);
        $item = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($item) {
            $existing_quantity = 0;

            // Calculate existing quantity for the item if it's already in the session
            foreach ($_SESSION['items'] as $cart_item) {
                if ($cart_item['item_id'] === $item_id) {
                    $existing_quantity += $cart_item['quantity'];
                }
            }

            // Check if the total quantity exceeds the available stock
            if ($quantity + $existing_quantity > $item['quantity']) {
                $error = 'Requested quantity for "' . htmlspecialchars($item['name']) . '" exceeds available stock. Only ' . $item['quantity'] . ' units are available.';
            } else {
                // If the item is already in the cart, update the quantity
                $item_found = false;
                foreach ($_SESSION['items'] as &$cart_item) {
                    if ($cart_item['item_id'] === $item_id) {
                        $cart_item['quantity'] += $quantity;
                        $item_found = true;
                        break;
                    }
                }

                // If the item is not in the cart, add it
                if (!$item_found) {
                    $newItem = [
                        'name' => $item['name'],
                        'item_id' => $item['item_id'],
                        'quantity' => $quantity,
                        'price' => $item['price']
                    ];
                    $_SESSION['items'][] = $newItem;
                }
            }
        } else {
            $error = 'Invalid item ID.';
        }
    }
}

// Calculate total price
$totalPrice = 0;
foreach ($_SESSION['items'] as $cart_item) {
    $totalPrice += $cart_item['price'] * $cart_item['quantity'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <title>Billing - Pathirana Motors</title>
    <style>
        #suggestions {
            position: absolute;
            z-index: 10;
            background-color: #1f2937;
            width: 100%;
            max-height: 200px;
            overflow-y: auto;
            border: 1px solid #4b5563;
            border-radius: 0.375rem;
        }

        .suggestion-item {
            padding: 8px;
            cursor: pointer;
            color: #f9fafb;
        }

        .suggestion-item:hover {
            background-color: #374151;
        }

        .hidden {
            display: none;
        }
    </style>
</head>
<body class="bg-blue-900 min-h-screen flex flex-col">
    <?php include 'navbar.php'; ?>

    <div class="container mx-auto mt-8 flex-grow">
        <h1 class="text-3xl font-semibold text-white mb-4">Billing</h1>

        <form action="billing.php" method="POST" class="bg-gray-800 p-4 rounded shadow-md mb-6 relative">
            <label for="search" class="block text-white mb-2">Search for Items</label>
            <input type="text" id="search" class="w-full p-2 border border-gray-600 rounded bg-gray-700 text-white" placeholder="Search items..." autocomplete="off">
            <div id="suggestions" class="hidden"></div>

            <input type="hidden" name="item_id" id="item_id">
            <input type="number" name="quantity" id="quantity" class="w-full p-2 border border-gray-600 rounded bg-gray-700 text-white mt-4" placeholder="Enter quantity">
            <button type="submit" name="add_item" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 mt-4">Add Item</button>
        </form>

        <!-- Display error message -->
        <?php if ($error): ?>
            <div class="bg-red-600 text-white p-4 rounded mb-4">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <!-- Display added items -->
        <?php if (!empty($_SESSION['items'])): ?>
            <table class="min-w-full bg-gray-700 text-white rounded">
                <thead>
                    <tr class="bg-gray-800">
                        <th class="py-2 px-4 text-left">Item Name</th>
                        <th class="py-2 px-4 text-left">Quantity</th>
                        <th class="py-2 px-4 text-left">Unit Price</th>
                        <th class="py-2 px-4 text-left">Total Price</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($_SESSION['items'] as $cart_item): ?>
                        <tr>
                            <td class="py-2 px-4"><?php echo htmlspecialchars($cart_item['name']); ?></td>
                            <td class="py-2 px-4"><?php echo htmlspecialchars($cart_item['quantity']); ?></td>
                            <td class="py-2 px-4">Rs <?php echo number_format($cart_item['price'], 2); ?></td>
                            <td class="py-2 px-4">Rs <?php echo number_format($cart_item['price'] * $cart_item['quantity'], 2); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <!-- Display total price -->
            <div class="text-white mt-4">
                <strong>Total Price: Rs <?php echo number_format($totalPrice, 2); ?></strong>
            </div>
        <?php endif; ?>
    </div>

    <!-- AJAX call for search suggestions -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('search');
            const suggestions = document.getElementById('suggestions');
            const itemIdInput = document.getElementById('item_id');

            searchInput.addEventListener('input', function() {
                const query = searchInput.value.trim();
                if (query.length > 0) {
                    fetch(`search.php?query=${encodeURIComponent(query)}`)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok');
                            }
                            return response.json();
                        })
                        .then(data => {
                            suggestions.innerHTML = '';
                            if (Array.isArray(data) && data.length > 0) {
                                data.forEach(item => {
                                    const div = document.createElement('div');
                                    div.textContent = `${item.name} (Rs ${item.price}) - Available: ${item.quantity}`;
                                    div.classList.add('suggestion-item');
                                    div.dataset.itemId = item.item_id;
                                    div.addEventListener('click', function() {
                                        searchInput.value = item.name;
                                        itemIdInput.value = item.item_id;
                                        suggestions.classList.add('hidden');
                                    });
                                    suggestions.appendChild(div);
                                });
                                suggestions.classList.remove('hidden');
                            } else {
                                suggestions.classList.add('hidden');
                            }
                        })
                        .catch(error => {
                            console.error('Error fetching data:', error);
                        });
                } else {
                    suggestions.classList.add('hidden');
                }
            });
        });
    </script>
</body>
</html>
