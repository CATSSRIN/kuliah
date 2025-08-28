<?php
$page = $_get['page'] ?? 'home';

if ($page == 'home') {
    echo "selamat datang di halaman home";
}
    elseif ($page == 'about') {
        echo "selamat datang di halaman about";
    }
        else if ($page == 'contact') {
            echo "selamat datang di halaman contact";
        }
            else {
                echo "halaman tidak ditemukan";
            }
            ?>