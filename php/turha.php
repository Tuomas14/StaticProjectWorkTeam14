<?php
    include ("./connect.php");
    include ("../pages/muokkaavarausta.html");

    // Tarkista yhteys
    if ($yhteys->connect_error) {
        die("Yhteys epäonnistui: " . $yhteys->connect_error);
    }

    // Tarkista onko käyttäjä lähettänyt varaustunnuksen lomakkeella
    if(isset($_POST['varaustunnus']) && !empty($_POST['varaustunnus'])) {
        $varaustunnus = $_POST['varaustunnus'];
    
    // Hae varauksen tiedot tietokannasta
    $sql = "SELECT ASIAKAS.*, VARAUKSET.varausaika, VARAUKSET.lisatiedot, TILA.tilan_nimi
    FROM ASIAKAS
    INNER JOIN VARAUKSET ON ASIAKAS.varaustunnus = VARAUKSET.varaustunnus
    INNER JOIN TILA ON VARAUKSET.varaustunnus = TILA.varaustunnus
    WHERE ASIAKAS.varaustunnus = '$varaustunnus'";

    $result = $yhteys->query($sql);

    if ($result->num_rows > 0) {
        // Tulosta varauksen tiedot ja mahdollista muokkaaminen
        $row = $result->fetch_assoc();
        echo "Etunimi: " . $row['etunimi'] . "<br>"; // Näytetään etunimi
        echo "Sukunimi: " . $row['sukunimi'] . "<br>";
        echo "Sahkoposti: " . $row['sahkoposti'] . "<br>";
        echo "Puhelinnro: " . $row['puhelinnro'] . "<br>";
        echo "Tila: " . $row['tilan_nimi'] . "<br>";
        echo "Varauksen kesto: " . $row['varausaika'] . "<br>";
        echo "Lisatiedot: " . $row['lisatiedot'] . "<br>";
        $etunimi = $row['etunimi'];
        $sukunimi = $row['sukunimi'];
        $sahkoposti = $row['sahkoposti'];
        $puhelinnumero = $row['puhelinnro'];
        $tilan_nimi = $row['tilan_nimi'];
        $varausaika = $row['varausaika'];
        $lisatiedot = $row['lisatiedot'];
        

        // Lisää lomake muokkaamiseen
        echo '<form method="post" action="paivitavaraus.php">';
        echo 'Varaustunnus: <input type="text" name="varaustunnus" readonly value=" ' . $varaustunnus . '"><br><br>';
        echo 'Uusi etunimi: <input type="text" name="uusi_etunimi" value="' . $etunimi . '"><br><br>';
        echo 'Uusi sukunimi: <input type="text" name="uusi_sukunimi" value="' . $sukunimi . '"><br><br>';
        echo 'Uusi sähköposti: <input type="text" name="uusi_sahkoposti" value="' . $sahkoposti . '"><br><br>';
        echo 'Uusi puhelinnumero: <input type="text" name="uusi_puhelinnumero" value="' . $puhelinnumero . '"><br><br>';


        echo '<input type="radio" name="uusi_tila" value="Iso kabinetti" ' . ($tilan_nimi == "Iso kabinetti" ? "checked" : "") . ' required/>Iso kabinetti (40-50 hlö) <p><em>75e/h</em></p> <br>';
        echo '<input type="radio" name="uusi_tila" value="Pieni kabinetti" ' . ($tilan_nimi == "Pieni kabinetti" ? "checked" : "") . ' required/>Pieni kabinetti(15-20 hlö) <p><em>40e/h</em></p><br>';
        echo '<input type="radio" name="uusi_tila" value="Koko kahvila" ' . ($tilan_nimi == "Koko kahvila" ? "checked" : "") . ' required/>Koko kahvila(100-150 hlö) sis. pitopalvelun<p><em>125e/h</em></p><br>';
        // Valitse varauksen kesto
        echo 'Valitse varauksen kesto<br>';
        echo '<select class="form-control" name="uusi_varausaika" required>';
        echo '<option value="1 tunti" ' . ($varausaika == "1 tunti" ? "selected" : "") . '>1 tunti</option>';
        echo '<option value="2 tuntia" ' . ($varausaika == "2 tuntia" ? "selected" : "") . '>2 tuntia</option>';
        echo '<option value="3 tuntia" ' . ($varausaika == "3 tuntia" ? "selected" : "") . '>3 tuntia</option>';
        echo '<option value="4 tuntia" ' . ($varausaika == "4 tuntia" ? "selected" : "") . '>4 tuntia</option>';
        echo '<option value="5 tuntia" ' . ($varausaika == "5 tuntia" ? "selected" : "") . '>5 tuntia</option>';
        echo '<option value="6 tuntia" ' . ($varausaika == "6 tuntia" ? "selected" : "") . '>6 tuntia</option>';
        echo '</select><br><br>';

        echo 'Lisatiedot: <textarea name="uudet_lisatiedot">' . $lisatiedot . '</textarea><br><br>';
        echo '<input type="submit" value="Tallenna muutokset">';
        echo '</form>';

         // Lisätään poistanappi
         echo '<form method="post" action="">'; 
         echo '<input type="hidden" name="poistettava" value="' . $varaustunnus . '">';
         echo '<input type="submit" name="poista" value="Poista varaus">';
         echo '</form>';
     } else {
         echo "Virhe: Varaustunnusta ei löytynyt.";
     }
 }

    // Jos poista-nappia on painettu
    if (isset($_POST['poista'])) {
        $poistettava=$_POST['poistettava'];

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

    // Lähetetään viesti käyttäjälle
    if ($poisto_onnistui) {
        echo "Varauksen poistaminen onnistui!";
    } else {
        echo "Virhe! Varauksen poistaminen epäonnistui.";
    }
}

$yhteys->close();
?>

<!-- HTML-lomake varauksen tietojen hakemiseen -->
<!DOCTYPE html>
<html>
<head>
    <title>Varauksen muokkaus</title>
</head>
<body>
    <?php
    // Tarkista onko varaustunnus jo lähetetty
    if (!isset($_POST['varaustunnus'])) {
        // Lomake näkyy vain, jos varaustunnusta ei ole vielä lähetetty
        echo '<h2>Syötä varaustunnus varauksen tietojen muokkaamiseksi</h2>
            <form method="post" action="">
                Varaustunnus: <input type="text" name="varaustunnus" maxlength="5"><br><br>
                <input type="submit" value="Hae ja muokkaa varauksen tietoja">
            </form>';
    }
    ?>
</body>
</html>