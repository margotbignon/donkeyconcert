<?php 
    include "function.php"; 
    $pdo = $connectDB();
    $idCart = $_GET['idcart'];
    if ($_GET['ref'] == 'cart') {
        deleteRow('donkeyconcert.cart', 'idcarte', $idCart);
    }

