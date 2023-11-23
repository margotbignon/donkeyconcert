<?php 
    include "template/header.php";
    $pdo = connectDB();
    $categories = getTable('donkeyconcert.category');
    $cities = getTable('donkeyconcert.city');
    $filterNotFund = false;
    $page = $_GET['page'] ?? 1;
    $nbResultsPerPage = 2;
    $offset = ($page -1) * $nbResultsPerPage;
    if (!empty($_POST)) {
        $activateFilter = true;
        if (!empty($_POST['search'])) {
            $search = $_POST['search'];
            $concerts = search($search);

        }
        if (!empty($_POST['category']) && empty($_POST['dateStart']) && empty($_POST['dateEnd'])) {
            $categorySearch = $_POST['category'];
            $concerts = categoryFilter($categorySearch);
        }
        if (!empty($_POST['dateStart']) && !empty($_POST['dateEnd']) && empty($_POST['category'])) {
            $dateStart = $_POST['dateStart'];
            $dateEnd = $_POST['dateEnd'];
            $concerts = dateFilter($dateStart, $dateEnd);
           
        }
        if (!empty($_POST['category']) && !empty($_POST['dateStart']) && !empty($_POST['dateEnd'])) {
            $categorySearch = $_POST['category'];
            $dateStart = $_POST['dateStart'];
            $dateEnd = $_POST['dateEnd'];
            $concerts = categoryAndDateFilter($dateStart, $dateEnd, $categorySearch);
            
        }
        if (!isset($concerts)) {
            $filterNotFund = true;
        }
    } else {
        $query = "SELECT COUNT(c.idconcert) as total FROM donkeyconcert.concert c";
        $statement = $pdo->query($query);
        $count = $statement->fetchColumn(0);
        $nbPages = ceil($count / $nbResultsPerPage);
        $query="SELECT c.idconcert, c.img_concert, c.name as concert, a.name as artist, DATE_FORMAT(MIN(cd.dateConcert), '%d/%m/%Y') as dateMinFR , DATE_FORMAT(MAX(cd.dateConcert), '%d/%m/%Y') as dateMaxFR FROM donkeyconcert.concert c LEFT JOIN donkeyconcert.artist a ON c.idartist = a.idartist LEFT JOIN donkeyconcert.concert_date cd ON c.idconcert = cd.idconcert GROUP BY c.idconcert LIMIT $offset, $nbResultsPerPage";
        $statement = $pdo->query($query);
        $concerts = $statement->fetchAll(PDO::FETCH_ASSOC);

    }

?>

<img class="img-header" src="img/concertimg2.jpg">
<h1 class="text-primary">Recherchez votre prochain concert !</h1>
<form method="post">
    <div class="filter">
        <select name="category" class="btn-group btn btn-primary btn-lg button" role="group" aria-label="Button group with nested dropdown">
            <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                <option value="" class="dropdown-item">Catégories</option>
                <?php foreach ($categories as $category) : ?>
                <option value="<?= $category['idcategory'] ?>" name="category" class="dropdown-item"><?= $category['name'] ?></option>
                <?php endforeach; ?>
            </div>
        </select>
            <p class="text-datefilter">Du</p>
            <input type="date" class="btn btn-outline-secondary" name="dateStart" value="$_POST['dateStart']">
            <p class="text-datefilter">Au</p>
            <input type="date" class="btn btn-outline-secondary" name="dateEnd" value="$_POST['dateEnd']">
            <input type="submit" class="btn btn-info" value="Rechercher">
    </div>
    
</form>
<?php if ($filterNotFund) : ?>
<p class="mt-5 ms-5">Aucun concert ne correspond à votre recherche.</p>
<p class="ms-5"><a href="index.php">Retour</a></p>
<?php die;
endif;?>
<?php if (!empty($_POST)) : ?>
<p class="mt-5 ms-5"><a href="index.php">Effacer le filtre</a></p>
<?php endif; ?>
<?php foreach ($concerts as $concert) :?>
        <div class="mt-5 ms-5 row w-50">
            <img src="img/<?= $concert['img_concert'] ?>" class="col-md-4 w-25 img-responsive">
            <div class="mt-3 ms-4 col-md-4">   
                <p><?= $concert['artist'] ?></p>
                <p><?= $concert['concert'] ?></p>
                <p>Du <?= $concert['dateMinFR'] ?> Au <?= $concert['dateMaxFR'] ?></p> 
            </div>
                    <div class="col-md-4 d-flex flex-column align-items-center mt-5 text-center">
                        <p class="offset-md-6 text-center">
                            <?php $price = getPrices($concert['idconcert']);?>
                            A partir de <?= getMinPrice($price)?>€
                        </p>
                        <a href="detailBooking.php?idconcert=<?= $concert['idconcert'] ?>"><button type="button" class="btn btn-primary btn-lg offset-md-6" style="width:150%" >Je réserve</button></a>
                    </div>    
                </div>
                <hr class="border border-danger border-2 opacity-50 w-75 mx-auto mt-5">
            <?php endforeach; 
        ?>
<?php if (!empty($nbPages)) : ?>
<nav class="btn-toolbar container d-flex justify-content-center" role="toolbar" aria-label="Toolbar with button groups">
  <ul class="btn-group me-2" role="group" aria-label="First group">
    <?php for ($i = 1; $i <= $nbPages; $i++) : ?>
    <li type="button" class="btn btn-secondary"><a class="text-white text-decoration-none" href="index.php?page=<?= $i; ?>"><?= $i; ?></a></li>

    <?php endfor ?>
    </ul>

    </nav>
<?php endif ?>
<?php include "template/footer.php" ?>