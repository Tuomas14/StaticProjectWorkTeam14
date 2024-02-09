<?php
include ("./connect.php");

// Tarkista yhteys
if ($yhteys->connect_error) {
    die("Yhteys epäonnistui: " . $yhteys->connect_error);
}

// Tarkista, onko lomakkeen tiedot lähetetty
if(isset($_POST['varaustunnus']) && isset($_POST['uusi_etunimi']) && isset($_POST['uusi_sukunimi']) && isset($_POST['uusi_sahkoposti']) && isset($_POST['uusi_puhelinnumero']) && isset($_POST['uusi_tila'])&& isset($_POST['uusi_varausaika'])&& isset($_POST['uudet_lisatiedot'])) {
    $varaustunnus = $_POST['varaustunnus'];
    $uusi_etunimi = $_POST['uusi_etunimi'];
    $uusi_sukunimi = $_POST['uusi_sukunimi'];
    $uusi_sahkoposti = $_POST['uusi_sahkoposti'];
    $uusi_puhelinnumero = $_POST['uusi_puhelinnumero'];
    $uusi_tila = $_POST['uusi_tila'];
    $uusi_varausaika = $_POST['uusi_varausaika'];
    $uudet_lisatiedot = $_POST['uudet_lisatiedot'];


    // Päivitä varauksen tiedot tietokantaan
    $sql1 = "UPDATE ASIAKAS SET etunimi = '$uusi_etunimi', sukunimi = '$uusi_sukunimi', sahkoposti = '$uusi_sahkoposti', puhelinnro = '$uusi_puhelinnumero' WHERE varaustunnus = '$varaustunnus';";
    $sql2 = "UPDATE VARAUKSET SET varausaika = '$uusi_varausaika', lisatiedot = '$uudet_lisatiedot' WHERE varaustunnus = '$varaustunnus';";
    $sql3 = "UPDATE TILA SET tilan_nimi = '$uusi_tila' WHERE varaustunnus = '$varaustunnus';";

    // Suorita jokainen päivityslause erikseen
    if ($yhteys->query($sql1) === TRUE && $yhteys->query($sql2) === TRUE && $yhteys->query($sql3) === TRUE) {
        echo "Varauksen tiedot päivitetty onnistuneesti!";
    } else {
        echo "Virhe päivitettäessä varauksen tietoja: " . $yhteys->error;
    }
    }
    $yhteys->close();
    ?>