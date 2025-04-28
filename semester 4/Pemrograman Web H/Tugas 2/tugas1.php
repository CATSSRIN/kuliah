<?php

class Product {
    protected $name;
    protected $price;
    protected $discount;

    public function __construct($name, $price, $discount) {
        $this->name = $name;
        $this->price = $price;
        $this->discount = $discount;
    }

    public function getPrice() {
        return $this->price;
    }

    public function setPrice($price) {
        $this->price = $price;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function getDiscount() {
        return $this->discount;
    }

    public function setDiscount($discount) {
        $this->discount = $discount;
    }
}

class CDMusic extends Product {
    private $artist;
    private $genre;

    public function __construct($name, $price, $discount, $artist, $genre) {
        parent::__construct($name, $price, $discount);
        $this->artist = $artist;
        $this->genre = $genre;
        $this->price *= 1.10; // Price + 10%
        $this->discount += 0.05; // Discount + 5%
    }

    public function getArtist() {
        return $this->artist;
    }

    public function setArtist($artist) {
        $this->artist = $artist;
    }

    public function getGenre() {
        return $this->genre;
    }

    public function setGenre($genre) {
        $this->genre = $genre;
    }
}

class CDCabinet extends Product {
    private $capacity;
    private $model;

    public function __construct($name, $price, $discount, $capacity, $model) {
        parent::__construct($name, $price, $discount);
        $this->capacity = $capacity;
        $this->model = $model;
        $this->price *= 1.15; // Price + 15%
    }

    public function getCapacity() {
        return $this->capacity;
    }

    public function setCapacity($capacity) {
        $this->capacity = $capacity;
    }

    public function getModel() {
        return $this->model;
    }

    public function setModel($model) {
        $this->model = $model;
    }
}

// Simulation

$cd = new CDMusic("Greatest Hits", 20, 0.2, "Artist 1", "Pop");
echo "CD: " . $cd->getName() . ", Price: " . $cd->getPrice() . ", Discount: " . $cd->getDiscount() . ", Artist: " . $cd->getArtist() . ", Genre: " . $cd->getGenre() . "<br>";

$cabinet = new CDCabinet("Storage Unit", 100, 0.1, 500, "Deluxe");
echo "Cabinet: " . $cabinet->getName() . ", Price: " . $cabinet->getPrice() . ", Discount: " . $cabinet->getDiscount() . ", Capacity: " . $cabinet->getCapacity() . ", Model: " . $cabinet->getModel() . "<br>";

?>