<?php
session_start();
require 'db.php'; // Include your database connection

// Check if items and customer details are available in the session
if (!isset($_SESSION['items']) || empty($_SESSION['items']) ||
    !isset($_SESSION['customer_name']) || !isset($_SESSION['customer_phone'])) {
    header('Location: billing.php'); // Redirect to billing if no data is found
    exit;
}

// Calculate total amount
$total = 0;
foreach ($_SESSION['items'] as $item) {
    $total += $item['quantity'] * $item['price'];
}

// Customer details
$customer_name = htmlspecialchars($_SESSION['customer_name']);
$customer_phone = htmlspecialchars($_SESSION['customer_phone']);

// Save invoice details to the database
try {
    $pdo->beginTransaction();

    // Insert the invoice into the bills table
    $stmt = $pdo->prepare('INSERT INTO bills (customer_name, customer_phone, total, created_at) VALUES (?, ?, ?, NOW())');
    $stmt->execute([$customer_name, $customer_phone, $total]);
    $invoice_id = $pdo->lastInsertId();

    // Insert each item into the invoice_items table
    foreach ($_SESSION['items'] as $item) {
        $stmt = $pdo->prepare('INSERT INTO invoice_items (invoice_id, item_name, item_id, quantity, price) VALUES (?, ?, ?, ?, ?)');
        $stmt->execute([$invoice_id, $item['name'], $item['item_id'], $item['quantity'], $item['price']]);
    }

    $pdo->commit();
} catch (Exception $e) {
    $pdo->rollBack();
    die('Error: ' . $e->getMessage());
}

// Clear session after invoice generation
unset($_SESSION['items']);
unset($_SESSION['customer_name']);
unset($_SESSION['customer_phone']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <title>Invoice - Pathirana Motors</title>
    <style>
        .invoice-header {
            border-bottom: 2px solid #1f2937;
            margin-bottom: 1rem;
        }

        .invoice-details {
            border-bottom: 1px solid #e5e7eb;
            margin-bottom: 1rem;
        }

        .invoice-table th, .invoice-table td {
            padding: 0.75rem;
            border: 1px solid #e5e7eb;
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">
    <div class="container mx-auto mt-8 p-6 bg-white shadow-md rounded">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Invoice</h1>

        <div class="invoice-header mb-6">
            <h2 class="text-xl font-semibold text-gray-700">Pathirana Motors</h2>
            <p class="text-gray-600">Walawa Junction, Udawalawa</p>
            <p class="text-gray-600">Owner: K P K C Kariyawasam</p>
        </div>

        <div class="invoice-details mb-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-2">Customer Details</h3>
            <p class="text-gray-600">Name: <?= $customer_name ?></p>
            <p class="text-gray-600">Phone: <?= $customer_phone ?></p>
        </div>

        <table class="w-full bg-white text-gray-800 invoice-table">
            <thead>
                <tr class="bg-gray-200">
                    <th class="text-left">Item Name</th>
                    <th class="text-left">Quantity</th>
                    <th class="text-left">Unit Price (Rs)</th>
                    <th class="text-left">Total (Rs)</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($_SESSION['items'] as $item): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['name']) ?></td>
                        <td><?= htmlspecialchars($item['quantity']) ?></td>
                        <td><?= number_format($item['price'], 2) ?></td>
                        <td><?= number_format($item['quantity'] * $item['price'], 2) ?></td>
                    </tr>
                <?php endforeach; ?>
                <tr class="font-semibold bg-gray-200">
                    <td colspan="3" class="text-right">Total</td>
                    <td><?= number_format($total, 2) ?></td>
                </tr>
            </tbody>
        </table>

        <div class="mt-6">
            <a href="billing.php" class="text-blue-600 hover:underline">Back to Billing</a>
        </div>

        <!-- PDF Generation Button -->
        <div class="mt-6">
            <a href="generate_pdf.php?invoice_id=<?= $invoice_id ?>" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                Download PDF
            </a>
        </div>
    </div>
</body>
</html>
