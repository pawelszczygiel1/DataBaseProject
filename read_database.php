<?php
$db = new SQLite3('ludność.db');

function getAllVoivedeships($db) {
    return $db->query('SELECT DISTINCT IDWoj, Województwo from WojewództwoPowiat');
}
function getCounties($voivodeship, $db) {
    $ret = $db->prepare('SELECT DISTINCT woj.IDPow, Powiat FROM WojewództwoPowiat as woj join PowiatGmina as pow on 
                         woj.IDPow = pow.IDPow WHERE IDWoj = :voivodeship');
    $ret->bindParam(':voivodeship', $voivodeship, SQLITE3_INTEGER);
    return $ret->execute();
}

function getCommunities($county, $db) {
    $ret = $db->prepare('SELECT gm.IDGmina, Gmina FROM PowiatGmina as pow join Gmina as gm on 
                         gm.IDGmina = pow.IDGmina WHERE IDPow = :county');
    $ret->bindParam(':county', $county, SQLITE3_INTEGER);
    return $ret->execute();

}

function smallerThanVilliages ($i, $db) {
    $ret = $db->prepare('SELECT * from Gmina WHERE LudnośćWieś < :i');
    $ret->bindParam(':i', $i, SQLITE3_INTEGER);
    return $ret->execute();
}
function selectAllVoivodeship($voivodeship, $db) {
    $ret = $db->prepare('SELECT * FROM (WojewództwoPowiat as woj join PowiatGmina as pow on woj.IDPow = pow.IDPow)
                      as powiaty join Gmina on powiaty.IDGmina = Gmina.IDGmina WHERE Województwo = :voivodeship');
    $ret->bindParam(':voivodeship', $voivodeship, SQLITE3_TEXT);
    return $ret->execute();
}
function selectAllCounty($county, $db) {
    $ret = $db->prepare('SELECT * FROM  PowiatGmina  join Gmina on PowiatGmina.IDGmina = Gmina.IDGmina 
                    WHERE Powiat = :county');
    $ret->bindParam(':county', $county, SQLITE3_TEXT);
    return $ret->execute();
}
function selectAllCommunity($community, $db) {
    $ret = $db->prepare('SELECT * FROM  Gmina WHERE Gmina = :community1 OR Gmina = :community2 
                OR Gmina = :community3');
    $community1 = "G." . $community;
    $community2 = "M." . $community;
    $community3 = "M-W." . $community;
    $ret->bindParam(':community1', $community1, SQLITE3_TEXT);
    $ret->bindParam(':community2', $community2, SQLITE3_TEXT);
    $ret->bindParam(':community3', $community3, SQLITE3_TEXT);
    return $ret->execute();
}

function getCommunityPopulation($id, $db) {
    $ret = $db->prepare('SELECT Gmina, IDGmina,ifnull(LudnośćMiasto, 0) as LudnośćMiasto,  ifnull(LudnośćWieś, 0) as LudnośćWieś 
                      FROM  Gmina WHERE IDGmina  = :id');
    $ret->bindParam(':id', $id, SQLITE3_INTEGER);
    return $ret->execute();
}
function getCountyPopulation($id, $db) {
    $ret = $db->prepare('SELECT Powiat, sum(ifnull(LudnośćMiasto, 0)) as LudnośćMiasto ,sum(ifnull(LudnośćWieś, 0)) as LudnośćWieś
         from PowiatGmina join Gmina on PowiatGmina.IDGmina = Gmina.IDGmina 
        where IDPow = :id GROUP BY IDPow ');
    $ret->bindParam(':id', $id, SQLITE3_INTEGER);
    return $ret->execute();
}

function getVoivodeshipPopulation($id, $db) {
    $ret = $db->prepare('SELECT Województwo, sum(ifnull(LudnośćMiasto, 0)) as LudnośćMiasto ,sum(ifnull(LudnośćWieś, 0)) as LudnośćWieś
        from WojewództwoPowiat join PowiatGmina on WojewództwoPowiat.IDPow = PowiatGmina.IDPow join Gmina on PowiatGmina.IDGmina = Gmina.IDGmina 
        where IDWoj = :id  GROUP BY IDWoj');
    $ret->bindParam(':id', $id, SQLITE3_INTEGER);
    return $ret->execute();
}

?>
