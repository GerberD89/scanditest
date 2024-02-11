<?php

class Pfurniture extends Products
{
    protected $tableName = 'pfurniture';
    // Additional property specific to Pdvd
    public float $length;
    public float $width;
    public float $height;

    public function __construct(float $id, string $sku, string $name, float $price, $dbc, float $length, float $width, float $height)
    {
        parent::__construct($id, $sku, $name, $price, $dbc);
        $this->length = $length;
        $this->width = $width;
        $this->height = $height;
    }

    public function setLength(float $length): void
    {
        $this->length = $length;
    }
    public function getLength(): float
    {
        return $this->length;
    }

    public function setWidth(float $width): void
    {
        $this->width = $width;
    }
    public function getWidth(): float
    {
        return $this->width;
    }

    public function setHeight(float $height): void
    {
        $this->height = $height;
    }
    public function getHeight(): float
    {
        return $this->height;
    }

    public function setAdditionalProperties($formData)
    {
        $this->length = $formData['length'] ?? null;
        $this->width = $formData['width'] ?? null;
        $this->height = $formData['height'] ?? null;
    }

    // Implement create method to insert data into the database
    public function create(): bool
    {

        $sql = "INSERT INTO pfurniture (id, sku, name, price, length, width, height) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->dbc->prepare($sql);
        $result = $stmt->execute([$this->id, $this->sku, $this->name, $this->price, $this->length, $this->width, $this->height]);

        return $result;
    }

    // Implement read method to retrieve data from the database
    public function read(float $id): void
    {
        $sql = "SELECT * FROM pfurniture WHERE id = ?";
        $stmt = $this->dbc->prepare($sql);
        $stmt->execute([$id]);
        $data = $stmt->fetch();

        if ($data) {
            // Populate object properties with retrieved data
            $this->id = $data['id'];
            $this->sku = $data['sku'];
            $this->name = $data['name'];
            $this->price = $data['price'];
            $this->length = $data['length'];
            $this->width = $data['width'];
            $this->height = $data['height'];
        }
        echo "Data: {$this-> $data}<br><hr>";
    }

    // Implement update method to modify data in the database
    public function update(): bool
    {
        $sql = "UPDATE pfurniture SET sku = ?, name = ?, price = ?, length = ?, width = ?, height = ? WHERE id = ?";
        $stmt = $this->dbc->prepare($sql);
        $result = $stmt->execute([$this->sku, $this->name, $this->price,$this->length, $this->width, $this->height, $this->id]);

        return $result;
    }

    // Implement delete method to remove data from the database
    public function delete(string $sku): bool
    {
        $sql = "DELETE FROM pfurniture WHERE sku = ?";
        $stmt = $this->dbc->prepare($sql);
        $result = $stmt->execute([$sku]);

        return $result;
    }

    // Implement display method to show information about the DVD
    public function display(): void
    {
        echo "Displaying DVD information:<br>";
        echo "ID: {$this->id}<br>";
        echo "sku: {$this->sku}<br>";
        echo "name: {$this->name}<br>";
        echo "price: {$this->price}<br>";
        echo "length: {$this->length} MB<br>";
        echo "width: {$this->width} MB<br>";
        echo "height: {$this->height} MB<br>";
    }

    // SKU validation method specific to Pdvd
    public function validateSKU(string $table, string $sku): bool
    {
        // Perform SKU validation using the parent class method
        return parent::validateSKU(
            'pfurniture',
            $sku
        );
    }

    // Inside your concrete class (e.g., Pdvd.php)
    public function getAllProducts()
    {
        $sql = "SELECT * FROM pfurniture ORDER BY id";  // Change 'pdvd' to the actual table name
        $stmt = $this->dbc->query($sql);

        // Fetch products and return an array of objects
        $products = [];
        while ($data = $stmt->fetch()) {
            $product = new Pfurniture(
                $data['id'],
                $data['sku'],
                $data['name'],
                $data['price'],
                $this->dbc,
                $data['length'],
                $data['width'],
                $data['height']
            );

            $products[] = $product;
        }

        return $products;
    }


    public function displayAll()
    {

        $products = $this->getAllProducts();

        // Display the HTML structure for each product
        echo '<div class="row mb-3 mt-3 mx-5">';
        foreach ($products as $product) {
            echo '<div class="col-md-2 mb-3">';
            echo '<div class="card" style="width: 13rem;">';
            echo '<div class="m-1">';
            echo '<input type="checkbox" id="checkbox-' . htmlspecialchars($product->getSKU()) . '"aria-label="Checkbox for following text input" class="delete-checkbox" data-table="' . htmlspecialchars($this->tableName) . '">';
            echo '<label for="checkbox-' . htmlspecialchars($product->getSKU()) . '"></label>';
            echo '</div>';
            echo '<div class="card-body">';
            echo '<h6 class="sku" data-attribute="sku">' . htmlspecialchars($product->getSKU()) . '</h6>';
            echo '<h6 class="name" data-attribute="name">' . htmlspecialchars($product->getName()) . '</h6>';
            echo '<h6 class="price" data-attribute="price">' . '$ ' . number_format($product->getPrice(), 2, '.', ',') . ' </h6>';
            echo '<h6 class="width" data-attribute="width">' . 'Width : ' . htmlspecialchars($product->getWidth()) . ' CM</h6>';
            echo '<h6 class="length" data-attribute="length">' . 'Length : ' . htmlspecialchars($product->getLength()) . ' CM</h6>';
            echo '<h6 class="height" data-attribute="height">' . 'Height : ' . htmlspecialchars($product->getHeight()) . ' CM</h6>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
        }
        echo '</div>';

    }
}
