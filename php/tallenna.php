<?php
include ("./connect.php");

    // Luon muuttujan varaustunnus joka uuden varauksen tehdessä generoi satunnaisen luvun 0-99999 väliltä
    // str_pad funktio varmistaa että jokainen sarake saa jonkun numeron esim. 00456
    // näin ollen varastunnus on aina vähintään 5 merkkiä pitkä
    $varaustunnus = str_pad(rand(0, 99999), 5, '0', STR_PAD_LEFT);

    // Luetaan uusivaraus.html lomakkeelta saadut tiedot ja tehdään niistä muuttujat
    $varauspvm=isset($_POST["varauspvm"]) ? $_POST["varauspvm"] : "";
    $etunimi=isset($_POST["etunimi"]) ? $_POST["etunimi"] : "";
    $sukunimi=isset($_POST["sukunimi"]) ? $_POST["sukunimi"] : "";
    $sahkoposti=isset($_POST["sahkoposti"]) ? $_POST["sahkoposti"] : "";
    $puhelinnro=isset($_POST["puhelinnro"]) ? $_POST["puhelinnro"] : "";
    $tilan_nimi = isset($_POST["tilan_nimi"]) ? $_POST["tilan_nimi"] : "";
    $varausaika = isset($_POST["varausaika"]) ? $_POST["varausaika"] : "";
    $lisatiedot = isset($_POST["lisatiedot"]) ? $_POST["lisatiedot"] : "";

    // Tarkistetaan, onko kyseiselle päivämäärälle ja tilalle jo olemassa varaus
    // Tässä tapauksessa koodi tarkistaa onko päivämäärä ja valittu tilan_nimi samat, ettei tule päällekkäisyyksiä varausjärjestelmässä
    // Jos tarkistus koskisi pelkkää päivämäärää, ei varauksia onnistuisi kuin yksi päivässä
    $sql_tarkista_pvm = "SELECT * FROM VARAUKSET WHERE varauspvm = ? AND EXISTS (SELECT * FROM TILA WHERE tilan_nimi = ?)";
    $stmt_tarkista_pvm = mysqli_prepare($yhteys, $sql_tarkista_pvm);
    mysqli_stmt_bind_param($stmt_tarkista_pvm, 'ss', $varauspvm, $tilan_nimi);
    mysqli_stmt_execute($stmt_tarkista_pvm);
    $result_tarkista_pvm = mysqli_stmt_get_result($stmt_tarkista_pvm);


    // Jos päällekkäinen varaus löytyy, ohjataan käyttäjä sivustolle joka antaa virheilmoituksen jo olemassaolevasta varauksesta.
    if(mysqli_num_rows($result_tarkista_pvm) > 0) {
        header("Location: ../pages/varausjoolemassa.html");
        exit;
    }

    // Tarkistetaan etunimi-kenttä että se ei jää tyhjäksi tai sisällä pelkkiä välilyöntejä
    // Jos tarkistus ei mene läpi ohjataan käyttäjä tietuettaloydy.html lomakkeelle
    if (empty(trim($etunimi))) {
        header("Location:../pages/tietuettaeiloydy.html");
        exit;
    }

    // Sama tarkistus suoritetaan myös sukunimelle
    // Jos tarkistus ei mene läpi ohjataan käyttäjä tietuettaloydy.html lomakkeelle
    if (empty(trim($sukunimi))) {
        header("Location:../pages/tietuettaeiloydy.html");
        exit;
    }

    // Tarkistetaan sähköposti-kenttä että sitä ei täytetä välilyönneillä tai jätetä tyhjäksi
    // Tehdään myös tarkistus PHP funktiolla "filter_var" että annettu osoite on validi sähköpostimuotoinen osoite
    // Jos tarkistus ei mene läpi ohjataan käyttäjä tietuettaloydy.html lomakkeelle
    if (empty(trim($sahkoposti)) || !filter_var($sahkoposti, FILTER_VALIDATE_EMAIL)) {
        header("Location:../pages/tietuettaeiloydy.html");
        exit;
    }

    // Tarkistetaan puhelinnumero-kenttä että sitä ei ole jätetty tyhjäksi, tai täytetty välilyönneillä.
    // Tehdään myös tarkistus PHP funktiolla "preg_match" vastaako annettu puhelinnumero regex-mallia joka edustaa yleistä puhelinnumeron muotoa
    // Jos tarkistus ei mene läpi ohjataan käyttäjä tietuettaloydy.html lomakkeelle
    if (empty(trim($puhelinnro)) || !preg_match("/^\+?\d{1,3}[\s.-]?\(?\d{1,3}\)?[\s.-]?\d{3,4}[\s.-]?\d{4}$/", $puhelinnro)) {
        header("Location:../pages/tietuettaeiloydy.html");
        exit;
    }

    // Tarkistetaan, onko jokin jäljellä olevista kentistä tyhjä
    // Jos tarkistus ei mene läpi ohjataan käyttäjä tietuettaloydy.html lomakkeelle
    if (empty($varauspvm) || empty($tilan_nimi) || empty($varausaika)) {
        header("Location:../pages/tietuettaeiloydy.html");
        exit;
    }

    // Lisätään saadut henkilötiedot tietokantaan
    // Henkilötiedot koskevat taulua ASIAKAS
    // Tallennetaan myös alussa luotu varaustunnus tauluun
    $sql_asiakas = "insert into ASIAKAS (etunimi, sukunimi, sahkoposti, varaustunnus, puhelinnro) VALUES (?, ?, ?, ?, ?)";
    $stmt_asiakas = mysqli_prepare($yhteys, $sql_asiakas);
    mysqli_stmt_bind_param($stmt_asiakas, 'sssss', $etunimi, $sukunimi, $sahkoposti, $varaustunnus, $puhelinnro);
    mysqli_stmt_execute($stmt_asiakas);


    // Kun ASIAKAS tauluun on tallennettu ylläolevat henkilötiedot, se saa tietokantaan oman asiakasID:n
    // Tässä haemme saadun ID:n että voimme lisätä sen muihin tauluihin viiteavaimena
    $asiakasID = mysqli_insert_id($yhteys);

    // Lisätään varaustiedot tietokantaan
    // Varaustiedot koskevat taulua VARAUKSET
    // Tallennetaan myös alussa luotu varaustunnus tauluun
    // ja myös yllä saatu asiakasID
    $sql_varaus = "INSERT INTO VARAUKSET (varauspvm, varaustunnus, lisatiedot, varausaika, asiakasID) VALUES (?, ?, ?, ?, ?)";
    $stmt_varaus = mysqli_prepare($yhteys, $sql_varaus);
    mysqli_stmt_bind_param($stmt_varaus, 'sssii', $varauspvm, $varaustunnus, $lisatiedot, $varausaika, $asiakasID);
    mysqli_stmt_execute($stmt_varaus);

    // Lisätään tilatiedot tietokantaan
    // Tilatiedot koskevat taulua TILA
    // Tallennetaan myös alussa luotu varaustunnus tauluun
    // ja myös yllä saatu asiakasID
    $sql_tila = "insert into TILA (tilan_nimi, varaustunnus) VALUES (?, ?)";
    $stmt_tila = mysqli_prepare($yhteys, $sql_tila);
    mysqli_stmt_bind_param($stmt_tila, 'ss', $tilan_nimi, $varaustunnus);
    mysqli_stmt_execute($stmt_tila);

    // Suljetaan tietokantayhteys
    mysqli_close($yhteys);

    // PHP-osuus päättyy
?>


<!-- Jos ylläolevat ehdot täyttyvät, eikä virheitä tule, ohjataan käyttäjä tähän HTML-sivustolle
Sivu antaa saadun varaustunnuksen ja kertoo varauksen onnistuneen -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kahvila Fore - Varaus onnistui</title>
    <link rel="stylesheet" href="../css/general.css">
    <link rel="stylesheet" href="../css/styles.aapo.css">
    <link rel="stylesheet" href="../css/styles.jani.css">
    <link rel="stylesheet" href="../css/styles-tuomas.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
     <!-- Bootstrap linkki -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</head>
<body>
    <div class="paateksti">
        <h2><em>Varaus onnistui!</em></h2>
        <p>Varaustunnuksesi on: <?php echo $varaustunnus; ?></p>
        <p><a href="../index.html">Etusivulle</a></p>
    </div>
</body>
</html>