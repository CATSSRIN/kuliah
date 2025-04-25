<?php
    class Hewan
    {
        protected $jml_kaki;
        protected $warna_kulit;

        function __construct($jml_kaki, $warna_kulit)
        {
            $this->jml_kaki = $jml_kaki;
            $this->warna_kulit = $warna_kulit;
        }

        function berpindah()
        {
            echo "Hewan ini memiliki $this->jml_kaki kaki dan berwarna $this->warna_kulit.<br>";
        }

        function makan()
        {
            echo "Hewan ini sedang makan.<br>";
        }   
    }
?>