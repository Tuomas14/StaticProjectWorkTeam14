<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Haetaan ht.asetukset.ini tiedosto ja luetaan sieltä tietokantayhteyden tarvitsemat tiedot (palvelimen nimi, käyttäjänimi, salasana ja tietokannan nimi)
$init=parse_ini_file("./.ht.asetukset.ini");

// Yritetään muodostaa yhteys tietokantaan seuraavilla tiedoilla.
try{
    $yhteys=mysqli_connect($init["palvelin"], $init ["tunnus"], $init["pass"], $init["tk"]);
}
// Jos yhteyttä tietokantaan ei voida muodostaa tai käyttäjätunnus/salasana on virheellinen, ohjataan käyttäjä yhteysvirhe sivulle.
catch(Exception $e){
    header("Location:../pages/yhteysvirhe.html");
    exit;
}

?>