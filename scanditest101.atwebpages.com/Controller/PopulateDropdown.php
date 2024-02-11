<?php

require_once '../autoloader.php';

if (isset($_GET['table'])) {
    $tableName = $_GET['table'];
    $databaseConnection = DatabaseConnection::getInstance();
    $pdo = $databaseConnection->getInstance();

    // Fetch columns for the selected table
    $statement = $pdo->query("DESCRIBE $tableName");
    $columns = $statement->fetchAll(PDO::FETCH_ASSOC);

    // Filter columns as needed
    $filteredColumns = array_filter($columns, function ($column) {
        return !in_array($column['Field'], ['id', 'sku', 'name', 'price']);
    });

    header('Content-Type: application/json');
    echo json_encode($filteredColumns);
} else {
    echo json_encode(array('error' => 'Table parameter not provided'));
}

?>
