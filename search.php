<?php
require 'db.php'; // Include your database connection

// Retrieve and sanitize search query
$query = isset($_GET['query']) ? trim($_GET['query']) : '';

// Initialize the response array
$response = [];

// Check if query is not empty
if (!empty($query)) {
    try {
        // Prepare and execute SQL statement
        $stmt = $pdo->prepare('
            SELECT item_id, name, price, quantity 
            FROM items 
            WHERE name LIKE :query OR item_id LIKE :query
        ');

        $searchQuery = "%$query%";
        $stmt->bindParam(':query', $searchQuery, PDO::PARAM_STR);
        $stmt->execute();

        // Fetch items
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Prepare response data
        foreach ($items as &$item) {
            $item['available_count'] = $item['quantity'];
            unset($item['quantity']); // Remove 'quantity' if you don't want it in the response
        }

        // Set the response type to JSON and echo the items
        header('Content-Type: application/json');
        echo json_encode($items);

    } catch (PDOException $e) {
        // Log the error and send an internal server error response
        error_log('Database error: ' . $e->getMessage());
        header('HTTP/1.1 500 Internal Server Error');
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    // If the query is empty, return an empty array
    header('Content-Type: application/json');
    echo json_encode([]);
}
