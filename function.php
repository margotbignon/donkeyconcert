<?php function connectDB() {
    require_once 'connect.php';
    $pdo = new \PDO(DSN, USER, PASS);
    return $pdo;
}

function getTable($table) {
    $pdo = connectDB(); 
    $statement=$pdo->query("SELECT * FROM $table");
    $array = $statement->fetchAll(PDO::FETCH_ASSOC);
    return $array;

}

function getPrices(int $id)
{
    $pdo = connectDB(); 
    $statement = $pdo->query("SELECT cpd.price, cd.idconcert, cd.dateConcert FROM donkeyconcert.concert_date cd LEFT JOIN donkeyconcert.concert_place_date cpd ON cd.idconcert_date = cpd.idconcert_date WHERE cd.idconcert = $id");
    $price = $statement->fetchAll(PDO::FETCH_ASSOC);
    return $price;
}

function getMinPrice (array $array) {
        $priceMin = 1000000;
        for($i=0; $i < count($array) ; $i++) {
            if ($array[$i]['price'] <= $priceMin) {
                $priceMin = $array[$i]['price'];
            }
        }
        return $priceMin;
}



?>