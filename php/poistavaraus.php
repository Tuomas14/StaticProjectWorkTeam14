<?php
// Sisällytetään connect.php, joka sisältää yhteyden muodostamisen tietokantaan
include("./connect.php");

    // Tarkistetaan, onko varaustunnus asetettu ja ei ole tyhjä
    if(isset($_POST['poistettava']) && !empty($_POST['poistettava'])) {
    $poistettava = $_POST['poistettava'];

    // Poistetaan TILA-rivi k.o varaustunnukseen liittyen. Käytetään ? tietoturvan takia.
    $sql_delete_tila = "DELETE FROM TILA WHERE varaustunnus=?";
    $stmt_delete_tila = $yhteys->prepare($sql_delete_tila); // Valmistellaan tietokantayhteys tilan poistamiseen
    $stmt_delete_tila->bind_param("s", $poistettava); // Suoritetaan valmistelu

    // Suoritetaan poisto
    $poisto_onnistui = false;
    if ($stmt_delete_tila->execute()) {

        // Poistetaan VARAUKSET-rivi k.o varaustunnukseen liittyen. Käytetään ? tietoturvan takia.
        $sql_delete_varaus = "DELETE FROM VARAUKSET WHERE varaustunnus=?";
        $stmt_delete_varaus = $yhteys->prepare($sql_delete_varaus); // Valmistellaan tietokantayhteys tilan poistamiseen
        $stmt_delete_varaus->bind_param("s", $poistettava); // Suoritetaan valmistelu

        // Suoritetaan poisto
        if ($stmt_delete_varaus->execute()) {

            // Poistetaan ASIAKAS-rivi k.o varaustunnukseen liittyen. Käytetään ? tietoturvan takia.
            $sql_delete_asiakas = "DELETE FROM ASIAKAS WHERE varaustunnus=?";
            $stmt_delete_asiakas = $yhteys->prepare($sql_delete_asiakas); // Valmistellaan tietokantayhteys tilan poistamiseen
            $stmt_delete_asiakas->bind_param("s", $poistettava); // Suoritetaan valmistelu

            // Suoritetaan poisto
            if ($stmt_delete_asiakas->execute()) {
                $poisto_onnistui = true;
            }
        }
    }

    // Suljetaan tietokantayhteys
    $yhteys->close();

    // Ohjataan käyttäjä poisto_onnistui.html-sivulle, jos poisto onnistui. Jos ei, annetaan virheilmoitus.
    if ($poisto_onnistui) {
        header("Location: ../pages/poisto_onnistui.html");
        exit();
    }else {
        echo "Virhe! Varauksen poistaminen epäonnistui.";
    }
}
exit(); // Lopetetaan skriptin suoritus PHP-koodin suorittamisen jälkeen
?>