document.getElementById('delete_product_btn').addEventListener('click', function () {
    // Array to store selected products
    var selectedProducts = [];

    // Select all checkboxes with the class 'product-checkbox'
    var checkboxes = document.querySelectorAll('.delete-checkbox:checked');

    checkboxes.forEach(function (checkbox) {
        // Find the closest parent card element
        var card = checkbox.closest('.card');

        // Check if the card element exists
        if (card) {
            // Extract all relevant attributes from the card
            var attributes = {};
            var attributeElements = card.querySelectorAll('.card-body h6');

            attributeElements.forEach(function (attributeElement) {
                // Extract attribute name and value
                var attributeName = attributeElement.getAttribute('data-attribute');
                var attributeValue = attributeElement.innerText;

                // Add the attribute to the object
                attributes[attributeName] = attributeValue;
            });

            // Add the tableName to the attributes
            attributes['tableName'] = checkbox.getAttribute('data-table');

            // Generate a unique "id" value 
            attributes['id'] = Math.random(); 
            selectedProducts.push(attributes);
        }
    });

    // AJAX request to the server
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            // (debugging purposes)
            console.log(xhr.responseText);
            // Redirect to the same page
            window.location.href = './index.php';
        }
    };

    xhr.open("POST", "./Controller/massdelete.php", true);
    xhr.setRequestHeader("Content-Type", "application/json");
    xhr.send(JSON.stringify(selectedProducts));

    // (debugging purposes)
    console.log(selectedProducts);
});
