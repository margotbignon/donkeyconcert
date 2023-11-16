<?php 
    include "template/header.php";
    $pdo = connectDB();
?>

<h1>Mon panier</h1>
<table class="table table-hover container text-center">
  <thead>
    <tr>
      <th scope="col">Nom concert</th>
      <th scope="col">Artiste</th>
      <th scope="col">Date</th>
      <th scope="col">Catégorie</th>
      <th scope="col">Nombre de billets</th>
      <th scope="col">Prix total</th>
      
    </tr>
  </thead>
  <tbody>
    <tr class="table-active">
      <td scope="row">Je suis M</td>
      <td>Mathieu Chedid</td>
      <td>25 décembre 2023</td>
      <td>Catégorie 1 et Catégorie 2</td>
      <td>3</td>
      <td>80€</td>
      <td>Modifier</td>
    </tr>
    <tr>
      <th scope="row">Default</th>
      <td>Column content</td>
      <td>Column content</td>
      <td>Column content</td>
    </tr>
    <tr class="table-primary">
      <th scope="row">Primary</th>
      <td>Column content</td>
      <td>Column content</td>
      <td>Column content</td>
    </tr>

  </tbody>
</table>