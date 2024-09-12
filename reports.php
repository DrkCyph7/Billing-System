<?php
$page = 'news'; // Set the current page
$customLinks = [
    'reports' => ['title' => 'Reports', 'href' => '#reports'],
    'extra-report' => ['title' => 'Extra Report', 'href' => '#extra-report']
];
include 'navbar.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports - Pathirana Motors</title>
    <link href="path/to/tailwind.css" rel="stylesheet">
</head>
<body class="bg-blue-900 text-white">
    <h1 class="text-2xl font-bold">Reports</h1>
    <!-- Reports content -->
    <?php include 'footer.php'; ?>
</body>
</html>
