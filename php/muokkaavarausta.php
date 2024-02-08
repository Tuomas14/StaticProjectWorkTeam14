<?php
include ("./connect.php");

// Tarkista yhteys
if ($yhteys->connect_error) {
    die("Yhteys epäonnistui: " . $yhteys->connect_error);
}

// Tarkista, onko lomakkeen tiedot lähetetty
if(isset($_POST['varaustunnus']) && isset($_POST['uusi_etunimi']) && isset($_POST['uusi_sukunimi']) && isset($_POST['uusi_sahkoposti']) && isset($_POST['uusi_puhelinnumero'])) {
    $varaustunnus = $_POST['varaustunnus'];
    $uusi_etunimi = $_POST['uusi_etunimi'];
    $uusi_sukunimi = $_POST['uusi_sukunimi'];
    $uusi_sahkoposti = $_POST['uusi_sahkoposti'];
    $uusi_puhelinnumero = $_POST['uusi_puhelinnumero'];
    
    // Päivitä varauksen tiedot tietokantaan
    $sql = "UPDATE ASIAKAS SET etunimi = '$uusi_etunimi', sukunimi = '$uusi_sukunimi', sahkoposti = '$uusi_sahkoposti', puhelinnro = '$uusi_puhelinnumero' WHERE varaustunnus = '$varaustunnus'";
    if ($yhteys->query($sql) === TRUE) {
        echo "Varauksen tiedot päivitetty onnistuneesti!";
    } else {
        echo "Virhe päivitettäessä varauksen tietoja: " . $yhteys->error;
    }
}

$yhteys->close();
?>