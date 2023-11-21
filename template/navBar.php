<?php
    session_start();
?>


<nav class="navbar navbar-expand-lg bg-dark" data-bs-theme="dark">
  <div class="container-fluid d-flex  justify-content-between">
        <div class="p-2 d-flex ">
            <a class="navbar-brand " href="#">DonkeyConcert</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarColor02" aria-controls="navbarColor02" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarColor02">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                    <a class="nav-link active" href="index.php">Concerts
                        <span class="visually-hidden">(current)</span>
                    </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="p-2">
        <form class="d-flex" method='post' action='index.php'>
            <input class="form-control me-sm-2" type="search" placeholder="concert ou artiste" name="search">
            <input type="submit" class="btn btn-secondary my-2 my-sm-0" type="submit" value="Rechercher">
        </form>
        </div>
        <div class="p-2 d-flex mt-1">
            <a href="cart.php"><i class="fa-solid fa-cart-shopping fa-2xl iconsnav" style="color: #ffffff;"></i></a>
            <a href="my-account.php"><i class="fa-solid fa-user fa-2xl" style="color: #ffffff;"></i></a>
            <?php if (!empty($_SESSION['iduser'])) :?>
                <a href="deletesession.php" class="ms-2 mt-n1">Se déconnecter</a>
            <?php ; endif;?>
        </div>
    </div>
</nav>