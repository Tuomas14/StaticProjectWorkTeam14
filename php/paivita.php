<?php
include("./connect.php");

// Tarkista yhteys
if ($yhteys->connect_error) {
    header("Location: ../pages/yhteysvirhe.html");
    exit;
}

// Tarkista, onko lomakkeen tiedot lähetetty ja ovatko kaikki kentät täytetty
if (
    isset($_POST['varaustunnus'], $_POST['uusi_etunimi'], $_POST['uusi_sukunimi'], $_POST['uusi_sahkoposti'], $_POST['uusi_puhelinnumero']) &&
    !empty($_POST['varaustunnus']) && !empty($_POST['uusi_etunimi']) && !empty($_POST['uusi_sukunimi']) && !empty($_POST['uusi_sahkoposti']) && !empty($_POST['uusi_puhelinnumero'])
) {
    // Nämä rivit tallentavat lomakkeelta lähetetyn tiedon tietokantaan
    $varaustunnus = $_POST['varaustunnus'];
    $uusi_etunimi = $_POST['uusi_etunimi'];
    $uusi_sukunimi = $_POST['uusi_sukunimi'];
    $uusi_sahkoposti = $_POST['uusi_sahkoposti'];
    $uusi_puhelinnumero = $_POST['uusi_puhelinnumero'];

    try {
        // Prepare statement
        $stmt = $yhteys->prepare("UPDATE ASIAKAS SET etunimi = ?, sukunimi = ?, sahkoposti = ?, puhelinnro = ? WHERE varaustunnus = ?");
        
        // Bind parameters
        $stmt->bind_param("ssssi", $uusi_etunimi, $uusi_sukunimi, $uusi_sahkoposti, $uusi_puhelinnumero, $varaustunnus);
        
        // Execute statement
        $stmt->execute();
        
        // Redirect on success
        header("Location: ../pages/muokkausonnistui.html");
        exit();
    } catch (Exception $e) {
        // Error handling
        header("Location: ../pages/yhteysvirhe.html");
        exit;
    }
}

// Sulje tietokantayhteys
$yhteys->close();
?>

