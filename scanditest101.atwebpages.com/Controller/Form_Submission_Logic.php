<?php
include '../autoloader.php';

if ($_SERVER["REQUEST_METHOD"]== "POST"){
$userSelection = $_POST["productType"];

$factory = new ProductFactory();
$additionalParameters = [];
$id = 1;
$SKU = $_POST["sku"];
$name = $_POST["name"];
$price = $_POST["price"];
$dbc = DatabaseConnection::getInstance();
$additionalParameters = $_POST;
$product = $factory->createProduct($userSelection, $dbc, $additionalParameters);

if ($product) {
// $product->display();
$product->create();
$product->displayAll();
}

//JavaScript for redirection
echo '<script>window.location.href = "../index.php";</script>';
exit(); 

}
