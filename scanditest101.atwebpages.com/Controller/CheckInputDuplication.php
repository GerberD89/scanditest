<?php
// ... (database connection setup)
include '../autoloader.php';

// SKU UNIQUENESS
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve data from POST
    $table = $_POST['table'] ?? '';
    $sku = $_POST['sku'] ?? '';

    try {
        // Establish a database connection
        $databaseConnection = DatabaseConnection::getInstance();
        $pdo = $databaseConnection->getInstance();

        // Perform SKU validation
        $sql = "SELECT COUNT(*) FROM $table WHERE sku = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$sku]);
        $count = $stmt->fetchColumn();

        // Respond with a JSON object indicating SKU validation result
        header('Content-Type: application/json');
        echo json_encode(['valid' => $count === 0]);
    } catch (Exception $e) {
        // Handle exceptions and respond with an error
        header('Content-Type: application/json');
        echo json_encode(['error' => $e->getMessage()]);
    }

    exit;
}
