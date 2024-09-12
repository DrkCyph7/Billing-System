<?php
require 'vendor/autoload.php'; // Include Composer's autoloader
require 'db.php'; // Include your database connection

// Check if the invoice ID is provided
if (!isset($_GET['invoice_id'])) {
    die('Invoice ID is missing.');
}

$invoice_id = intval($_GET['invoice_id']);

// Fetch invoice details from the database
$stmt = $pdo->prepare('SELECT * FROM bills WHERE id = ?');
$stmt->execute([$invoice_id]);
$invoice = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$invoice) {
    die('Invoice not found.');
}

// Fetch items for the invoice
$stmt = $pdo->prepare('SELECT * FROM invoice_items WHERE invoice_id = ?');
$stmt->execute([$invoice_id]);
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Create PDF
$mpdf = new \Mpdf\Mpdf();
$mpdf->SetTitle('Invoice - Pathirana Motors');
$mpdf->SetHeader('Pathirana Motors||{PAGENO}');
$mpdf->SetFooter('{PAGENO}');
$mpdf->WriteHTML('<h1>Invoice</h1>');
$mpdf->WriteHTML('<p><strong>Customer Name:</strong> ' . htmlspecialchars($invoice['customer_name']) . '</p>');
$mpdf->WriteHTML('<p><strong>Customer Phone:</strong> ' . htmlspecialchars($invoice['customer_phone']) . '</p>');
$mpdf->WriteHTML('<h2>Items</h2>');
$mpdf->WriteHTML('<table border="1" cellpadding="5" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>Item Name</th>
            <th>Quantity</th>
            <th>Unit Price (Rs)</th>
            <th>Total (Rs)</th>
        </tr>
    </thead>
    <tbody>');

foreach ($items as $item) {
    $total_price = $item['quantity'] * $item['price'];
    $mpdf->WriteHTML('<tr>
        <td>' . htmlspecialchars($item['item_name']) . '</td>
        <td>' . htmlspecialchars($item['quantity']) . '</td>
        <td>' . number_format($item['price'], 2) . '</td>
        <td>' . number_format($total_price, 2) . '</td>
    </tr>');
}

$mpdf->WriteHTML('</tbody>
    <tfoot>
        <tr>
            <td colspan="3" style="text-align:right;"><strong>Total</strong></td>
            <td>' . number_format($invoice['total'], 2) . '</td>
        </tr>
    </tfoot>
</table>');

// Output PDF
$mpdf->Output('Invoice_' . $invoice_id . '.pdf', 'D');
exit;
?>
