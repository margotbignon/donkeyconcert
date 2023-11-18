<?php
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
            if (!empty($_POST['option-insurance']) && $_POST['option-insurance'] == 'on') {
                insertOptions($iduser, 1, $dateSelection, $idconcert, 25);
            }
            foreach ($categoriesAdd as $id => $category){
                if ($category > 0) {
                    foreach ($categoriesPlacement as $categoryPlacement) {
                        if ($id == $categoryPlacement['idplace'] && $category > 0) {
                                $priceTotal = $category * $categoryPlacement['price'];
                            }
                        } 
                    }
                    InsertCartWithoutOptions($iduser, $dateSelection, $id, $idconcert, $category, $options, $idconcert_date, $priceTotal);
                    header('Location:cart.php');
                }
            }    
        }
    