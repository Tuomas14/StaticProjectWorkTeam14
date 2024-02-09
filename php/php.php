<?php
include ("./connect.php");
include ("../pages/muokkaavarausta.html");

// Tarkista yhteys
if ($yhteys->connect_error) {
    die("Yhteys epäonnistui: " . $yhteys->connect_error);
}

// Tarkista, onko lomakkeen tiedot lähetetty
if(isset($_POST['varaustunnus']) && !empty($_POST['varaustunnus'])) {
    $varaustunnus = $_POST['varaustunnus'];
    
    // Hae varauksen tiedot tietokannasta
    $sql = "SELECT * FROM ASIAKAS WHERE varaustunnus = '$varaustunnus'";
    $result = $yhteys->query($sql);

    if ($result->num_rows > 0) {
        // Tulosta varauksen tiedot ja mahdollista muokkaaminen
        $row = $result->fetch_assoc();
        $etunimi = $row['etunimi'];
        $sukunimi = $row['sukunimi'];
        $sahkoposti = $row['sahkoposti'];
        $puhelinnumero = $row['puhelinnro'];

        echo '<form method="post" action="muokkaa.php">';
        echo '<input type="hidden" name="varaustunnus" value="' . $varaustunnus . '">';
        echo 'Uusi etunimi: <input type="text" name="uusi_etunimi" value="' . $etunimi . '"><br>';
        echo 'Uusi sukunimi: <input type="text" name="uusi_sukunimi" value="' . $sukunimi . '"><br>';
        echo 'Uusi sähköposti: <input type="text" name="uusi_sahkoposti" value="' . $sahkoposti . '"><br>';
        echo 'Uusi puhelinnumero: <input type="text" name="uusi_puhelinnumero" value="' . $puhelinnumero . '"><br>';
        echo '<input type="submit" value="Tallenna muutokset">';
        echo '</form>';
   
    // Lisätään poistanappi
    echo '<form method="post" action="">'; 
    echo '<input type="hidden" name="poistettava" value="' . $varaustunnus . '">';
    echo '<input type="submit" name="poista" value="Poista varaus">';
    echo '</form>';
} else {
    echo "Virhe: Varaustunnusta ei löytynyt.";

}  }       // Jos poista-nappia on painettu
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
