<?php
// Output received data to the console
$selectedProducts = file_get_contents("php://input");
echo "Received data from AJAX request:\n";
echo $selectedProducts . "\n\n";

// Include necessary files and initialize the database connection
include '../autoloader.php'; // Adjust the path accordingly

// Receive AJAX request
$selectedProducts = json_decode(file_get_contents("php://input"), true);

// Set up an array to hold additional parameters
$additionalParameters = [];

$id = 1; // You may need to handle this dynamically or based on your application logic
$dbc = DatabaseConnection::getInstance();
$additionalParameters = $selectedProducts; // You may need to adjust this based on the structure of your data

$factory = new ProductFactory();

// Iterate over selected products and perform mass deletion
foreach ($selectedProducts as $productData) {
    $userSelection = $productData['tableName'];
    // Create an instance of the product
    $product = $factory->massDelete($userSelection, $dbc, $additionalParameters);
   
}
?>