<?php

include '../autoloader.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $table = $_POST['table'] ?? '';

        try {
                $databaseConnection = DatabaseConnection::getInstance();
                $pdo = $databaseConnection->getInstance();
                $query = " SELECT MAX(CAST(REGEXP_REPLACE(SUBSTRING_INDEX(sku, '-', -1), '[^0-9]', '') AS UNSIGNED)) + 1 AS nextSkuNumber FROM $table";
                $result = $pdo->query($query);
                if ($result === false) {
                    throw new Exception('Database query error: ' . $pdo->errorInfo()[2]);
                }
                
                $row = $result->fetch(PDO::FETCH_ASSOC);
                $nextSkuNumber = $row['nextSkuNumber'] ?? 1;

                header('Content-Type: application/json');
                echo json_encode(['nextSkuNumber' => $nextSkuNumber]);
        } catch (Exception $e) {
                error_log('Error in GetNextSKUNumber.php: ' . $e->getMessage(), 3, 'error.log');

                header('Content-Type: application/json');
                echo json_encode(['error' => 'An error occurred while processing the request.']);
        }

        exit;
}
