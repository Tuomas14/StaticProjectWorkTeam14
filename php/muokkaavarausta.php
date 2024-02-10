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
        echo "Etunimi: " . $row['etunimi'] . "<br>"; // Näytetään etunimi
        echo "Sukunimi: " . $row['sukunimi'] . "<br>";
        echo "Sahkoposti: " . $row['sahkoposti'] . "<br>";
        echo "Puhelinnro: " . $row['puhelinnro'] . "<br>";
        $etunimi = $row['etunimi'];
        $sukunimi = $row['sukunimi'];
        $sahkoposti = $row['sahkoposti'];
        $puhelinnumero = $row['puhelinnro'];

        echo '<form method="post" action="paivita.php">';
        echo '<input type="hidden" name="varaustunnus" value="' . $varaustunnus . '">';
        echo 'Uusi etunimi: <input type="text" name="uusi_etunimi" value="' . $etunimi . '"><br>';
        echo 'Uusi sukunimi: <input type="text" name="uusi_sukunimi" value="' . $sukunimi . '"><br>';
        echo 'Uusi sähköposti: <input type="text" name="uusi_sahkoposti" value="' . $sahkoposti . '"><br>';
        echo 'Uusi puhelinnumero: <input type="text" name="uusi_puhelinnumero" value="' . $puhelinnumero . '"><br>';
        echo '<input type="submit" value="Tallenna muutokset">';
        echo '</form>';

        // Lisätään poistanappi
        echo '<form method="post" action="poistavaraus.php">'; 
        echo '<input type="hidden" name="poistettava" value="' . $varaustunnus . '">';
        echo '<input type="submit" name="poista" value="Poista varaus">';
        echo '</form>';
    }}
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
