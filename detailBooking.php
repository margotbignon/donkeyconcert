<?php 
    include "template/header.php";
   
    $idconcert = $_GET['idconcert'];
    $concert = getOneConcertJoin('idconcert', $idconcert);
    $optionsDB = getTable('donkeyconcert.options');
    $iduser = 1;
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
                            if (!empty($_POST['option-insurance']) && $_POST['option-insurance'] == 'on') {
                                $priceTotal = $optionsDB[0]['price'] + ($category * $categoryPlacement['price']);
                                $options = 1;
                            } else {
                                $priceTotal = $category * $categoryPlacement['price'];
                                $options = NULL;
                            }
                        } 
                    }
                    InsertCart($iduser, $dateSelection, $id, $idconcert, $category, $options, $priceTotal);
                }
            }    
        }
    }
    
?>
<h2 class="text-center mt-5">Voyez <?= $concert[0]['artist']?> en concert !</h2>
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
        
                <input class="btn btn-outline-secondary" value="<?php if(!empty($_POST)) { echo $_POST['dateSelection']; }; ?>" type="date" name="dateSelection" min="<?= $concert[0]['dateMinFRInput'] ?>" max="<?= $concert[0]['dateMaxFRInput'] ?>">
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
    <div class="mt-5 ms-5 container">
            <?php foreach ($optionsDB as $optionDB) : ?>
            <input class="form-check-input" type="checkbox" name="option-insurance" id="flexCheckChecked">
            <label class="form-check-label" for="flexCheckChecked">
            Je prends l'<?= $optionDB['name'] ?> à <?= $optionDB['price'] ?>€
            </label>
            <?php endforeach ;?>
    </div>
    <div class="text-center mt-5">
        <input type="submit" class="btn btn-info mx-auto" value="J'ajoute au panier" name="addcart"></input>
    </div>
</form>
