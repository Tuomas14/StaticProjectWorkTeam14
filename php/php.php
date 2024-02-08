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
        echo "etunimi: " . $row['etunimi'] . "<br>"; // Näytetään etunimi
        echo "sukunimi: " . $row['sukunimi'] . "<br>";
        echo "sahkoposti: " . $row['sahkoposti'] . "<br>";
        echo "puhelinnro: " . $row['puhelinnro'] . "<br>";

        // Lisää lomake muokkaamiseen
        echo '<form method="post" action="muokkaavarausta.php">';
        echo '<input type="hidden" name="varaustunnus" value="' . $varaustunnus . '">';
        echo 'Uusi etunimi: <input type="text" name="uusi_etunimi"><br>';
        echo 'Uusi sukunimi: <input type="text" name="uusi_sukunimi"><br>';
        echo 'Uusi sähköposti: <input type="text" name="uusi_sahkoposti"><br>';
        echo 'Uusi puhelinnumero: <input type="text" name="uusi_puhelinnumero"><br>';
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
        Varaustunnus: <input type="text" name="varaustunnus"><br><br>
        <input type="submit" value="Hae ja muokkaa varauksen tietoja">
    </form>
</body>
</html>