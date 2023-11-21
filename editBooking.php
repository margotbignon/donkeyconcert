<?php 
    include "template/header.php";
    if (empty($_SESSION)) {
        header ('Location:login.php');
        die;
    }
    $pdo = connectDB();
    $iduser = $_SESSION['iduser'];
    $idBooking = $_GET['idbooking'];
    $idconcert = $_GET['idconcert'];
    $concert = getOneConcertJoin('idconcert', $idconcert);
    $booking = getRowOrder($iduser, $idBooking);
     if (!empty($_POST)) {
         $dateSelection = $_POST['dateSelection'];
         $categoriesPlacement = getCategoriesPlacementWhereConcert('idconcert', $idconcert, $dateSelection);
         $priceTotal=0;
         if (!empty($_POST['updatebooking'])) {
             $categoriesAdd = $_POST['categoryPlacement'];
             foreach ($categoriesAdd as $id => $category){
                 if ($category > 0) {
                     foreach ($categoriesPlacement as $categoryPlacement) {
                         if ($id == $categoryPlacement['idplace'] && $category > 0) {
                                 $priceTotal = $category * $categoryPlacement['price'];
                             }
                         } 
                    updateBooking($dateSelection, $id, $idconcert, $category, $priceTotal, $idBooking);
                        $sql=<<<SQL
                        UPDATE donkeyconcert.concert_place_date cpd
                        JOIN donkeyconcert.booking_concert bc ON cpd.idconcert_place_date = bc.idconcert_place_date
                        SET cpd.capacity_available = cpd.capacity_available - (:newNbTicket - :nbTicket) 
                        WHERE bc.idbooking_concert = :idbooking;
                           
SQL;
                    $statement = $pdo->prepare($sql);
                    $statement->bindValue(':nbTicket', $booking['nb_tickets'], PDO::PARAM_INT);
                    $statement->bindValue(':newNbTicket', $category, PDO::PARAM_INT);
                    $statement->bindValue(':idbooking', $booking['idbooking_concert'], PDO::PARAM_INT);
                    $statement->execute();

                    header('Location:my-account.php');
                }
             }    
         }
    }
?>
<h2 class="text-center mt-5">Modifiez votre réservation pour votre concert avec <?= $concert[0]['artist']?> !</h2>
<div class="mt-5 ms-5 container">
    <div class="row">
        <div class="col-md-3">
            <img src="img/<?= $concert[0]['img_concert'] ?>">
        </div>
        <div class="col-md-3 ms-n5 mt-2">
            <p><?= $concert[0]['artist'] ?></p>
            <p><?= $concert[0]['concert'] ?></p>
            <p>Rock</p>
            <p>Du <?= $concert[0]['dateMinFR'] ?> au <?= $concert[0]['dateMaxFR'] ?></p>
        </div>
        <div class="col-md-6 w-25 card text-white bg-primary mb-3 pt-4 text-center">
            <p><?= $concert[0]['description'] ?></p>
        </div>
    </div>
</div>
<form method='post'>
    <div class="mt-5  d-flex flex-row border justify-content-around">
        <div class="p-2 d-flex flex-column">
            <p class="text-center">Sélectionnez la date </p>
        
                <input class="btn btn-outline-secondary" type="date" name="dateSelection" min="<?= $booking['dateConcert'] ?>" max="<?= $booking['dateConcert'] ?>" value="<?php if (!empty($_POST['dateSelection'])) { echo $_POST['dateSelection']; } else { ?><?= $booking['dateConcert']?><?php } ?>">
                <input type="submit" value="Valider" class="btn btn-secondary mt-1">
            
        </div>
        <?php if (!empty($categoriesPlacement)) { ?>
            <div class="p-2">
            <p class="text-center">Choisissez vos billets</p>
            <fieldset name="category_placement[]">
                <?php foreach ($categoriesPlacement as $categoryPlacement) : ?>
                    <div class="mb-1">
                        <?php if ($categoryPlacement['idplace'] == $booking['idplace']) { 
                            if ($categoryPlacement['capacity_available'] == 0) { ?>
                            <p>Il n'y a plus de billets disponibles pour cette catégorie de placement. Veuillez choisir une autre date ou relancer une réservation sur une autre catégorie.</p>
                            <?php ; } else { ?> 
                            <?= $categoryPlacement['namePlace'] ?> <input  class="bg-secondary text-center border rounded ms-n2" name="categoryPlacement[<?= $categoryPlacement['idplace'] ?>] " type="number" max="<?=$categoryPlacement['capacity_available']?>" value="<?= $booking['nb_tickets']?>">
                            <?= $categoryPlacement['price'] ?>€/place
                        <?php ; }  } ?>
                    </div>
                <?php endforeach; ?>
            </fieldset>
            </div>
        <?php ; }  ?>
        
    </div>
    <div class="text-center mt-5">
        <input type="submit" class="btn btn-info mx-auto" value="Je mets à jour ma commande" name="updatebooking"></input>
    </div>
</form>