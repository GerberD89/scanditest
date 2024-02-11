<?php
//product_add.php
include 'autoloader.php';

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">


    <title>Adding Products</title>
</head>

<body>
    <div id="outputContainer"></div>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light" style="background-color: #e3f2fd;">
        <h3 class="navbar">Product Add</h3>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <form>
                        <button class="btn btn-primary m-1" type="button" onclick="submitForm()">Save</button>
                    </form>
                </li>
                <li class="nav-item">
                    <a class="btn btn-primary m-1" href="index.php">Cancel</a>
                </li>
            </ul>
        </div>
    </nav>
    <hr class="my-5" />

    <!-- Form -->
    <form method="post" action="./Controller/Form_Submission_Logic.php" class="form-col" id="product_form" enctype="multipart/form-data" name="productType">
        <div class="row mb-3 mt-3 mx-5" id="testing">
        </div>
        <!-- SKU -->
        <div class="form-group row m-5">
            <label for="sku" class="col-sm-2 col-form-label">SKU</label>
            <div class="col-sm-3">
                <input type="text" class="form-control" id="sku" name="sku" placeholder="SKU">
                <small id="preferredSKUMessage" class="form-text text-muted"></small>
            </div>
        </div>

        <!-- Name -->
        <div class="form-group row m-5">
            <label for="name" class="col-sm-2 col-form-label">Name</label>
            <div class="col-sm-3">
                <input type="text" class="form-control" id="name" name="name">
            </div>
        </div>

        <!-- Price -->
        <div class="form-group row m-5">
            <label for="price" class="col-sm-2 col-form-label">Price ($)</label>
            <div class="col-sm-3">
                <input type="text" class="form-control" id="price" name="price">
            </div>
        </div>


        <!-- TypeSwitcher -->
        <div class="form-group row m-5">
            <label for="dropdown" class="col-sm-2 col-form-label">Type Switcher</label>
            <div class="col-sm-3">
                <select class=" form-control " id="productType" name="productType" onchange="handleDropdownChange(event)">
                    <?php

                    // Call the method and store the result in a variable
                    $formHandler = new FormHandler();
                    $result = $formHandler->generateDropdownAndContainers();

                    // Access the keys from the result variable
                    $optionsHtml = $result['optionsHtml'];
                    $dynamicFormFieldsContainerHtml = $result['dynamicFormFieldsContainerHtml'];
                    $requiredDescriptionContainerHtml = $result['requiredDescriptionContainerHtml'];


                    $print = new FormHandler();
                    $print->generateDropdownAndContainers();


                    echo $optionsHtml; ?>
                </select>
            </div>
        </div>

        <?php echo $dynamicFormFieldsContainerHtml; ?>
        <?php echo $requiredDescriptionContainerHtml; ?>


    </form>
    <script src="./js/dynamicform.js"></script>
    <script src="./js/preferredSKUGenerator.js"></script>
</body>

</html>