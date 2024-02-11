<?php

abstract class Products
{
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

    protected function displayAll(){
        
    }
   
    
}
