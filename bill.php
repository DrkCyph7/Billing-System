<?php
session_start();
require_once 'config.php'; // Database connection

// Ensure user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

// Initialize session variables for items and customer details
$items = $_SESSION['items'] ?? [];
$customerName = $_SESSION['customer_name'] ?? 'Unknown';
$customerPhone = $_SESSION['customer_phone'] ?? 'Unknown';

// Clear items from session after processing
unset($_SESSION['items']);

// Handle bill saving and PDF generation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Save bill to database and generate PDF if needed
    // Your code for saving bill and generating PDF goes here
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <title>Bill - Pathirana Motors</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/jsPDF@2.5.1/dist/jspdf.umd.min.js"></script>
</head>
<body class="bg-blue-900 min-h-screen flex flex-col">
    <?php include 'navbar.php'; ?>

    <div class="container mx-auto mt-8 flex-grow">
        <h1 class="text-3xl font-semibold text-white mb-4">Bill</h1>

        <div class="bg-white text-black p-6 rounded-lg shadow-lg">
            <div class="text-lg font-semibold mb-4">Pathirana Motors</div>
            <div class="mb-4">Address: 123 Main St, Colombo</div>
            <div class="mb-4">Owner: John Pathirana</div>
            <div class="mb-4">Phone: +94 11 123 4567</div>
            <div class="mb-4">Customer Name: <?php echo htmlspecialchars($customerName); ?></div>
            <div class="mb-4">Customer Phone: <?php echo htmlspecialchars($customerPhone); ?></div>

            <table class="min-w-full bg-gray-800 text-white border border-gray-700 rounded-lg mb-4">
                <thead>
                    <tr>
                        <th class="p-3 border-b">Item Name</th>
                        <th class="p-3 border-b">Quantity</th>
                        <th class="p-3 border-b">Unit Price</th>
                        <th class="p-3 border-b">Total Price</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $item): ?>
                        <tr>
                            <td class="p-3 border-b"><?php echo htmlspecialchars($item['name']); ?></td>
                            <td class="p-3 border-b"><?php echo htmlspecialchars($item['quantity']); ?></td>
                            <td class="p-3 border-b">Rs <?php echo number_format($item['price'], 2); ?></td>
                            <td class="p-3 border-b">Rs <?php echo number_format($item['total'], 2); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="p-3 border-t font-semibold">Total</td>
                        <td colspan="1" class="p-3 border-t font-semibold" id="total-price">
                            Rs <?php echo number_format(array_sum(array_column($items, 'total')), 2); ?>
                        </td>
                    </tr>
                </tfoot>
            </table>

            <!-- Save and Download PDF Button -->
            <div class="flex justify-between mt-4">
                <form method="POST" action="bill.php">
                    <button type="submit" name="save" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Save Bill</button>
                </form>
                <button id="download-pdf" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Download PDF</button>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>

    <script>
        document.getElementById('download-pdf').addEventListener('click', function() {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();
            doc.text('Pathirana Motors', 10, 10);
            doc.text('Address: 123 Main St, Colombo', 10, 20);
            doc.text('Owner: John Pathirana', 10, 30);
            doc.text('Phone: +94 11 123 4567', 10, 40);
            doc.text('Customer Name: <?php echo htmlspecialchars($customerName); ?>', 10, 50);
            doc.text('Customer Phone: <?php echo htmlspecialchars($customerPhone); ?>', 10, 60);

            doc.autoTable({
                head: [['Item Name', 'Quantity', 'Unit Price', 'Total Price']],
                body: <?php echo json_encode(array_map(function($item) {
                    return [$item['name'], $item['quantity'], 'Rs ' . number_format($item['price'], 2), 'Rs ' . number_format($item['total'], 2)];
                }, $items)); ?>,
                startY: 70,
                theme: 'striped',
            });

            doc.text('Total: Rs <?php echo number_format(array_sum(array_column($items, 'total')), 2); ?>', 10, doc.autoTable.previous.finalY + 10);
            doc.save('bill.pdf');
        });
    </script>
</body>
</html>
