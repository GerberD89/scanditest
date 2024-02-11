<?php
include '../autoloader.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $table = $_POST['table'] ?? '';
    $sku = $_POST['sku'] ?? '';

    try {
        $databaseConnection = DatabaseConnection::getInstance();
        $pdo = $databaseConnection->getInstance();
        $sql = "SELECT COUNT(*) FROM $table WHERE sku = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$sku]);
        $count = $stmt->fetchColumn();

        header('Content-Type: application/json');
        echo json_encode(['valid' => $count === 0]);
    } catch (Exception $e) {
        header('Content-Type: application/json');
        echo json_encode(['error' => $e->getMessage()]);
    }
    exit;
}
