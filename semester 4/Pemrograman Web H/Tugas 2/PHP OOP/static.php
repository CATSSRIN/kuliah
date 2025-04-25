<?php
    class Foo{
        public static $my_static = 'foo';
        public function staticValue(){
            return self::$my_static;
        }
    }

    class Bar extends Foo{
        public function staticValue(){
            return parent::$my_static;
        }
    }

    print Foo::$my_static . "<br>";
    $foo = new Foo();
    print $foo->staticValue() . "<br>";
    print $foo->$my_static . "<br>";
    print $foo::$my_static . "<br>";
    print bar::$my_static . "<br>";
    $bar = new Bar();
    print $bar->staticValue() . "<br>";
?>