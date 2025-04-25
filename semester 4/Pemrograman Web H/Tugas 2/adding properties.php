<?php
    class Demo
    {
        public $name;
        public $age;

        public function __construct($name, $age)
        {
            $this->name = $name;
            $this->age = $age;
        }

        public function display()
        {
            echo "Name: " . $this->name . "<br>";
            echo "Age: " . $this->age . "<br>";
        }
    }
?>