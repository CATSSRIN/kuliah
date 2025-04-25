<?php
    class burung extends hewan 
    {
        protected $sayap;

        function berpindah()
        {
            echo "Saya terbang";
        }

        function makan()
        {
            echo "Saya makan dengan mematuk";
        }
    }
?>