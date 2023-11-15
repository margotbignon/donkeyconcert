<?php 
    include "template/header.php";
    $pdo = connectDB();
    $categories = getTable('donkeyconcert.category');
    $cities = getTable('donkeyconcert.city');
    $query="SELECT c.idconcert, c.name as concert, a.name as artist, DATE_FORMAT(MIN(cd.dateConcert), '%d/%m/%Y') as dateMinFR , DATE_FORMAT(MAX(cd.dateConcert), '%d/%m/%Y') as dateMaxFR FROM donkeyconcert.concert c LEFT JOIN donkeyconcert.artist a ON c.idartist = a.idartist LEFT JOIN donkeyconcert.concert_date cd ON c.idconcert = cd.idconcert GROUP BY c.idconcert";
    $statement = $pdo->query($query);
    $concerts = $statement->fetchAll(PDO::FETCH_ASSOC);
?>

<img class="img-header" src="img/concertimg2.jpg">
<h1 class="text-primary">Recherchez votre prochain concert !</h1>
<form>
    <div class="filter">
        <select name="city" class="btn-group btn btn-primary btn-lg button" role="group" aria-label="Button group with nested dropdown">
            <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
            <?php foreach ($cities as $city) : ?>
                <option value="" class="dropdown-item">Villes</option>
                <option value="<?= $city['idcity'] ?>" class="dropdown-item"><?= $city['name'] ?></option>
                <?php endforeach; ?>
            </div>
        </select>
        <select name="category" class="btn-group btn btn-primary btn-lg button" role="group" aria-label="Button group with nested dropdown">
            <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                <option value="" class="dropdown-item">Catégories</option>
                <?php foreach ($categories as $category) : ?>
                <option value="<?= $category['idcategory'] ?>" class="dropdown-item"><?= $category['name'] ?></option>
                <?php endforeach; ?>
            </div>
        </select>
            <p class="text-datefilter">Du</p>
            <input type="date" class="btn btn-outline-secondary" name="dateStart">
            <p class="text-datefilter">Au</p>
            <input type="date" class="btn btn-outline-secondary" name="dateEnd">
            <input type="submit" class="btn btn-info" value="Rerchercher">
    </div>
    
</form>
<?php foreach ($concerts as $concert) :?>
    <div class="mt-5 ms-5 row w-50">
        <img src="img/mathieuchedid.jpg" class="col-md-4 w-25 img-responsive">
        <div class="mt-3 ms-4 col-md-4">
            
            <p><?= $concert['artist'] ?></p>
            <p><?= $concert['concert'] ?></p>
            <p>Du <?= $concert['dateMinFR'] ?> Au <?= $concert['dateMaxFR'] ?></p>
            
        </div>
        <div class="col-md-4 d-flex flex-column align-items-center mt-5">
            <p class="offset-md-6">
                <?php $price = getPrices($concert['idconcert']);?>
                A partir de <?= getMinPrice($price)?> €
            </p>
            <button type="button" class="btn btn-primary btn-lg offset-md-6">Je réserve !</button>
        </div>    
    </div>
    <hr class="border border-danger border-2 opacity-50 w-75 mx-auto mt-5">
<?php endforeach; ?>
<?php include "template/footer.php" ?>