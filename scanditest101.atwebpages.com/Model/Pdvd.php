<?php

class Pdvd extends Products {
	// Additional property specific to Pdvd
	public float $size;
    // Table name for CRUD operations
    protected $tableName = 'pdvd';
    public function __construct(float $id, string $sku, string $name, float $price, $dbc, float $size) {
    parent::__construct($id, $sku, $name, $price, $dbc);
    $this->size = $size;
    }

    public function setSize(float $size): void {
        $this->size = $size;
    }

    public function getSize(): float {
        return $this->size;
    }

    public function setAdditionalProperties($formData) {
        $this->size = $formData['size'] ?? null;
    }

    public function create(): bool {
    $sql = "INSERT INTO $this->tableName (id, sku, name, price, size) VALUES (?, ?, ?, ?, ?)";
    $stmt = $this->dbc->prepare($sql);
    $result = $stmt->execute([$this->id, $this->sku, $this->name, $this->price, $this->size]);
    return $result;

    }

    public function read(float $id): void {
        $sql = "SELECT * FROM pdvd WHERE id = ?";
        $stmt = $this->dbc->prepare($sql);
        $stmt->execute([$id]);
        $data = $stmt->fetch();

        if ($data) {
            $this->id = $data['id'];
            $this->sku = $data['sku'];
            $this->name = $data['name'];
            $this->price = $data['price'];
            $this->size = $data['size'];
        }
    }

        public function update(): bool {
        $sql = "UPDATE pdvd SET sku = ?, name = ?, price = ?, size = ? WHERE id = ?";
        $stmt = $this->dbc->prepare($sql);
        $result = $stmt->execute([$this->sku, $this->name, $this->price, $this->size, $this->id]);
        return $result;
        }

        public function delete(string $sku): bool {
        $sql = "DELETE FROM pdvd WHERE sku = ?";
        $stmt = $this->dbc->prepare($sql);
        $result = $stmt->execute([$sku]);

        return $result;
        }

        public function display(): void {
        echo "Displaying DVD information:<br>";
        echo "ID: {$this->id}<br>";
        echo "sku: {$this->sku}<br>";
        echo "name: {$this->name}<br>";
        echo "price: {$this->price}<br>";
        echo "size: {$this->size} MB<br>";
        }

    public function getAllProducts()
    {
        $sql = "SELECT * FROM pdvd ORDER BY id"; 
        $stmt = $this->dbc->query($sql);
        $products = [];
        while ($data = $stmt->fetch()) {
            $product = new Pdvd(
                $data['id'],
                $data['sku'],
                $data['name'],
                $data['price'],
                $this->dbc,
                $data['size']
            );
            $products[] = $product;
        }
        return $products;
    }

    public function displayAll()
    {
        $products = $this->getAllProducts();
        echo '<div class="row mb-3 mt-3 mx-5">';
        foreach ($products as $product) {
            echo '<div class="col-md-2 mb-3">';
            echo '<div class="card" style="width: 18rem;">';
            echo '<div class="m-1">';
            echo '<input type="checkbox" id="checkbox-' . htmlspecialchars($product->getSKU()) . '" aria-label="Checkbox for following text input" class="delete-checkbox" data-table="' . htmlspecialchars($this->tableName) . '">';
            echo '<label for="checkbox-' . htmlspecialchars($product->getSKU()) . '"></label>';
            echo '</div>';
            echo '<div class="card-body">';
            echo '<h6 class="sku" data-attribute="sku">' . htmlspecialchars($product->getSKU()) . '</h6>';
            echo '<h6 class="name" data-attribute="name">' . htmlspecialchars($product->getName()) . '</h6>';
            echo '<h6 class="price" data-attribute="price">' . '$ ' . number_format($product->getPrice(), 2, '.', ',') . ' </h6>';
            echo '<h6 class="size" data-attribute="size">' . 'Size : ' . htmlspecialchars($product->getSize()) . ' MB</h6>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
        }
        echo '</div>';

    }




    
}

