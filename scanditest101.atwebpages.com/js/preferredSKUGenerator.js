function generateSKU(columns, tableName, callback) {
    var typePrefix = tableName.slice(0, 2).toUpperCase();
    var sku = 'SKU-' + typePrefix;

    for (var key in columns) {
        if (columns.hasOwnProperty(key)) {
            var columnValue = columns[key]['Field'];
            sku += columnValue.charAt(0).toUpperCase();
        }
    }
        
   // (debugging purposes)    
    console.log('Constructed Base SKU:', sku);


    // Ajax request to get the next available SKU number
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4) {
            if (xhr.status == 200) {
                try {
                    var responseText = xhr.responseText.trim();
                    // (debugging purposes)    
                    console.log('Response Text:', responseText);

                    var response = JSON.parse(responseText);

                    if (response && response.nextSkuNumber !== undefined) {
                        var nextSKUNumber = response.nextSkuNumber || 1;

                        // Call the callback with the next SKU number
                        if (typeof callback === 'function') {
                            callback(sku + nextSKUNumber);
                        }
                    } else {
                        // (debugging purposes)    
                        console.error('Invalid JSON response:', responseText);
                    }
                } catch (error) {
                    // (debugging purposes)    
                    console.error('Error parsing JSON response:', error);
                    console.error('Actual Response Text:', xhr.responseText);
                }
            } else {
                // (debugging purposes)    
                console.error('Error in AJAX request:', xhr.status, xhr.statusText);
            }
        }
    };




    // xhr.open("POST", "../Controller/GetNextSKUNumber.php", true);
    xhr.open("POST", "../Controller/GetNextSKUNumber.php?table=" + tableName, true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.send("table=" + encodeURIComponent(tableName));
}
