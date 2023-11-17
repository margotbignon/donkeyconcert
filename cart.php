<?php 
    include "template/header.php";
    $pdo = connectDB();
    $iduser = 1;
    $sql =<<<SQL
    SELECT 
      ca.*, cd.dateConcert, cd.hourConcert, c.name as concert, a.name as artist, p.namePlace
    FROM
      donkeyconcert.cart ca 
    LEFT JOIN 
      donkeyconcert.concert_place_date cpd ON ca.idconcert_place_date = cpd.idconcert_place_date
    LEFT JOIN
      donkeyconcert.concert_date cd ON cpd.idconcert_date = cd.idconcert_date
    LEFT JOIN
      donkeyconcert.concert c ON cd.idconcert = c.idconcert
    LEFT JOIN
      donkeyconcert.artist a ON c.idartist = a.idartist
    LEFT JOIN
      donkeyconcert.place p ON cpd.idplace = p.idplace
    WHERE iduser = :iduser;
SQL;
  $statement=$pdo->prepare($sql);
  $statement->bindValue(':iduser', $iduser, PDO::PARAM_INT);
  $statement->execute();
  $cartBookings = $statement->fetchAll(PDO::FETCH_ASSOC);
  var_dump($cartBookings);
  $priceTotal = 0;

?>

<h1>Mon panier</h1>
<table class="table table-hover container text-center">
  <thead>
    <tr>
      <th scope="col">Nom concert</th>
      <th scope="col">Artiste</th>
      <th scope="col">Date</th>
      <th scope="col">Catégorie</th>
      <th scope="col">Nombre de billets</th>
      <th scope="col">Prix total</th>
      
    </tr>
  </thead>
  <tbody>
    <?php foreach($cartBookings as $cartBooking) : ?>
      <tr class="table-active">
        
        <td scope="row"><?=$cartBooking['concert']?></td>
        <td><?=$cartBooking['artist']?></td>
        <td><?=$cartBooking['dateConcert']?> <?=$cartBooking['hourConcert']?></td>
        <td><?=$cartBooking['namePlace']?></td>
        <td><?=$cartBooking['nb_tickets']?></td>
        <td><?=$cartBooking['priceTotal']?></td>
        <td>Modifier<br/><a href="delete.php?idcart=<?=$cartBooking['idcarte']?>&ref=cart&iduser=<?=$iduser?>">Supprimer</td>
        
      </tr>
        <?php 
            $priceTotal = $priceTotal + $cartBooking['priceTotal'];
        endforeach;?>
  </tbody>
</table> 
<div class="card border-primary mb-3 container text-center" style="max-width: 20rem; ">
  <div class="card-body">
    <h4 class="card-title">Prix total</h4>
    <p class="card-text"><?= $priceTotal ?> €</p>
  </div>

