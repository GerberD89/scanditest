<?php 

class Pbook extends Products {
    protected $tableName = 'pbook';
    public float $weight;

    public function __construct(float $id,string $sku, string $name, float $price, $dbc, float $weight){
        parent::__construct($id, $sku, $name, $price, $dbc);
    $this->weight = $weight;


    }

      public function setWeight(float $weight): void {
        $this->weight = $weight;
    }



    public function getWeight(): float {
        return $this->weight;
    }


    public function setAdditionalProperties($formData)
    {
        $this->weight = $formData['weight'] ?? null;
    }



    // Implement create method to insert data into the database
    public function create(): bool
    {
        $sql = "INSERT INTO pbook (id, sku, name, price, weight) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->dbc->prepare($sql);
        $result = $stmt->execute([$this->id, $this->sku, $this->name, $this->price, $this->weight]);

        return $result;
    }





    // Implement read method to retrieve data from the database
    public function read(float $id): void
    {

        $sql = "SELECT * FROM pbook WHERE id = ?";
        $stmt = $this->dbc->prepare($sql);
        $stmt->execute([$id]);
        $data = $stmt->fetch();

        if ($data) {
            // Populate object properties with retrieved data
            $this->id = $data['id'];
            $this->sku = $data['sku'];
            $this->name = $data['name'];
            $this->price = $data['price'];
            $this->weight = $data['weight'];
        }
    }

    // Implement update method to modify data in the database
    public function update(): bool
    {
        $sql = "UPDATE pbook SET sku = ?, name = ?, price = ?, weight = ? WHERE id = ?";
        $stmt = $this->dbc->prepare($sql);
        $result = $stmt->execute([$this->sku, $this->name, $this->price, $this->weight, $this->id]);

        return $result;
    }

    // Implement delete method to remove data from the database
    public function delete(string $sku): bool
    {
        $sql = "DELETE FROM pbook WHERE sku = ?";
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
        echo "weight: {$this->weight} MB<br>";
    }



    // sku validation method specific to pbook
    public function validateSKU(string $table, string $sku): bool
    {
        // Perform sku validation using the parent class method
        return parent::validateSKU(
            'pbook',
            $sku
        );
    }

    // Inside your concrete class (e.g., pbook.php)
    public function getAllProducts()
    {
        $sql = "SELECT * FROM pbook ORDER BY id";  // Change 'pbook' to the actual table name
        $stmt = $this->dbc->query($sql);

        // Fetch products and return an array of objects
        $products = [];
        while ($data = $stmt->fetch()) {
            $product = new Pbook(
                $data['id'],
                $data['sku'],
                $data['name'],
                $data['price'],
                $this->dbc,
                $data['weight']
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
            echo '<div class="card" style="width: 18rem;">';
            echo '<div class="m-1">';
            echo '<input type="checkbox" id="checkbox-' . htmlspecialchars($product->getSKU()) . '"aria-label="Checkbox for following text input" class="delete-checkbox" data-table="' . htmlspecialchars($this->tableName) . '">';
            echo '<label for="checkbox-' . htmlspecialchars($product->getSKU()) . '"></label>';
            echo '</div>';
            echo '<div class="card-body">';
            echo '<h6 class="sku" data-attribute="sku">' . htmlspecialchars($product->getSKU()) . '</h6>';
            echo '<h6 class="name" data-attribute="name">' . htmlspecialchars($product->getName()) . '</h6>';
            echo '<h6 class="price" data-attribute="price">' . '$ ' . number_format($product->getPrice(), 2, '.', ',') . ' </h6>';
            echo '<h6 class="weight" data-attribute="weight">' . 'Weight : ' . htmlspecialchars($product->getWeight()) . ' KG</h6>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
        }
        echo '</div>';
    }
    
}

