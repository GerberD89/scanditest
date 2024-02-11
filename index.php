<?php
// index.php
include 'autoloader.php';

?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
        <title>Product List</title>
</head>

<body>


  <!--Navbar -->
  <nav class="navbar navbar-expand-lg navbar-light" style="background-color: #e3f2fd;">
  	<h3 class="navbar">Product List</h3>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ml-auto">
        <li class="nav-item">
          <a class="btn btn-primary m-1" href="product_add.php">ADD</a>
        </li>
        <li class="nav-item">
          <a class="btn btn-primary m-1" id="delete_product_btn" href="#">MASS DELETE</a>
        </li>
      </ul>
    </div>
  </nav>
  <hr class="my-5" />

  <!-- Cards -->


  <?php
  $id = $_GET["id"] ?? '';
  $sku = $_GET["sku"] ?? '';
  $name = $_GET["name"] ?? '';
  $price = $_GET["price"] ?? '';
  $size = $_GET["size"] ?? '';
  $dbc = DatabaseConnection::getInstance();
  $pdvd = new Pdvd((float) $id, (string) $sku, (string) $name, (float) $price, $dbc, (float) $size);
  $pdvd->displayAll();
  ?>
  <?php
  $id = $_GET["id"] ?? '';
  $sku = $_GET["sku"] ?? '';
  $name = $_GET["name"] ?? '';
  $price = $_GET["price"] ?? '';
  $length = $_GET["length"] ?? '';
  $width = $_GET["width"] ?? '';
  $height = $_GET["height"] ?? '';
  $dbc = DatabaseConnection::getInstance();
  $pfurniture = new Pfurniture((float) $id, (string) $sku, (string) $name, (float) $price, $dbc, (float) $length, (float) $width, (float) $height);
  $pfurniture->displayAll();
  ?>
  <?php
  $id = $_GET["id"] ?? '';
  $sku = $_GET["sku"] ?? '';
  $name = $_GET["name"] ?? '';
  $price = $_GET["price"] ?? '';
  $width = $_GET["width"] ?? '';

  $dbc = DatabaseConnection::getInstance();
  $pbook = new Pbook((float) $id, (string) $sku, (string) $name, (float) $price, $dbc, (float) $width);
  $pbook->displayAll();
  ?>




  <script src="./js/massdelete.js"></script>

</body>

</html>