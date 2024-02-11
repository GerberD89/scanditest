
//To populate items in dropdown-list with tables in the database
//dynamically create form fields based upon user selection with updateFormFields() function
//display a preferred SKU based on user selection generateAndDisplayPreferredSKU()
function handleDropdownChange(event) {
    let selectedOption = event.target.options[event.target.selectedIndex];
    let tableName = selectedOption.getAttribute('value');
    let productType = selectedOption.getAttribute('id');

    // Ajax request to retrieve columns for the selected table
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            //(debugging purposes)
			console.log(xhr);
            var formFieldsHTML = xhr.responseText;
            columns = JSON.parse(formFieldsHTML);
            updateFormFields();

            // Call SKU generation when product type is selected
            generateSKU(columns, tableName, function (generatedSKU) {
                //(debugging purposes)    
                console.log("Generated SKU:", generatedSKU);
                displayPreferredSKU(generatedSKU);
            });
        }
    };

  xhr.open("GET", "../Controller/PopulateDropdown.php?table=" + tableName, true);
xhr.send();

    // Display the required description
    displayRequiredDescription(productType);
}

function displayPreferredSKU(preferredSKU) {
    // Get the preferred SKU element
    var preferredSKUElement = document.getElementById('preferredSKUMessage');

    // Set the text content
    preferredSKUElement.textContent = 'Preferred SKU: ' + preferredSKU;

    // Apply styling with more specific selectors
    preferredSKUElement.style.setProperty('color', 'green', 'important');
    preferredSKUElement.style.setProperty('font-weight', 'bold', 'important');
    // Add more styling properties as needed
}

function updateFormFields() {
    var dynamicFormFieldsContainer = document.getElementById("dynamicFormFieldsContainer");
    // Clear existing content
    dynamicFormFieldsContainer.innerHTML = "";

    // Loop through the columns and generate HTML for form fields
    for (var key in columns) {
        if (columns.hasOwnProperty(key)) {
            var column = columns[key];
            // Use the column information to generate form fields
            var columnName = column['Field'];
            var selectedProductType = columnName;
            // Capitalize the first letter of the column name
            var capitalizedColumnName = columnName.charAt(0).toUpperCase() + columnName.slice(1);

          // Create a wrapper div for each label-input pair
            var wrapperDiv = document.createElement('div');
            wrapperDiv.setAttribute('class', 'form-group row m-5');

            // Create label element
            var label = document.createElement('label');
            label.setAttribute('for', columnName);
            label.setAttribute('class', 'col-sm-2 col-form-label');
            label.innerHTML = capitalizedColumnName; 

             // Create a div for the input with class "col-sm-3"
             var inputDiv = document.createElement('div');
             inputDiv.setAttribute('class', 'col-sm-3');

            // Create input element
            var inputDiv = document.createElement('div');
            inputDiv.setAttribute('class', 'col-sm-3');

            var input = document.createElement('input');
            input.setAttribute('type', 'text');
            input.setAttribute('class', 'form-control');
            input.setAttribute('id', columnName);
            input.setAttribute('name', columnName);
            input.setAttribute('placeholder', capitalizedColumnName); 

            // Append label and input to the container
            inputDiv.appendChild(input);
            wrapperDiv.appendChild(label);
            wrapperDiv.appendChild(inputDiv);
            dynamicFormFieldsContainer.appendChild(wrapperDiv);
        }
    }

 
}


function displayRequiredDescription(productType) {
    // Find the index of the selected productType in the array
    var index = productTypes.indexOf(productType);

    if (index !== -1) {
        var requiredDescription = requiredDescriptions[index];

        // Display the description below the dynamic form
        var descriptionContainer = document.getElementById("requiredDescriptionContainer");
        descriptionContainer.innerHTML = "<h5>" + requiredDescription + "</h5>";
    }
}

function validateForm() {
    // Implement your validation logic here
    let sku = document.getElementById('sku').value;
    let name = document.getElementById('name').value;
    let price = document.getElementById('price').value;

    // Example: Check if SKU, Name, and Price are not empty
    if (sku.trim() === '' || name.trim() === '' || price.trim() === '') {
        // Display an error message or take appropriate action
        displayErrorMessage('Please fill in all required fields!');
        return false;
    }

    // Validate dynamically generated input fields
    for (var key in columns) {
        if (columns.hasOwnProperty(key)) {
            var columnName = columns[key]['Field'];
            var columnValue = document.getElementById(columnName).value.trim();

            // Example validation: Check if dynamically generated field is not empty
            if (columnValue === '') {
                displayErrorMessage('Please fill in all required fields!');
                return false;
            }

            
        }
    }
 

    // SKU format: Letters, Numbers, and " - "
    if (!/^[a-zA-Z0-9 -]+$/.test(sku)) {
        displayErrorMessage('Please provide a valid SKU!');
        return false;
    }

    
    // Pricing format: Numeric value with optional decimal part, allowing spaces and commas
    var match = price.match(/^(\d{1,3}(?:[ \d{3}]*\d{3})*(?:\.\d{1,2})?)$/);

    if (!match) {
        displayErrorMessage('Please provide a valid price!');
        return false;
    }

    // Extract the numeric part
    var numericPrice = parseFloat(match[1].replace(/ /g, '').replace(/,/g, '.'));

    if (isNaN(numericPrice)) {
        displayErrorMessage('Please provide a valid pricing!');
        return false;
    }

    
    return true;
    
}


function displayErrorMessage(message, duration = 5000) {
    // Create a new element to display the error message
    if(displayErrorMessage){
        var errorMessage = document.createElement('div');
        errorMessage.setAttribute('class', 'alert alert-danger');
        errorMessage.innerText = message;

        // Append the error message to the form or another appropriate container
        var form = document.getElementById('testing');
        form.appendChild(errorMessage);
        setTimeout(function () {
            errorMessage.remove();
        }, duration);
    }
    
}
function validateSKU(sku) {
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4) {
            if (xhr.status == 200) {
                var response;
                try {
                    response = JSON.parse(xhr.responseText);
                } catch (error) {
                    //(debugging purposes)
                    console.error("Error parsing JSON response:", xhr.responseText);
                    // Handle the error here, e.g., show an error message
                    displayErrorMessage('Error validating SKU. Please try again.');
                    return;
                }

                // Handle the validation result
                if (response.valid) {
                    // SKU is valid, continue with form submission
                    document.getElementById('product_form').submit();
                } else {
                    // SKU is not valid, show a notification or take appropriate action
                    displayErrorMessage('SKU already exists. Please choose a different SKU.');
                }
            } else {
                //(debugging purposes)
                console.error("Server error:", xhr.status, xhr.statusText);
                // Handle the error here, e.g., show an error message
                displayErrorMessage('Error validating SKU. Please try again.');
            }
        }
    };

    xhr.open("POST", "../controller/CheckInputDuplication.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.send("sku=" + encodeURIComponent(sku) + "&table=" + encodeURIComponent(selectedProductType));
}


function submitForm() {
    // Perform validation
    if (validateForm()) {
        // If validation passes, submit the form
        document.getElementById('product_form').submit();
    } else {
        // If validation fails, display an error or take appropriate action
            console.log('Validation failed. Please check your inputs.');
    }
}


