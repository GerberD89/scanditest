<?php

abstract class Products
{

    //properties inside the class
    public float $id;
    public string $sku;
    public string $name;
    public float $price;
    public $dbc;

    public function __construct(float $id, string $sku, string $name, float $price, $dbc)
    {
        $this->id = $id;
        $this->sku = $sku;
        $this->name = $name;
        $this->price = $price;
        $this->dbc = $dbc;
    }
    
    public function setSKU(string $sku): void
    {
        $this->sku = $sku;
    }

    public function getSKU(): string
    {
        return $this->sku;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setPrice(float $price): void
    {
        $this->price = $price;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    // Abstract methods to be implemented by concrete classes
    abstract public function create(): bool;
    abstract public function read(float $id): void;
    abstract public function update(): bool;
    abstract public function delete(string $sku): bool;
/*
    protected function validateSKU(string $table, string $sku): bool
    {
        // Use cURL or another HTTP request method to call the SKU validation script
        $ch = curl_init('checkInputDuplication.php');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, ['table' => $table, 'sku' => $sku]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);

        // Decode the JSON response
        $result = json_decode($response, true);

        // Return the result of the SKU validation
        return $result['valid'];
    }

    protected function validateName(string $name): bool
    {
        // Implement name validation logic
        return true;
    }

    protected function validatePrice(float $price): bool
    {
        // Implement price validation logic
        return true;
    }

    protected function displayAll(){
        
    }
    // Add more validation methods as needed
    */
}
