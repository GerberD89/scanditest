<?php
include '../autoloader.php';

// Retrieve data from the POST request
$productType = $_POST['productType'] ?? '';
$formData = json_decode($_POST['formData'], true) ?? [];

// Create an instance of ProductFactory
$factory = new ProductFactory();

// Generate the SKU
$generatedSKU = $factory->generateSKU($productType, $formData);

// Return the generated SKU as JSON
header('Content-Type: application/json');
echo json_encode(['generatedSKU' => $generatedSKU]);
