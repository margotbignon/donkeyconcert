<?php 
include "template/header.php";
$idconcert = $_GET['idconcert'];
$concert = getOneConcertJoin('idconcert', $idconcert);

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
<div class="mt-5  d-flex flex-row border justify-content-around">
    <div class="p-2">
        <p class="text-center">Sélectionnez la date </p>
        <input class="btn btn-outline-secondary" type="date" min="<?= $concert[0]['dateMinFRInput'] ?>" max="<?= $concert[0]['dateMaxFRInput'] ?>">
    </div>
    <div class="p-2">
    <p class="text-center">Choisissez vos billets</p>
    Catégorie 1 <input  class="bg-secondary text-center border rounded ms-n2" type="number" value="0">
    </div>
</div>
<div class="mt-5 ms-5 container">
        <input class="form-check-input" type="checkbox" value="" id="flexCheckChecked" checked="">
        <label class="form-check-label" for="flexCheckChecked">
          Je prends l'assurance annulation à X€
        </label>
</div>


