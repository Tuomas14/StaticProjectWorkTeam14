<?php
include ("./connect.php");

// Tarkista yhteys
if ($yhteys->connect_error) {
    exit("Yhteys epäonnistui: " . $yhteys->connect_error);
}

// Tarkista, onko lomakkeen tiedot lähetetty ja ovatko kaikki kentät täytetty
if(isset($_POST['varaustunnus'], $_POST['uusi_etunimi'], $_POST['uusi_sukunimi'], $_POST['uusi_sahkoposti'], $_POST['uusi_puhelinnumero']) &&
   !empty($_POST['varaustunnus']) && !empty($_POST['uusi_etunimi']) && !empty($_POST['uusi_sukunimi']) && !empty($_POST['uusi_sahkoposti']) && !empty($_POST['uusi_puhelinnumero'])) {
    $varaustunnus = $_POST['varaustunnus'];
    $uusi_etunimi = $_POST['uusi_etunimi'];
    $uusi_sukunimi = $_POST['uusi_sukunimi'];
    $uusi_sahkoposti = $_POST['uusi_sahkoposti'];
    $uusi_puhelinnumero = $_POST['uusi_puhelinnumero'];
    
// Päivitä varauksen tiedot tietokantaan
$sql = "UPDATE ASIAKAS SET etunimi = '$uusi_etunimi', sukunimi = '$uusi_sukunimi', sahkoposti = '$uusi_sahkoposti', puhelinnro = '$uusi_puhelinnumero' WHERE varaustunnus = '$varaustunnus'";
if ($yhteys->query($sql) === TRUE) {
    header("Location: ../pages/muokkausonnistui.html");
    exit();
} else {
    echo "Virhe päivitettäessä varauksen tietoja: " . $yhteys->error;
}}

// Sulje tietokantayhteys
$yhteys->close();

?>