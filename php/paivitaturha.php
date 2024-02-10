<?php
include ("./connect.php");

// Tarkista yhteys
if ($yhteys->connect_error) {
    die("Yhteys epäonnistui: " . $yhteys->connect_error);
}

// Tarkista, onko lomakkeen tiedot lähetetty
if(isset($_POST['varaustunnus'], $_POST['uusi_etunimi'], $_POST['uusi_sukunimi'], $_POST['uusi_sahkoposti'], $_POST['uusi_puhelinnumero'], $_POST['uusi_tila'], $_POST['uusi_varausaika'], $_POST['uudet_lisatiedot']) &&
   !empty($_POST['varaustunnus']) && !empty($_POST['uusi_etunimi']) && !empty($_POST['uusi_sukunimi']) && !empty($_POST['uusi_sahkoposti']) && !empty($_POST['uusi_puhelinnumero']) && !empty($_POST['uusi_tila']) && !empty($_POST['uusi_varausaika']) && !empty($_POST['uudet_lisatiedot'])) {
    $varaustunnus = $_POST['varaustunnus'];
    $uusi_etunimi = $_POST['uusi_etunimi'];
    $uusi_sukunimi = $_POST['uusi_sukunimi'];
    $uusi_sahkoposti = $_POST['uusi_sahkoposti'];
    $uusi_puhelinnumero = $_POST['uusi_puhelinnumero'];
    $uusi_tila = $_POST['uusi_tila'];
    $uusi_varausaika = $_POST['uusi_varausaika'];
    $uudet_lisatiedot = $_POST['uudet_lisatiedot'];
    
    // Aloita transaktio
    $yhteys->begin_transaction();

    // Valmistele lauseet
    $stmt1 = $yhteys->prepare("UPDATE ASIAKAS SET etunimi = ?, sukunimi = ?, sahkoposti = ?, puhelinnro = ? WHERE varaustunnus = ?");
    $stmt2 = $yhteys->prepare("UPDATE VARAUKSET SET varausaika = ?, lisatiedot = ? WHERE varaustunnus = ?");
    $stmt3 = $yhteys->prepare("UPDATE TILA SET tilan_nimi = ? WHERE varaustunnus = ?");

    // Tarkista lauseiden valmistelu
    if (!$stmt1 || !$stmt2 || !$stmt3) {
        $yhteys->rollback();
        die("Valmistelu epäonnistui: " . $yhteys->error);
    }

    // Sijoita parametrit ja suorita lauseet
    $stmt1->bind_param("sssss", $uusi_etunimi, $uusi_sukunimi, $uusi_sahkoposti, $uusi_puhelinnumero, $varaustunnus);
    $stmt2->bind_param("iss", $uusi_varausaika, $uudet_lisatiedot, $varaustunnus);
    $stmt3->bind_param("ss", $uusi_tila, $varaustunnus);

    // Suorita lauseet ja tarkista niiden onnistuminen
    $success = $stmt1->execute() && $stmt2->execute() && $stmt3->execute();
    
    if ($success) {
        $yhteys->commit();
        echo "Varauksen tiedot päivitetty!";
    } else {
        $yhteys->rollback(); // Peruuta muutokset
        echo "Virhe päivitettäessä varauksen tietoja: " . $yhteys->error;
    }

    // Sulje valmistellut lauseet
    $stmt1->close();
    $stmt2->close();
    $stmt3->close();
} else {
    echo "Virhe: Kaikki kentät on täytettävä ennen päivittämistä!";
}

$yhteys->close();
?>
