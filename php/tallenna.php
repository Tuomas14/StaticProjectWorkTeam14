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


    if (empty($varauspvm) || empty($etunimi) || empty($sukunimi)|| empty($sahkoposti)|| empty($puhelinnro)|| empty($tilan_nimi)|| empty($varausaika)){
        header("Location:../pages/yhteysvirhe.html");
        exit;
    }

    // Lisätään henkilötiedot tietokantaan
    $sql_asiakas = "insert into ASIAKAS (etunimi, sukunimi, sahkoposti, varaustunnus, puhelinnro) VALUES (?, ?, ?, ?, ?)";
    $stmt_asiakas = mysqli_prepare($yhteys, $sql_asiakas);
    mysqli_stmt_bind_param($stmt_asiakas, 'ssssi', $etunimi, $sukunimi, $sahkoposti, $varaustunnus, $puhelinnro);
    mysqli_stmt_execute($stmt_asiakas);

    // Lisätään varaustiedot tietokantaan
    $sql_varaus = "insert into VARAUKSET (varauspvm, varaustunnus, varausaika) VALUES (?, ?, ?)";
    $stmt_varaus = mysqli_prepare($yhteys, $sql_varaus);
    mysqli_stmt_bind_param($stmt_varaus, 'ssi', $varauspvm, $varaustunnus, $varausaika);
    mysqli_stmt_execute($stmt_varaus);

    // Lisätään tilatiedot tietokantaan
    $sql_tila = "insert into TILA (tilan_nimi, varaustunnus) VALUES (?, ?)";
    $stmt_tila = mysqli_prepare($yhteys, $sql_tila);
    mysqli_stmt_bind_param($stmt_tila, 'ss', $tilan_nimi, $varaustunnus);
    mysqli_stmt_execute($stmt_tila);


    // Näytetään varaustunnus käyttäjälle
    echo "Varaustunnuksesi on: " . $varaustunnus;

    // Suljetaan tietokantayhteys
    mysqli_close($yhteys);
?>
