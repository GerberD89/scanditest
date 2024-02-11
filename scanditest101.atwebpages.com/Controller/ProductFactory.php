<?php


class ProductFactory {
    // private static $counter =[];
    public static function createProduct($userSelection, $dbc, $formData) {
        $className = ucfirst($userSelection);
        $fullyQualifiedClassName = "\\$className";

        if (class_exists($fullyQualifiedClassName, true)) {
            // Fetch required parameters for the selected product type
            $requiredParameters = self::getRequiredParameters($dbc, $userSelection);

            if (!empty($requiredParameters)) {
                // Prepare arguments array for the constructor
                $constructorArgs = [];

                // Add common properties to the arguments array
                $constructorArgs[] = isset($formData['id']) ? (float)$formData['id'] : 0.0;
                $constructorArgs[] = isset($formData['sku']) ? $formData['sku'] : '';
                $constructorArgs[] = isset($formData['name']) ? $formData['name'] : '';
                $priceWithoutSpaces = preg_replace('/[^0-9.]/', '', $formData['price']);
                $constructorArgs[] = isset($formData['price']) ? (float)$priceWithoutSpaces : 0.0;
                $constructorArgs[] = $dbc;

                // Add dynamic properties to the arguments array
                foreach ($requiredParameters as $paramKey) {
                    $constructorArgs[] = $formData[$paramKey] ?? null;
                }

                // Create an instance of the class with dynamic arguments
                $instance = new $className(...$constructorArgs);

                return $instance;
            } else {
                echo 'Class does not exist';
                return null;
            }
        } else {
            echo 'Class does not exist initially';
            return null;
        }
    }

    public static function getRequiredParameters($dbc, $table_name) {
        $requiredProperties = self::getRequiredProperties($dbc, $table_name);

        // Convert the string of required properties to an array
        $requiredParameters = explode(', ', $requiredProperties);

        // Return the array of required parameters
        return $requiredParameters;
    }

    public static function getRequiredProperties($dbc, $table_name) {
        $stmt = $dbc->prepare("SELECT required_properties FROM productmap WHERE table_name = :productType");
        $stmt->execute(['productType' => $table_name]);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        // Return only the 'required_properties' column value
        return $result['required_properties'] ?? '';
    }

    //     public static function massDelete($selectedProducts)
    //     {
    //         foreach ($selectedProducts as $product) {
    //             $sku = $product['sku'];
    //             $className = $product['tableName'];
    //             $className = ucfirst($className);
    //             $fullyQualifiedClassName = "\\$className";
    //             $success = true;

    //         if (class_exists($fullyQualifiedClassName, true)) {
    //         }
    //         foreach ($selectedProducts as $product) {
    //             // $className = ucfirst($product['tableName']);

    //             if ($className) {
    //                 // Call the delete method on each concrete class
    //                 $success = $className->delete($product['sku']);

    //                 if (!$success) {
    //                     // Handle the failure if needed
    //                     break;
    //                 }
    //             } else {
    //                 // Handle the failure to create an instance if needed
    //                 $success = false;
    //                 break;
    //             }
    //         }

    //         return $success;
    //     }
    // }

    //     public function generateSKU($productType, $formData)
    //     {
    //         // Start the SKU with the product type
    //         $sku = strtoupper($productType);

    //         // Extract the first letter of each dynamic form field
    //         foreach ($formData as $field => $value) {
    //             if ($field !== 'id' && $field !== 'sku' && $field !== 'name' && $field !== 'price') {
    //                 $sku .= strtoupper(substr($field, 0, 1));
    //             }
    //         }

    //         // Add a three-digit number to the SKU
    //         $index = $this->getNextSKUIndex($productType, $formData);
    //         $sku .= str_pad($index, 3, '0', STR_PAD_LEFT);

    //         return $sku;
    //     }

    //     private function isSKUDuplicated($table, $sku)
    //     {
    //         // Logic to check if the SKU already exists in the database
    //         $databaseConnection = DatabaseConnection::getInstance();
    //         $pdo = $databaseConnection->getInstance();

    //         $sql = "SELECT COUNT(*) FROM $table WHERE sku = ?";
    //         $stmt = $pdo->prepare($sql);
    //         $stmt->execute([$sku]);
    //         $count = $stmt->fetchColumn();

    //         // Return true if SKU is duplicated, false otherwise
    //         return $count > 0;
    //     }

    //     private static function getNextSKUIndex($productType, $formData)
    //     {
    //         if (!isset(self::$counter[$productType])) {
    //             self::$counter[$productType] = 1;
    //         } else {
    //             // Increment the counter until a unique SKU is found
    //             do {
    //                 $potentialSKU = self::generateSKU($productType, $formData) . str_pad(self::$counter[$productType], 3, '0', STR_PAD_LEFT);
    //                 self::$counter[$productType]++;
    //             } while (self::isSKUDuplicated($productType, $potentialSKU));
    //         }

    //         return self::$counter[$productType];
    //     }



    public static function massDelete($userSelection, $dbc, $additionalParameters)
    {
        $success = true;

        foreach ($additionalParameters as $product) {
            // Extract information needed for deletion
            $sku = $product['sku'];
            $tableName = $product['tableName']; // Make sure this key matches the actual key in your data

            // Check if the concrete class exists
            $className = ucfirst($tableName);
            $fullyQualifiedClassName = "\\$className";
            if (class_exists($fullyQualifiedClassName, true)) {
                // Prepare arguments array for deletion
                $deletionArgs = [
                    'id' => $product['id'], // Assuming you have an 'id' key in your data
                    'sku' => $sku,
                    'name' => $product['name'], // Assuming you have a 'name' key in your data
                    'price' => (float)$product['price'], // Assuming you have a 'price' key in your data
                    'dbc' => $dbc,
                ];

                // Dynamically include other relevant parameters
                foreach ($product as $key => $value) {
                    if ($key !== 'id' && $key !== 'sku' && $key !== 'name' && $key !== 'price' && $key !== 'dbc' && $key !== 'tableName') {
                        $deletionArgs[$key] = (float)$value;
                    }
                }

                // Create an instance of the concrete class
                $instance = new $fullyQualifiedClassName(...$deletionArgs);

                // Call the delete method on the concrete class
                $deleteSuccess = $instance->delete($sku);

                if (!$deleteSuccess) {
                    // Handle the failure if needed
                    $success = false;
                    break;
                }
            } else {
                // Handle the case where the concrete class does not exist
                $success = false;
                break;
            }
        }

        return $success;
    }
}
    //     public static function massDelete($selectedProducts)
//     {
//         $success = true;

//         foreach ($selectedProducts as $product) {
//             $sku = $product['sku'];
//             $className = $product['tableName'];
//             $className = ucfirst($className);
//             $fullyQualifiedClassName = "\\$className";

//             if (class_exists($fullyQualifiedClassName, true)) {
//                 // Instantiate the class
//                 $productInstance = new $fullyQualifiedClassName($selectedProducts);

//                 // Call the delete method on the instantiated object
//                 $success = $productInstance->delete($sku);

//                 if (!$success) {
//                     // Handle the failure if needed
//                     break;
//                 }
//             } else {
//                 // Handle the case where the class doesn't exist
//                 $success = false;
//                 break;
//             }
//         }

//         return $success;
//     }

// }
