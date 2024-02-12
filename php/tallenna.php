<?php
include ("./connect.php");

    $varaustunnus = str_pad(rand(0, 99999), 5, '0', STR_PAD_LEFT);

    $varauspvm=isset($_POST["varauspvm"]) ? $_POST["varauspvm"] : "";
    $etunimi=isset($_POST["etunimi"]) ? $_POST["etunimi"] : "";
    $sukunimi=isset($_POST["sukunimi"]) ? $_POST["sukunimi"] : "";
    $sahkoposti=isset($_POST["sahkoposti"]) ? $_POST["sahkoposti"] : "";
    $puhelinnro=isset($_POST["puhelinnro"]) ? $_POST["puhelinnro"] : "";
    $tilan_nimi = isset($_POST["tilan_nimi"]) ? $_POST["tilan_nimi"] : "";
    $varausaika = isset($_POST["varausaika"]) ? $_POST["varausaika"] : "";
    $lisatiedot = isset($_POST["lisatiedot"]) ? $_POST["lisatiedot"] : "";


    // Tarkistetaan, onko kyseiselle päivämäärälle ja tilalle jo olemassa varaus
    $sql_check_booking = "SELECT * FROM VARAUKSET WHERE varauspvm = ? AND EXISTS (SELECT * FROM TILA WHERE tilan_nimi = ?)";
    $stmt_check_booking = mysqli_prepare($yhteys, $sql_check_booking);
    mysqli_stmt_bind_param($stmt_check_booking, 'ss', $varauspvm, $tilan_nimi);
    mysqli_stmt_execute($stmt_check_booking);
    $result_check_booking = mysqli_stmt_get_result($stmt_check_booking);

    if(mysqli_num_rows($result_check_booking) > 0) {
        // Päällekkäinen varaus löytyi, ohjataan käyttäjä takaisin varauslomakkeeseen
        header("Location: ../pages/varausjoolemassa.html");
        exit;
    }



    if (empty($varauspvm) || empty($etunimi) || empty($sukunimi)|| empty($sahkoposti)|| empty($puhelinnro)|| empty($tilan_nimi)|| empty($varausaika)){
        header("Location:../pages/tietuettaeiloydy.html");
        exit;
    }

    // Lisätään henkilötiedot tietokantaan
    $sql_asiakas = "insert into ASIAKAS (etunimi, sukunimi, sahkoposti, varaustunnus, puhelinnro) VALUES (?, ?, ?, ?, ?)";
    $stmt_asiakas = mysqli_prepare($yhteys, $sql_asiakas);
    mysqli_stmt_bind_param($stmt_asiakas, 'sssss', $etunimi, $sukunimi, $sahkoposti, $varaustunnus, $puhelinnro);
    mysqli_stmt_execute($stmt_asiakas);

    $asiakasID = mysqli_insert_id($yhteys);

    // Lisätään varaustiedot tietokantaan
    $sql_varaus = "INSERT INTO VARAUKSET (varauspvm, varaustunnus, lisatiedot, varausaika, asiakasID) VALUES (?, ?, ?, ?, ?)";
    $stmt_varaus = mysqli_prepare($yhteys, $sql_varaus);
    mysqli_stmt_bind_param($stmt_varaus, 'sssii', $varauspvm, $varaustunnus, $lisatiedot, $varausaika, $asiakasID);
    mysqli_stmt_execute($stmt_varaus);

    // Lisätään tilatiedot tietokantaan
    $sql_tila = "insert into TILA (tilan_nimi, varaustunnus) VALUES (?, ?)";
    $stmt_tila = mysqli_prepare($yhteys, $sql_tila);
    mysqli_stmt_bind_param($stmt_tila, 'ss', $tilan_nimi, $varaustunnus);
    mysqli_stmt_execute($stmt_tila);

    // Suljetaan tietokantayhteys
    mysqli_close($yhteys);
?>
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