<?php
include("./connect.php");

// Tarkista, onko varaustunnus asetettu ja ei ole tyhjä
if(isset($_POST['poistettava']) && !empty($_POST['poistettava'])) {
    $poistettava = $_POST['poistettava'];

    // Poista TILA-rivi k.o varaustunnukseen liittyen
    $sql_delete_tila = "DELETE FROM TILA WHERE varaustunnus=?";
    $stmt_delete_tila = $yhteys->prepare($sql_delete_tila);
    $stmt_delete_tila->bind_param("s", $poistettava);

    // Suoritetaan poisto
    $poisto_onnistui = false;
    if ($stmt_delete_tila->execute()) {

        // Poistetaan VARAUKSET-rivi k.o varaustunnukseen liittyen
        $sql_delete_varaus = "DELETE FROM VARAUKSET WHERE varaustunnus=?";
        $stmt_delete_varaus = $yhteys->prepare($sql_delete_varaus);
        $stmt_delete_varaus->bind_param("s", $poistettava);

        // Suoritetaan poisto
        if ($stmt_delete_varaus->execute()) {

            // Poistetaan ASIAKAS-rivi k.o varaustunnukseen liittyen
            $sql_delete_asiakas = "DELETE FROM ASIAKAS WHERE varaustunnus=?";
            $stmt_delete_asiakas = $yhteys->prepare($sql_delete_asiakas);
            $stmt_delete_asiakas->bind_param("s", $poistettava);

            // Suoritetaan poisto
            if ($stmt_delete_asiakas->execute()) {
                $poisto_onnistui = true;
            }
        }
    }

    // Suljetaan tietokantayhteys
    $yhteys->close();

    // Ohjataan käyttäjä poisto_onnistui.html-sivulle, jos poisto onnistui
    if ($poisto_onnistui) {
        header("Location: ../pages/poisto_onnistui.html");
        exit();
    }else {
        echo "Virhe! Varauksen poistaminen epäonnistui.";
    }
}
exit();
?>
