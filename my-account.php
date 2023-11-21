<?php 
    include "template/header.php";
    if (empty($_SESSION)) {
        header ('Location:login.php');
        die;
    }
    $iduser = $_SESSION['iduser'];
    $upcomingConcerts = getRowForDate('>', $iduser);
    $pastConcerts = getRowForDate('<', $iduser);
    $profil = getRow('donkeyconcert.user', 'iduser', $iduser);
    if (!empty($_POST)) {
      var_dump($_POST);
      $email = $_POST['email'];
      $firstname = $_POST['firstname'];
      $lastname = $_POST['lastname'];    
      updateProfil($email, $firstname, $lastname, $iduser);
      header('Location:index.php');
    }
?>
<h1 class="text-center">Mon compte</h1>
<ul class="nav nav-tabs container" role="tablist">
  <li class="nav-item" role="presentation">
    <a class="nav-link active" data-bs-toggle="tab" href="#upComingConcerts" aria-selected="true" role="tab">Mes concerts à venir</a>
  </li>
  <li class="nav-item" role="presentation">
    <a class="nav-link" data-bs-toggle="tab" href="#pastConcerts" aria-selected="false" tabindex="-1" role="tab">Mes concerts passés</a>
  </li>
  <li class="nav-item" role="presentation">
    <a class="nav-link" data-bs-toggle="tab" href="#profil" aria-selected="false" tabindex="-1" role="tab">Mon profil</a>
  </li>
</ul>

<div id="myTabContent" class="tab-content container">
  <div class="tab-pane fade show active container" id="upComingConcerts" role="tabpanel"> 
  <?php if(empty($upcomingConcerts)) { ?>
    <p>Vous n'avez pas de concerts à venir</p>
  <?php ; die; }?>
  <?php foreach ($upcomingConcerts as $upcomingConcert) : ?>
        <div class="mt-5 ms-5 row  d-flex flex-row">
            <div class="d-flex flex-row">
                <img src="img/<?=$upcomingConcert['img_concert']?>" class=" w-25 img-responsive p-2">
                <div class="mt-3 ms-4 p-2">   
                    <p><?=$upcomingConcert['concert']?></p>
                    <p><?=$upcomingConcert['artist']?></p>
                    <p><?=$upcomingConcert['dateConcert']?> <?=$upcomingConcert['hourConcert']?></p> 
                    <p>
                
                    Prix : <?=$upcomingConcert['priceTotal']?> €
                    </p>
                    <p>Catégorie : <?=$upcomingConcert['namePlace']?></p>Nombre de billets : <?=$upcomingConcert['nb_tickets']?>
                </div>
                <div class=" d-flex flex-column align-items-center mt-5 text-center p-2 ms-5">
                    <a href="editBooking.php?iduser=<?=$upcomingConcert['iduser']?>&idbooking=<?=$upcomingConcert['idbooking_concert']?>&idconcert=<?=$upcomingConcert['idconcert']?>&idevent=<?=$upcomingConcert['idconcert_place_date']?>"><button type="button" class="btn btn-primary btn-lg " style="width:150%" >Modifier</button></a>
                    <a href="delete.php?ref=account&idbooking_concert=<?=$upcomingConcert['idbooking_concert']?>&nb=<?=$upcomingConcert['nb_tickets']?>&idevent=<?=$upcomingConcert['idconcert_place_date']?>"><button type="button" class="btn btn-primary btn-lg mt-3" style="width:150%" >Annuler</button></a>
                </div>    
            </div>
        </div>
        <hr class="border border-danger border-2 opacity-50 w-75 mx-auto mt-5">
        <?php ; endforeach ; ?>
    </div>

  <div class="tab-pane fade" id="pastConcerts" role="tabpanel">
 
  <?php foreach ($pastConcerts as $pastConcert) : ?>
        <div class="mt-5 ms-5 row  d-flex flex-row">
            <div class="d-flex flex-row">
                <img src="img/<?=$upcomingConcert['img_concert']?>" class=" w-25 img-responsive p-2">
                <div class="mt-3 ms-4 p-2">   
                    <p><?=$pastConcert['concert']?></p>
                    <p><?=$pastConcert['artist']?></p>
                    <p><?=$pastConcert['dateConcert']?> <?=$pastConcert['hourConcert']?></p> 
                    <p>
                
                    Prix : <?=$pastConcert['priceTotal']?> €
                    </p>
                    <p>Catégorie : <?=$pastConcert['namePlace']?></p>Nombre de billets : <?=$pastConcert['nb_tickets']?>
                </div>
                
            </div>
        </div>
        <hr class="border border-danger border-2 opacity-50 w-75 mx-auto mt-5">
        <?php ; endforeach ; ?>
  </div>
  <div class="tab-pane fade" id="profil" role="tabpanel">

    <form class="container mt-5" method="post">
      <fieldset>
        <legend>Mes informations</legend>
        <div class="form-group row">
        </div>
        <div class="form-group">
          <label for="exampleInputEmail1" class="form-label mt-4">Adresse email</label>
          <input type="email" class="form-control w-25" id="exampleInputEmail1" aria-describedby="emailHelp" value="<?= $profil['email'] ?>" name="email">
        </div>
        <div class="form-group">
          <label for="exampleInputPassword1" class="form-label mt-4 ">Prénom</label>
          <input type="text" class="form-control w-25" id="exampleInputPassword1" value="<?= $profil['firstname'] ?>" autocomplete="off" name="firstname">
        </div>
        <div class="form-group">
          <label for="exampleInputPassword1" class="form-label mt-4">Nom</label>
          <input type="text" class="form-control w-25" id="exampleInputPassword1" value="<?= $profil['lastname'] ?>" autocomplete="off" name="lastname">
        </div>
        <div class="form-group">
      <label for="formFile" class="form-label mt-4">Photo de profil</label>
      <input class="form-control" type="file" id="formFile" accept="image/png, image/jpeg">
      </div>
        <button type="submit" class="btn btn-primary mt-2" name="update-profil">Mettre à jour</button>
      </fieldset>
    </form>
  
  </div>
</div>


<?php include "template/footer.php"; ?>
