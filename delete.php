<?php 
    include "function.php"; 
    $pdo = connectDB();
    $idCart = $_GET['idcart'];
    if ($_GET['ref'] == 'cart') {
        deleteRow('donkeyconcert.cart', 'idcart', $idCart);
        header('Location:cart.php');
    }

