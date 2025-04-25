<?php

// Parent Class
class Product {
    protected $name;
    protected $price;
    protected $discount;

    public function getName() {
        return $this->name;
    }

    public function setName($name): void {
        $this->name = $name;
    }

    public function getPrice() {
        return $this->price;
    }

    public function setPrice($price): void {
        $this->price = $price;
    }

    public function getDiscount() {
        return $this->discount;
    }

    public function setDiscount($discount): void {
        $this->discount = $discount;
    }
}

// Child Class CDMusic
class CDMusic extends Product {
    private $artist;
    private $genre;

    public function getArtist() {
        return $this->artist;
    }

    public function setArtist($artist): void {
        $this->artist = $artist;
    }

    public function getGenre() {
        return $this->genre;
    }

    public function setGenre($genre): void {
        $this->genre = $genre;
    }
}

// Child Class CDCabinet
class CDCabinet extends Product {
    private $capacity;
    private $model;

    public function getCapacity() {
        return $this->capacity;
    }

    public function setCapacity($capacity): void {
        $this->capacity = $capacity;
    }

    public function getModel() {
        return $this->model;
    }

    public function setModel($model): void {
        $this->model = $model;
    }
}

?>
