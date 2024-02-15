<!DOCTYPE html>
<html>
<head>
    <title>Varauksen muokkaus</title>
    <link rel="stylesheet" href="../css/general.css">
    <link rel="stylesheet" href="../css/styles.aapo.css">
    <link rel="stylesheet" href="../css/styles.jani.css">
    <link rel="stylesheet" href="../css/styles-tuomas.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
     <!-- Bootstrap linkki -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</head>
<body>
<nav class="varaus">
        <h1> Hallinnoi varausta | Kahvila FORE </h1> 
</nav>
<?php
include ("./connect.php");

if(isset($_POST['varaustunnus']) && !empty($_POST['varaustunnus'])) {
    // Valmistellaan kysely
    $varaustunnus = $_POST['varaustunnus'];
    $sql = "SELECT * FROM ASIAKAS WHERE varaustunnus = ?";
    // Valmistellaan lauseke
    $stmt = $yhteys->prepare($sql);
    // Tarkistetaan, että valmistelu onnistui ennen suoritusta
    if ($stmt) {
        // Liitetään parametri ja suoritetaan kysely
        $stmt->bind_param('i', $varaustunnus);
        $stmt->execute();
        // Haetaan tulos
        $result = $stmt->get_result();
        // Tarkistetaan onko rivejä
        if ($result->num_rows > 0) {
            // Tulosta varauksen tiedot ja mahdollista muokkaaminen
            $row = $result->fetch_assoc();
        echo '<div class="paateksti">';
        echo "<em><u>Varauksen tiedot</u></em>" . "<br>";
        // hakee tietokannasta halutun tiedon joka sijaitsee $row assosiatiivisessa taulukossa
        echo "Varaustunnuksesi: " . $row['varaustunnus'] . "<br>";
        echo "Etunimi: " . $row['etunimi'] . "<br>";
        echo "Sukunimi: " . $row['sukunimi'] . "<br>";
        echo "Sahkoposti: " . $row['sahkoposti'] . "<br>";
        echo "Puhelinnro: " . $row['puhelinnro'] . "<br><br>";
        // tallennetaan arvot uusiksi muuttujiksi jotta niitä voidaan käyttää myöhemmin
        $etunimi = $row['etunimi'];
        $sukunimi = $row['sukunimi'];
        $sahkoposti = $row['sahkoposti'];
        $puhelinnumero = $row['puhelinnro'];

        // Tässä luodaan formi jossa käyttäjä voi muokata tietoja
        // Käyttäjälle näytetään varaustunnus, mutta se on vain lukemiseen
        // Lomake lähetetään POST-tyyppinä paivita.php tiedostolle, joten käyttäjän syöttämät tiedot eivät näy suoraan URL:ssä
        echo "<em><u>Muokkaa tietojasi</u></em>". "<br>";
        echo '<form method="post" action="paivita.php">';
        echo '<input type="hidden" name="varaustunnus" value="' . $varaustunnus . '">';
        echo 'Uusi etunimi: <input type="text" name="uusi_etunimi" value="' . $etunimi . '"><br><br>';
        echo 'Uusi sukunimi: <input type="text" name="uusi_sukunimi" value="' . $sukunimi . '"><br><br>';
        echo 'Uusi sähköposti: <input type="text" name="uusi_sahkoposti" value="' . $sahkoposti . '"><br><br>';
        echo 'Uusi puhelinnumero: <input type="text" name="uusi_puhelinnumero" value="' . $puhelinnumero . '"><br><br>';
        echo '<input type="submit" value="Tallenna muutokset">'."<br><br>";
        echo '</form>';

        // Lisätään poistanappi
        echo '<form method="post" action="poistavaraus.php">'; 
        echo '<input type="hidden" name="poistettava" value="' . $varaustunnus . '">';
        // Lisätään JavaScript-funktio varmistusikkunan näyttämiseksi
        echo '<input type="submit" name="poista" value="Poista varaus" onclick="return confirmDelete()">';
        echo '</form>' . "<br>";
        echo "<em><u>Jos haluat muokata tarkempia tietojasi ota yhteyttä!</u></em>". "<br>";
        echo "Fore@kahvila.fi "."<br><br>";
        echo '</div>';
        // Lisätään JavaScript-funktio varmistusikkunan näyttämiseksi
        echo '<script>';
        echo 'function confirmDelete() {';
        echo 'return confirm("Haluatko varmasti poistaa varauksen?")';
        echo '}';
        echo '</script>';
    } else {
        echo '<div class="paateksti">';
        echo "VIRHE!"."<br><br>";
        echo "Varaustunnusta ei löytynyt. "."<br><br>";
        echo "<a href='../php/muokkaavarausta.php'>TAKAISIN</a>";
        echo '</div>';
        }
    }
    // Suljetaan valmisteltu lauseke
    $stmt->close();
} 

        // Tarkista onko varaustunnus jo lähetetty
        if (!isset($_POST['varaustunnus'])) {
            // Lomake näkyy vain, jos varaustunnusta ei ole vielä lähetetty
            echo '<div class="paateksti">';
            echo '<h2>Syötä varaustunnus</h2><br>
                <form method="post" action="">
                    Varaustunnus: <input type="text" name="varaustunnus" maxlength="5"><br><br>
                    <input type="submit" value="Hae ja muokkaa varauksen tietoja">
                </form>';
            echo '</div>';
        }
    ?>
</body>
