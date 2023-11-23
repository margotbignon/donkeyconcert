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

function search(string $search) 
{
    $pdo = connectDB();
    $sql=<<<SQL
    SELECT c.idconcert, 
        c.img_concert, 
        c.name as concert, 
        a.name as artist, 
        DATE_FORMAT(MIN(cd.dateConcert), '%d/%m/%Y') as dateMinFR , 
        DATE_FORMAT(MAX(cd.dateConcert), '%d/%m/%Y') as dateMaxFR 
    FROM 
        donkeyconcert.concert c 
    LEFT JOIN 
        donkeyconcert.artist a ON c.idartist = a.idartist 
    LEFT JOIN 
        donkeyconcert.concert_date cd ON c.idconcert = cd.idconcert 
    GROUP BY 
        c.idconcert
    HAVING c.name LIKE :search OR a.name LIKE :search
    
SQL; 
    $statement=$pdo->prepare($sql);
    $statement->bindValue(':search', '%'.$search.'%', PDO::PARAM_STR);
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

function getRow(string $table, string $id, int $idGet) {
    $pdo = connectDB();
    $statement=$pdo->prepare("SELECT $table.* FROM $table WHERE $id=:getId");
    $statement->bindValue(':getId', $idGet, PDO::PARAM_INT);
    $statement->execute();
    $array=$statement->fetch(PDO::FETCH_ASSOC);
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
        p.idplace,
        p.namePlace,
        cpd.price,
        cpd.capacity_available
    FROM 
        donkeyconcert.concert_place_date cpd
    LEFT JOIN 
        donkeyconcert.place p ON cpd.idplace = p.idplace
    LEFT JOIN 
        donkeyconcert.concert_date cd ON cpd.idconcert_date = cd.idconcert_date
    WHERE $id = :idGet AND dateConcert = :dateSelection AND cpd.capacity_available > 0
    SQL;
    $statement = $pdo->prepare($sql);
    $statement->bindValue(':idGet', $idGet, PDO::PARAM_INT);
    $statement->bindValue(':dateSelection', $dateSelection, PDO::PARAM_STR);
    $statement->execute();
    $array = $statement->fetchAll(PDO::FETCH_ASSOC);
    return $array;
}

function InsertCartWithoutOptions($iduser, $dateSelection, $categoryPlacement, $idconcert, $nbTickets, $priceTotal) {
    $pdo = connectDB();
    $sql=<<<SQL
    INSERT INTO 
        donkeyconcert.cart (iduser, idconcert_place_date, nb_tickets, idoption, idconcert_date, priceTotal) 
        VALUES 
        (:iduser, 
            (SELECT cpd.idconcert_place_date 
            FROM donkeyconcert.concert_place_date cpd
            LEFT JOIN donkeyconcert.concert_date cd ON cpd.idconcert_date = cd.idconcert_date
            WHERE cd.dateConcert = STR_TO_DATE(:dateSelection, '%Y-%m-%d') AND cpd.idplace = :categoryPlacement AND cd.idconcert = :idconcert),
        :nbTickets, NULL, NULL, :priceTotal);
SQL;
    $statement = $pdo->prepare($sql);
    $statement->bindValue(':iduser', $iduser, PDO::PARAM_INT);
    $statement->bindValue(':dateSelection', $dateSelection, PDO::PARAM_STR);
    $statement->bindValue(':categoryPlacement', $categoryPlacement, PDO::PARAM_STR);
    $statement->bindValue(':idconcert', $idconcert, PDO::PARAM_STR);
    $statement->bindValue(':nbTickets', $nbTickets, PDO::PARAM_STR);
    $statement->bindValue(':priceTotal', $priceTotal, PDO::PARAM_STR);
    $statement->execute();
}

function insertOptions($iduser, $options, $dateSelection, $idconcert, $priceTotal) {
    $pdo = connectDB();
    $sql=<<<SQL
    INSERT INTO
        donkeyconcert.cart (iduser, idconcert_place_date, nb_tickets, idoption, idconcert_date, priceTotal) 
    VALUES 
        (:iduser, NULL, NULL, :options, 
        (SELECT cd.idconcert_date
        FROM donkeyconcert.concert_date cd
        WHERE cd.dateConcert = STR_TO_DATE(:dateSelection, '%Y-%m-%d') AND cd.idconcert = :idconcert), 
        :priceTotal);
SQL;
    $statement = $pdo->prepare($sql);
    $statement->bindValue(':iduser', $iduser, PDO::PARAM_INT);
    $statement->bindValue(':options', $options, PDO::PARAM_STR);
    $statement->bindValue(':dateSelection', $dateSelection, PDO::PARAM_STR);
    $statement->bindValue(':idconcert', $idconcert, PDO::PARAM_INT);
    $statement->bindValue(':priceTotal', $priceTotal, PDO::PARAM_STR);
    $statement->execute();
}

function deleteRow(string $table, string $idDB, int $idGet) {
    $pdo = connectDB();
    $sql=<<<SQL
    DELETE FROM
        $table
    WHERE 
        $idDB = :idGet    
SQL;
    $statement = $pdo->prepare($sql);
    $statement->bindValue(':idGet', $idGet, PDO::PARAM_INT);
    $statement->execute();
}


function InsertBookingWithoutOptions($iduser, $idconcert_place_date, $nbTickets, $priceTotal, $idbooking) {
    $pdo = connectDB();
    $sql=<<<SQL
    INSERT INTO 
        donkeyconcert.booking_concert (iduser, idconcert_place_date, nb_tickets, idoption, idconcert_date, priceTotal, idbooking) 
        VALUES 
        (:iduser, 
        :idconcert_place_date,
        :nbTickets, NULL, NULL, :priceTotal, :idbooking);
SQL;
    $statement = $pdo->prepare($sql);
    $statement->bindValue(':iduser', $iduser, PDO::PARAM_INT);
    $statement->bindValue(':idconcert_place_date', $idconcert_place_date, PDO::PARAM_INT);
    $statement->bindValue(':nbTickets', $nbTickets, PDO::PARAM_STR);
    $statement->bindValue(':priceTotal', $priceTotal, PDO::PARAM_STR);
    $statement->bindValue(':idbooking', $idbooking, PDO::PARAM_INT);
    $statement->execute();
}

function createOrder($pdo, int $iduser, string $totalPrice) {
    $sql =<<<SQL
    INSERT INTO 
        donkeyconcert.booking (iduser, createdDate, totalPrice)
    VALUES
        (:iduser, NOW(), :totalPrice); 
SQL;
    $statement = $pdo->prepare($sql);
    $statement->bindValue(':iduser', $iduser, PDO::PARAM_INT); 
    $statement->bindValue(':totalPrice', $totalPrice);
    $statement->execute();
}

function insertBookingOptions($iduser, $options, $idconcert_date, $priceTotal, $idbooking) {
    $pdo = connectDB();
    $sql=<<<SQL
    INSERT INTO
        donkeyconcert.booking_concert (iduser, idconcert_place_date, nb_tickets, idoption, idconcert_date, priceTotal, idbooking) 
    VALUES 
        (:iduser, NULL, NULL, :options, 
        :idconcert_date, 
        :priceTotal, :idbooking);
SQL;
    $statement = $pdo->prepare($sql);
    $statement->bindValue(':iduser', $iduser, PDO::PARAM_INT);
    $statement->bindValue(':options', $options, PDO::PARAM_STR);
    $statement->bindValue(':idconcert_date', $idconcert_date, PDO::PARAM_INT);
    $statement->bindValue(':priceTotal', $priceTotal, PDO::PARAM_STR);
    $statement->bindValue(':idbooking', $idbooking, PDO::PARAM_INT);
    $statement->execute();
}

function getRowForDate ($parameter, int $iduser) {
    $pdo = connectDB();
    $sql=<<<SQL
    SELECT 
        cpd.idconcert_place_date, c.name as concert, a.name as artist, cd.dateConcert, cd.hourConcert, p.namePlace, bc.priceTotal, bc.nb_tickets, c.img_concert, bc.idbooking_concert, c.idconcert, bc.idbooking_concert, bc.iduser
    FROM 
        donkeyconcert.booking_concert bc
    LEFT JOIN 
        donkeyconcert.concert_place_date cpd ON bc.idconcert_place_date = cpd.idconcert_place_date
    LEFT JOIN 
        donkeyconcert.concert_date cd ON cpd.idconcert_date = cd.idconcert_date
    LEFT JOIN 
        donkeyconcert.place p ON cpd.idplace = p.idplace
    LEFT JOIN 
        donkeyconcert.concert c ON cd.idconcert = c.idconcert
    LEFT JOIN 
        donkeyconcert.artist a ON c.idartist = a.idartist
    WHERE 
        iduser = :iduser AND cd.dateConcert $parameter NOW()
SQL;
    $statement=$pdo->prepare($sql);
    $statement->bindValue(':iduser', $iduser, PDO::PARAM_INT);
    $statement->execute();
    $array=$statement->fetchAll(PDO::FETCH_ASSOC);
    return $array;
}

function getRowCart(int $iduser, int $idcart) {
    $pdo = connectDB();
    $sql=<<<SQL
     SELECT 
        cd.dateConcert, cd.hourConcert, p.idplace, p.namePlace, ca.priceTotal, ca.nb_tickets, ca.idconcert_place_date
    FROM 
        donkeyconcert.cart ca
    LEFT JOIN 
        donkeyconcert.concert_place_date cpd ON ca.idconcert_place_date = cpd.idconcert_place_date
    LEFT JOIN 
        donkeyconcert.concert_date cd ON cpd.idconcert_date = cd.idconcert_date
    LEFT JOIN 
        donkeyconcert.place p ON cpd.idplace = p.idplace
    LEFT JOIN 
        donkeyconcert.concert c ON cd.idconcert = c.idconcert
    LEFT JOIN 
        donkeyconcert.artist a ON c.idartist = a.idartist
    WHERE 
        iduser = :iduser AND ca.idcart = :idcart
SQL;
    $statement=$pdo->prepare($sql);
    $statement->bindValue(':iduser', $iduser, PDO::PARAM_INT);
    $statement->bindValue(':idcart', $idcart, PDO::PARAM_INT);
    $statement->execute();
    $array=$statement->fetch(PDO::FETCH_ASSOC);
    return $array;
}

function getRowOrder(int $iduser, int $idbookingConcert) {
    $pdo = connectDB();
    $sql=<<<SQL
     SELECT 
        cd.dateConcert, cd.hourConcert, p.idplace, p.namePlace, bc.priceTotal, bc.nb_tickets, bc.idconcert_place_date, bc.idbooking_concert
    FROM 
        donkeyconcert.booking_concert bc
    LEFT JOIN 
        donkeyconcert.concert_place_date cpd ON bc.idconcert_place_date = cpd.idconcert_place_date
    LEFT JOIN 
        donkeyconcert.concert_date cd ON cpd.idconcert_date = cd.idconcert_date
    LEFT JOIN 
        donkeyconcert.place p ON cpd.idplace = p.idplace
    LEFT JOIN 
        donkeyconcert.concert c ON cd.idconcert = c.idconcert
    LEFT JOIN 
        donkeyconcert.artist a ON c.idartist = a.idartist
    WHERE 
        iduser = :iduser AND bc.idbooking_concert = :idbooking
SQL;
    $statement=$pdo->prepare($sql);
    $statement->bindValue(':iduser', $iduser, PDO::PARAM_INT);
    $statement->bindValue(':idbooking', $idbookingConcert, PDO::PARAM_INT);
    $statement->execute();
    $array=$statement->fetch(PDO::FETCH_ASSOC);
    return $array;
}

function updateCart($dateSelection, $categoryPlacement, $idconcert, $nbTickets, $priceTotal, $idCart) {
    $pdo = connectDB();
    $sql=<<<SQL
    UPDATE 
        donkeyconcert.cart
    SET
        idconcert_place_date = (SELECT cpd.idconcert_place_date 
            FROM donkeyconcert.concert_place_date cpd
            LEFT JOIN donkeyconcert.concert_date cd ON cpd.idconcert_date = cd.idconcert_date
            WHERE cd.dateConcert = STR_TO_DATE(:dateSelection, '%Y-%m-%d') AND cpd.idplace = :categoryPlacement AND cd.idconcert = :idconcert),
            nb_tickets = :nbTickets,
            priceTotal = :priceTotal
            WHERE idcart = :idcart
SQL;

    $statement = $pdo->prepare($sql);
    $statement->bindValue(':dateSelection', $dateSelection, PDO::PARAM_STR);
    $statement->bindValue(':categoryPlacement', $categoryPlacement, PDO::PARAM_STR);
    $statement->bindValue(':idconcert', $idconcert, PDO::PARAM_STR);
    $statement->bindValue(':nbTickets', $nbTickets, PDO::PARAM_STR);
    $statement->bindValue(':priceTotal', $priceTotal, PDO::PARAM_STR);
    $statement->bindValue(':idcart', $idCart, PDO::PARAM_INT);
    $statement->execute();
}


function updateBooking($dateSelection, $categoryPlacement, $idconcert, $nbTickets, $priceTotal, $idBooking) {
    $pdo = connectDB();
    $sql=<<<SQL
    UPDATE 
        donkeyconcert.booking_concert
    SET
        idconcert_place_date = (SELECT cpd.idconcert_place_date 
            FROM donkeyconcert.concert_place_date cpd
            LEFT JOIN donkeyconcert.concert_date cd ON cpd.idconcert_date = cd.idconcert_date
            WHERE cd.dateConcert = STR_TO_DATE(:dateSelection, '%Y-%m-%d') AND cpd.idplace = :categoryPlacement AND cd.idconcert = :idconcert),
            nb_tickets = :nbTickets,
            priceTotal = :priceTotal
            WHERE idbooking_concert = :idbooking
SQL;

    $statement = $pdo->prepare($sql);
    $statement->bindValue(':dateSelection', $dateSelection, PDO::PARAM_STR);
    $statement->bindValue(':categoryPlacement', $categoryPlacement, PDO::PARAM_STR);
    $statement->bindValue(':idconcert', $idconcert, PDO::PARAM_STR);
    $statement->bindValue(':nbTickets', $nbTickets, PDO::PARAM_STR);
    $statement->bindValue(':priceTotal', $priceTotal, PDO::PARAM_STR);
    $statement->bindValue(':idbooking', $idBooking, PDO::PARAM_INT);
    $statement->execute();
}

function updateProfil($email, $firstname, $lastname, $iduser) {
    $pdo = connectDB();
    $sql=<<<SQL
    UPDATE donkeyconcert.user
    SET 
        email = :email,
        firstname = :firstname,
        lastname = :lastname
    WHERE iduser = :id
SQL;
    $statement = $pdo->prepare($sql); 
    $statement->bindValue(':email', $email, PDO::PARAM_STR);
    $statement->bindValue(':firstname', $firstname, PDO::PARAM_STR);
    $statement->bindValue(':lastname', $lastname, PDO::PARAM_STR);
    $statement->bindValue(':id', $iduser, PDO::PARAM_INT);
    $statement->execute();
}

?>
