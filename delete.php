<?php 
    include "function.php"; 
    $pdo = connectDB();
    if ($_GET['ref'] == 'cart') {
        $idCart = $_GET['idcart'];
        deleteRow('donkeyconcert.cart', 'idcart', $idCart);
        header('Location:cart.php');
    }
    if ($_GET['ref'] == 'account') {
        $idbookingConcert = $_GET['idbooking_concert'];
        $nbTickets = $_GET['nb'];
        $idConcertPlaceDate = $_GET['idevent'];
        deleteRow('donkeyconcert.booking_concert', 'idbooking_concert', $idbookingConcert);
        $sql =<<<SQL
        UPDATE 
          donkeyconcert.concert_place_date
        SET capacity_available = capacity_available + :nbTickets
        WHERE idconcert_place_date = :idconcert_place_date
SQL;
        $statement = $pdo->prepare($sql);
        $statement->bindValue('nbTickets', $nbTickets, PDO::PARAM_INT);
        $statement->bindValue('idconcert_place_date', $idConcertPlaceDate, PDO::PARAM_INT);
        $statement->execute();
        header('Location:my-account.php');

    }
    

