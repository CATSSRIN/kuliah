<?php
    class monyet extends Hewan
    {
        protected $ekor;
        protected $kaki;
        protected $tangan;

        function berpindah()
        {
            echo "Saya melompat";
        }

        function makan()
        {
            echo "Saya makan dengan tangan";
        }
    }
?>