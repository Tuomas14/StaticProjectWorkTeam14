<?php
include ("./connect.php");

// Tarkista yhteys
if ($yhteys->connect_error) {
    die("Yhteys epäonnistui: " . $yhteys->connect_error);
}

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

        // Voit lisätä tähän koodin varauksen tietojen muokkaamiseen
    } else {
        echo "Virhe: Varaustunnusta ei löytynyt.";
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
    <h2>Syötä varaustunnus varauksen tietojen muokkaamiseksi</h2>
    <form method="post" action="">
        Varaustunnus: <input type="text" name="varaustunnus"><br><br>
        <input type="submit" value="Hae ja muokkaa varauksen tietoja">
    </form>
</body>
</html>