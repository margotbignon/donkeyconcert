<?php 
    include "template/header.php";
    if (empty($_SESSION)) {
        header ('Location:login.php');
        die;
    }
    $iduser = $_SESSION['iduser'];
    $idCart = $_GET['idcart'];
    $idconcert = $_GET['idconcert'];
    $concert = getOneConcertJoin('idconcert', $idconcert);
    $booking = getRowCart($iduser, $idCart);
    var_dump($booking);
     if (!empty($_POST)) {
         $dateSelection = $_POST['dateSelection'];
         $categoriesPlacement = getCategoriesPlacementWhereConcert('idconcert', $idconcert, $dateSelection);
         $priceTotal=0;
         if (!empty($_POST['updatecart'])) {
             $categoriesAdd = $_POST['categoryPlacement'];
             foreach ($categoriesAdd as $id => $category){
                 if ($category > 0) {
                     foreach ($categoriesPlacement as $categoryPlacement) {
                         if ($id == $categoryPlacement['idplace'] && $category > 0) {
                                 $priceTotal = $category * $categoryPlacement['price'];
                             }
                         } 
                     
                    updateCart($dateSelection, $id, $idconcert, $category, $priceTotal, $idCart);
                    header('Location:cart.php');
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
        
                <input class="btn btn-outline-secondary" type="date" name="dateSelection" min="<?= $concert[0]['dateMinFRInput'] ?>" max="<?= $concert[0]['dateMaxFRInput'] ?>" value="<?php if (!empty($_POST['dateSelection'])) { echo $_POST['dateSelection']; } else { ?><?= $booking['dateConcert']?><?php } ?>">
                <input type="submit" value="Valider" class="btn btn-secondary mt-1">
            
        </div>
        <?php if (!empty($categoriesPlacement)) : ?>
            <div class="p-2">
            <p class="text-center">Choisissez vos billets</p>
            <fieldset name="category_placement[]">
                <?php foreach ($categoriesPlacement as $categoryPlacement) : ?>
                    <div class="mb-1">
                        <?php if ($categoryPlacement['idplace'] == $booking['idplace']) { ?>
                            <?= $categoryPlacement['namePlace'] ?> <input  class="bg-secondary text-center border rounded ms-n2" name="categoryPlacement[<?= $categoryPlacement['idplace'] ?>] " type="number" max="<?=$categoryPlacement['capacity_available']?>" value="<?= $booking['nb_tickets']?>">
                            <?= $categoryPlacement['price'] ?>€/place
                        <?php ; } ?>
                    </div>
                <?php endforeach; ?>
            </fieldset>
            </div>
        <?php endif; ?>
    </div>
    <div class="text-center mt-5">
        <input type="submit" class="btn btn-info mx-auto" <?php if ($_GET['ref'] == 'cart') { ?>value="Je mets à jour mon panier" name="updatecart" <?php ; } else { ?>value="Je mets à jour ma commande" name="updatebooking"<?php ; } ?> ></input>
    </div>
</form>