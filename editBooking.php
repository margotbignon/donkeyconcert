<?php 
    include "template/header.php";
    if (empty($_SESSION)) {
        header ('Location:login.php');
        die;
    }
    $iduser = $_SESSION['iduser'];
    $idcart = $_GET['idcart'];
    $booking = getRowCart($iduser, $idcart);
    var_dump($booking);
     if (!empty($_POST)) {
         $dateSelection = $_POST['dateSelection'];
         $categoriesPlacement = getCategoriesPlacementWhereConcert('idconcert', $idconcert, $dateSelection);
         $priceTotal=0;
         if (!empty($_POST['addcart'])) {
             $categoriesAdd = $_POST['categoryPlacement'];
             foreach ($categoriesAdd as $id => $category){
                 if ($category > 0) {
                     foreach ($categoriesPlacement as $categoryPlacement) {
                         if ($id == $categoryPlacement['idplace'] && $category > 0) {
                                 $priceTotal = $category * $categoryPlacement['price'];
                             }
                         } 
                     
                     InsertCartWithoutOptions($iduser, $dateSelection, $id, $idconcert, $category, $priceTotal);
                     header('Location:cart.php');
                    }
                 }
                 if (!empty($_POST['option-insurance']) && $_POST['option-insurance'] == 'on') {
                    insertOptions($iduser, 1, $dateSelection, $idconcert, 25);
                }
             }    
         }
    
?>
<h2 class="text-center mt-5">Modifiez votre réservation pour votre concert avec <?= $booking[0]['artist']?> en concert !</h2>
<div class="mt-5 ms-5 container">
    <div class="row">
        <div class="col-md-3">
            <img src="img/<?= $booking[0]['img_concert'] ?>">
        </div>
        <div class="col-md-3 ms-n5 mt-2">
            <p><?= $booking[0]['artist'] ?></p>
            <p><?= $booking[0]['concert'] ?></p>
            <p>Rock</p>
            <p>Du <?= $booking[0]['dateMinFR'] ?> au <?= $booking[0]['dateMaxFR'] ?></p>
        </div>
        <div class="col-md-6 w-25 card text-white bg-primary mb-3 pt-4 text-center">
            <p><?= $booking[0]['description'] ?></p>
        </div>
    </div>
</div>
<form method='post'>
    <div class="mt-5  d-flex flex-row border justify-content-around">
        <div class="p-2 d-flex flex-column">
            <p class="text-center">Sélectionnez la date </p>
        
                <input class="btn btn-outline-secondary" value="<?php if(!empty($_POST)) { echo $_POST['dateSelection']; }; ?>" type="date" name="dateSelection" min="<?= $booking[0]['dateMinFRInput'] ?>" max="<?= $booking[0]['dateMaxFRInput'] ?>">
                <input type="submit" value="Valider" class="btn btn-secondary mt-1">
            
        </div>
        <?php if (!empty($categoriesPlacement)) : ?>
            <div class="p-2">
            <p class="text-center">Choisissez vos billets</p>
            <fieldset name="category_placement[]">
                <?php foreach ($categoriesPlacement as $categoryPlacement) : ?>
                    <div class="mb-1">
                        <?= $categoryPlacement['namePlace'] ?> <input  class="bg-secondary text-center border rounded ms-n2" name="categoryPlacement[<?= $categoryPlacement['idplace'] ?>] " type="number" max="<?=$categoryPlacement['capacity_available']?>" value="0">
                        <?= $categoryPlacement['price'] ?>€/place
                    </div>
                <?php endforeach; ?>
            </fieldset>
            </div>
        <?php endif; ?>
    </div>
    <div class="text-center mt-5">
        <input type="submit" class="btn btn-info mx-auto" value="J'ajoute au panier" name="addcart"></input>
    </div>
</form>