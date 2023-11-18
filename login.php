<?php
    include "template/header.php";
    $pdo = connectDB();
    if (!empty($_POST)) {
        $login = $_POST['email'];
        $password = $_POST['password'];
        $sql=<<<SQL
        SELECT * FROM donkeyconcert.user WHERE email = :login 
SQL;
        $statement = $pdo->prepare($sql);
        $statement->bindValue(':login', $login, PDO::PARAM_STR);
        $statement->execute();
        $users = $statement->fetchAll(PDO::FETCH_ASSOC);
        if($users && $password = $users[0]['password']) {
            $_SESSION['iduser'] = $users[0]['iduser'];
            $_SESSION['email'] = $users[0]['email'];
            header ('Location:index.php');
        }
    }
?>
<h1 class="text-center">Connectez-vous</h1>
<form class="container text-center" method='post'>
  <fieldset>
    <div class="form-group">
      <input type="email" class="form-control w-25 text-center mx-auto mt-3" id="exampleInputEmail1" aria-describedby="emailHelp" name="email" placeholder="Votre email">
    </div>
    <div class="form-group">
      <input type="password" class="form-control w-25 mx-auto text-center mt-3" id="exampleInputPassword1" placeholder="Votre mot de passe" name="password" autocomplete="off">
    </div>
    <button type="submit" class="btn btn-primary mt-3">Se connecter</button>
  </fieldset>
</form>
