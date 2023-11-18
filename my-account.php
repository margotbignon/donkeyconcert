<?php 
    include "template/header.php";
    if (empty($_SESSION)) {
        header ('Location:login.php');
        die;
    }
?>
<h1 class="text-center">Mon compte</h1>
<ul class="nav nav-tabs container" role="tablist">
  <li class="nav-item" role="presentation">
    <a class="nav-link active" data-bs-toggle="tab" href="#home" aria-selected="true" role="tab">Mes concerts à venir</a>
  </li>
  <li class="nav-item" role="presentation">
    <a class="nav-link" data-bs-toggle="tab" href="#profile" aria-selected="false" tabindex="-1" role="tab">Mes concerts passés</a>
  </li>
</ul>
<div id="myTabContent" class="tab-content container">
  <div class="tab-pane fade show active container" id="home" role="tabpanel">
    <div class="mt-5 ms-5 row w-50 d-flex flex-row">
        <img src="img/mathieuchedid.jpg" class=" w-50 img-responsive p-2">
        <div class="mt-3 ms-4 p-2">   
            <p>Mathieu Chedid</p>
            <p>Je suis M</p>
            <p>23/12/2023 20:30</p> 
            <p>
           
           Prix : 40€
   </p>
   <p>
           
           Catégorie : 40€
   </p>
   Nombre de billets : 2
        </div>
        <div class=" d-flex flex-column align-items-center mt-5 text-center p-2">
           
            <a href="detailBooking.php?idconcert="><button type="button" class="btn btn-primary btn-lg " style="width:150%" >Modifier</button></a>
            <a href="detailBooking.php?idconcert="><button type="button" class="btn btn-primary btn-lg mt-3" style="width:150%" >Annuler</button></a>
        </div>    
    </div>
                    <hr class="border border-danger border-2 opacity-50 w-75 mx-auto mt-5">
  </div>
  <div class="tab-pane fade" id="profile" role="tabpanel">
    <p>Food truck fixie locavore, accusamus mcsweeney's marfa nulla single-origin coffee squid. Exercitation +1 labore velit, blog sartorial PBR leggings next level wes anderson artisan four loko farm-to-table craft beer twee. Qui photo booth letterpress, commodo enim craft beer mlkshk aliquip jean shorts ullamco ad vinyl cillum PBR. Homo nostrud organic, assumenda labore aesthetic magna delectus mollit.</p>
  </div>
</div>


<?php include "template/footer.php"; ?>
