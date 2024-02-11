<?php
class ProductFactory {
    public static function createProduct($userSelection, $dbc, $formData) {
        $className = ucfirst($userSelection);
        $fullyQualifiedClassName = "\\$className";

        if (class_exists($fullyQualifiedClassName, true)) {
            $requiredParameters = self::getRequiredParameters($dbc, $userSelection);

            if (!empty($requiredParameters)) {
                $constructorArgs = [];
                $constructorArgs[] = isset($formData['id']) ? (float)$formData['id'] : 0.0;
                $constructorArgs[] = isset($formData['sku']) ? $formData['sku'] : '';
                $constructorArgs[] = isset($formData['name']) ? $formData['name'] : '';
                $priceWithoutSpaces = preg_replace('/[^0-9.]/', '', $formData['price']);
                $constructorArgs[] = isset($formData['price']) ? (float)$priceWithoutSpaces : 0.0;
                $constructorArgs[] = $dbc;

                    foreach ($requiredParameters as $paramKey) {
                    $constructorArgs[] = $formData[$paramKey] ?? null;
                }

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
        $requiredParameters = explode(', ', $requiredProperties);
        return $requiredParameters;
    }

    public static function getRequiredProperties($dbc, $table_name) {
        $stmt = $dbc->prepare("SELECT required_properties FROM productmap WHERE table_name = :productType");
        $stmt->execute(['productType' => $table_name]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result['required_properties'] ?? '';
    }


    public static function massDelete($userSelection, $dbc, $additionalParameters)
    {
        $success = true;

        foreach ($additionalParameters as $product) {
            // Extract information needed for deletion
            $sku = $product['sku'];
            $tableName = $product['tableName']; 

            // Check if the concrete class exists
            $className = ucfirst($tableName);
            $fullyQualifiedClassName = "\\$className";
            if (class_exists($fullyQualifiedClassName, true)) {
                // Prepare arguments array for deletion
                $deletionArgs = [
                    'id' => $product['id'],
                    'sku' => $sku,
                    'name' => $product['name'], 
                    'price' => (float)$product['price'], 
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
    
