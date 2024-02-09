<?php
include ("./connect.php");
include ("../pages/muokkaavarausta.html");

// Tarkista yhteys
if ($yhteys->connect_error) {
    die("Yhteys epäonnistui: " . $yhteys->connect_error);
}

// Tarkista onko käyttäjä lähettänyt varaustunnuksen lomakkeella
if(isset($_POST['varaustunnus'])) {
    $varaustunnus = $_POST['varaustunnus'];
    
  // Tarkista onko käyttäjä lähettänyt varaustunnuksen lomakkeella
if(isset($_POST['varaustunnus'])) {
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
        

        // Lisää lomake muokkaamiseen
        echo '<form method="post" action="muokkaavarausta.php">';
        echo 'Varaustunnus: <input type="text" name="varaustunnus" readonly value=" ' . $varaustunnus . '"><br><br>';
        echo 'Uusi etunimi: <input type="text" name="uusi_etunimi" value="' . $etunimi . '"><br><br>';
        echo 'Uusi sukunimi: <input type="text" name="uusi_sukunimi" value="' . $sukunimi . '"><br><br>';
        echo 'Uusi sähköposti: <input type="text" name="uusi_sahkoposti" value="' . $sahkoposti . '"><br><br>';
        echo 'Uusi puhelinnumero: <input type="text" name="uusi_puhelinnumero" value="' . $puhelinnumero . '"><br><br>';
        echo '<input type="submit" value="Tallenna muutokset">';
        echo '</form>';
    } else {
        echo "Virhe: Varaustunnusta ei löytynyt.";
    }
}}

$yhteys->close();
?>

<!-- HTML-lomake varauksen tietojen hakemiseen -->
<!DOCTYPE html>
<html>
<head>
    <title>Varauksen muokkaus</title>
</head>
<body>
    <h2>Syötä varaustunnus varauksen tietojen muokkaamiseksi</h2>
    <form method="post" action="">
        Varaustunnus: <input type="text" name="varaustunnus" maxlength="5"><br><br>
        <input type="submit" value="Hae ja muokkaa varauksen tietoja">
    </form>
</body>
</html>