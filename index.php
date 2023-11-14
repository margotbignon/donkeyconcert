<?php 
    include "template/header.php";

?>

<img class="img-header" src="img/concertimg2.jpg">
<h1 class="text-primary">Recherchez votre prochain concert !</h1>
<form>
    <div class="filter">
        <select name="city" class="btn-group btn btn-primary btn-lg button" role="group" aria-label="Button group with nested dropdown">
            <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                <option value="" class="dropdown-item">Villes</option>
                <option value="Paris" class="dropdown-item">Paris</option>
            </div>
        </select>
        <select name="city" class="btn-group btn btn-primary btn-lg button" role="group" aria-label="Button group with nested dropdown">
            <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                <option value="" class="dropdown-item">Catégories</option>
                <option value="Paris" class="dropdown-item">Rock</option>
            </div>
        </select>
            <p class="text-datefilter">Du</p>
            <input type="date" class="btn btn-outline-secondary" name="dateStart">
            <p class="text-datefilter">Au</p>
            <input type="date" class="btn btn-outline-secondary" name="dateEnd">
            <input type="submit" class="btn btn-info" value="Rerchercher">
    </div>
    
</form>
<div class="mt-5 ms-5 row w-50">
    
        <img src="img/mathieuchedid.jpg" class="col-md-4 w-25 img-responsive">
        <div class="mt-3 ms-4 col-md-4">
            <p>Mathieu Chedid</p>
            <p>Tournée 2023</p>
            <p>23, 24 et 25 décembre 2025</p>
            <p>Zenith de Paris</p>
        </div>
        <div class="col-md-4 d-flex flex-column align-items-center mt-5">
            <p class="offset-md-6">A partir de €</p>
            <button type="button" class="btn btn-primary btn-lg offset-md-6">Je réserve !</button>
        </div>    
    
    
</div>
<hr class="border border-danger border-2 opacity-50 w-75 mx-auto mt-5">
<?php include "template/footer.php" ?>