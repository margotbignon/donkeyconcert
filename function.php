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


function categoryFilter(int $categorySearch) 
{
    $pdo = connectDB();
    $sql=<<<SQL
    SELECT 
        catc.idconcert, 
        c.name as concert, 
        c.img_concert, 
        a.name as artist,
        DATE_FORMAT(MIN(cd.dateConcert), '%d/%m/%Y') as dateMinFR, 
        DATE_FORMAT(MAX(cd.dateConcert), '%d/%m/%Y') as dateMaxFR 
    FROM 
        donkeyconcert.category_concert catc
    LEFT JOIN 
        donkeyconcert.concert c ON catc.idconcert = c.idconcert
    LEFT JOIN 
        donkeyconcert.artist a ON c.idartist = a.idartist
    LEFT JOIN 
        donkeyconcert.concert_date cd ON catc.idconcert = cd.idconcert
    WHERE 
        catc.idcategory = :categorySearch 
    GROUP BY 
        catc.idconcert 
SQL; 
    $statement=$pdo->prepare($sql);
    $statement->bindValue(':categorySearch', $categorySearch, PDO::PARAM_INT);
    $statement->execute();
    $array = $statement->fetchAll(PDO::FETCH_ASSOC);
    return $array;
}

function categoryAndDateFilter(string $dateStart, string $dateEnd, string $categorySearch) {
    $pdo = connectDB();
    $sql=<<<SQL
    SELECT 
        catc.idconcert, 
        c.name as concert, 
        c.img_concert, 
        a.name as artist,
        DATE_FORMAT(MIN(cd.dateConcert), '%d/%m/%Y') as dateMinFR, 
        DATE_FORMAT(MAX(cd.dateConcert), '%d/%m/%Y') as dateMaxFR 
    FROM 
        donkeyconcert.category_concert catc
    LEFT JOIN 
        donkeyconcert.concert c ON catc.idconcert = c.idconcert
    LEFT JOIN 
        donkeyconcert.artist a ON c.idartist = a.idartist
    LEFT JOIN 
        donkeyconcert.concert_date cd ON catc.idconcert = cd.idconcert
    WHERE 
        cd.dateConcert BETWEEN :dateStart AND :dateEnd AND catc.idcategory = :categorySearch
    GROUP BY 
        catc.idconcert
SQL; 

    $statement=$pdo->prepare($sql);
    $statement->bindValue(':dateStart', $dateStart, PDO::PARAM_STR);
    $statement->bindValue(':dateEnd', $dateEnd, PDO::PARAM_STR);
    $statement->bindValue(':categorySearch', $categorySearch, PDO::PARAM_STR);
    $statement->execute();
    $array = $statement->fetchAll(PDO::FETCH_ASSOC);
    return $array;
}

function dateFilter(string $dateStart, string $dateEnd) {
    $pdo = connectDB();
    $sql =<<<SQL
    SELECT 
        cd.idconcert,
        c.name as concert, 
        c.img_concert, 
        a.name as artist, 
        DATE_FORMAT(MIN(cd.dateConcert), '%d/%m/%Y') as dateMinFR, 
        DATE_FORMAT(MAX(cd.dateConcert), '%d/%m/%Y') as dateMaxFR 
    FROM 
        donkeyconcert.concert_date cd
    LEFT JOIN 
        donkeyconcert.concert c ON cd.idconcert = c.idconcert
    LEFT JOIN 
        donkeyconcert.artist a ON c.idartist = a.idartist
    WHERE 
        cd.dateConcert BETWEEN :dateStart AND :dateEnd
    GROUP BY 
        cd.idconcert
SQL;

    $statement=$pdo->prepare($sql);
    $statement->bindValue(':dateStart', $dateStart, PDO::PARAM_STR);
    $statement->bindValue(':dateEnd', $dateEnd, PDO::PARAM_STR);
    $statement->execute();
    $array = $statement->fetchAll(PDO::FETCH_ASSOC);
    return $array;
}

function getOneConcert(string $table, string $id, int $idGet) {
    $pdo = connectDB();
    $statement=$pdo->prepare("SELECT $table.* FROM $table WHERE $id=:getId");
    $statement->bindValue(':getId', $idGet, PDO::PARAM_INT);
    $statement->execute();
    $array=$statement->fetchAll(PDO::FETCH_ASSOC);
    return $array;
}

function getOneConcertJoin(string $id, string $idGet) {
    $pdo = connectDB();
    $sql = <<<SQL
    SELECT c.idconcert, 
    c.img_concert, c.name as concert,
    c.description, 
    a.name as artist, 
    DATE_FORMAT(MIN(cd.dateConcert), '%d/%m/%Y') as dateMinFR, 
    DATE_FORMAT(MAX(cd.dateConcert), '%d/%m/%Y') as dateMaxFR, 
    DATE_FORMAT(MIN(cd.dateConcert), '%Y-%m-%d') as dateMinFRInput, 
    DATE_FORMAT(MAX(cd.dateConcert), '%Y-%m-%d') as dateMaxFRInput 
    FROM 
        donkeyconcert.concert c 
    LEFT JOIN 
        donkeyconcert.artist a ON c.idartist = a.idartist 
    LEFT JOIN 
        donkeyconcert.concert_date cd ON c.idconcert = cd.idconcert 
    GROUP BY 
        c.idconcert
    HAVING 
        $id = :idGet
SQL;
    $statement = $pdo->prepare($sql);
    $statement->bindValue(':idGet', $idGet, PDO::PARAM_INT);
    $statement->execute();
    $array = $statement->fetchAll(PDO::FETCH_ASSOC);
    return $array;
}

function getCategoriesPlacementWhereConcert(string $id, string $idGet, string $dateSelection) {
    $pdo = connectDB();
    $sql=<<<SQL
    SELECT 
        p.namePlace,
        cpd.price,
        cpd.capacity_available
    FROM 
        donkeyconcert.concert_place_date cpd
    LEFT JOIN 
        donkeyconcert.place p ON cpd.idplace = p.idplace
    LEFT JOIN 
        donkeyconcert.concert_date cd ON cpd.idconcert_date = cd.idconcert_date
    WHERE $id = :idGet AND dateConcert = :dateSelection
    SQL;
    $statement = $pdo->prepare($sql);
    $statement->bindValue(':idGet', $idGet, PDO::PARAM_INT);
    $statement->bindValue(':dateSelection', $dateSelection, PDO::PARAM_STR);
    $statement->execute();
    $array = $statement->fetchAll(PDO::FETCH_ASSOC);
    return $array;
}



?>
