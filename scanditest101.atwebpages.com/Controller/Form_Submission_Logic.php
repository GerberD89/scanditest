<?php
include '../autoloader.php';

//this is the logic that handles the form submission
if ($_SERVER["REQUEST_METHOD"]== "POST"){
$userSelection = $_POST["productType"];

$factory = new ProductFactory();

// Set up an array to hold additional parameters
$additionalParameters = [];

$id = 1;
$SKU = $_POST["sku"];
$name = $_POST["name"];
$price = $_POST["price"];
$dbc = DatabaseConnection::getInstance();
$additionalParameters = $_POST;

// $product = $factory->createProduct($userSelection, $id, $SKU, $name, $price, $dbc, $additionalParameters);
$product = $factory->createProduct($userSelection, $dbc, $additionalParameters);

// Check if $product is not null before calling methods on it
if ($product) {
// $product->display();
$product->create();
// $product->read($id);
$product->displayAll();


}

// Redirect to index.php
// header("Location: ../index.php");
// exit();

//JavaScript for redirection
echo '<script>window.location.href = "../index.php";</script>';
exit(); 

}
