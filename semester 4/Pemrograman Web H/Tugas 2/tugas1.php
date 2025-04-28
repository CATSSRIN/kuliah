<?php

echo '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Display</title>
    <style>
        body {
            background-color: #2c2c2c;
            color: #ffffff;
            font-family: Arial, sans-serif;
        }
    </style>
</head>
<body>';

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
        $this->price *= 1.10; // harga (100%) + 10%
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
        $this->price *= 1.15; // harga (100%) + 15%
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

// Simulasi

$cd = new CDMusic("Greatest Hits", 20, 0.2, "Artist 1", "Pop");
echo "CD: <br>\n";
echo "Name: " . $cd->getName() . "<br>\n";
echo "Price: " . $cd->getPrice() . "<br>\n";
echo "Discount: " . $cd->getDiscount() . "<br>\n";
echo "Artist: " . $cd->getArtist() . "<br>\n";
echo "Genre: " . $cd->getGenre() . "<br>\n";
echo " " . "<br>\n";

$cabinet = new CDCabinet("Storage Unit", 100, 0.1, 500, "Deluxe");
echo "Cabinet: <br>\n";
echo "Name: " . $cabinet->getName() . "<br>\n";
echo "Price: " . $cabinet->getPrice() . "<br>\n";
echo "Discount: " . $cabinet->getDiscount() . "<br>\n";
echo "Capacity: " . $cabinet->getCapacity() . "<br>\n";
echo "Model: " . $cabinet->getModel() . "<br>\n";

?>