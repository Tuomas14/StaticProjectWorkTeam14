<?php
include ("./connect.php");
//Luetaan lomakkeelta tulleet tiedot funktiolla $_POST
//jos syötteet ovat olemassa
$asiakasID=isset($_POST["asiakasID"]) ? $_POST["asiakasID"] : "";
$etunimi=isset($_POST["etunimi"]) ? $_POST["etunimi"] : "";
$sukunimi=isset($_POST["sukunimi"]) ? $_POST["sukunimi"] : "";
$sahkoposti=isset($_POST["sahkoposti"]) ? $_POST["sahkoposti"] : "";
$puhelinnro=isset($_POST["puhelinnro"]) ? $_POST["puhelinnro"] : "";

//Jos ei jompaa kumpaa tai kumpaakaan tietoa ole annettu
//ohjataan pyyntö takaisin lomakkeelle
if (empty($etunimi) || empty($sukunimi) || empty($asiakasID)|| empty($sahkoposti) || empty($puhelinnro)){
    header("Location:../html/tietuettaeiloydy.html");
    exit;
}
//Tehdään sql-lause, jossa kysymysmerkeillä osoitetaan paikat
//joihin laitetaan muuttujien arvoja
$sql="update ASIAKAS set etunimi=?, sukunimi=?, sahkoposti=?, puhelinnro=? where asiakasID=?";
//Valmistellaan sql-lause
$stmt=mysqli_prepare($yhteys, $sql);
//Sijoitetaan muuttujat oikeisiin paikkoihin
mysqli_stmt_bind_param($stmt, 'sssii', $etunimi, $sukunimi, $sahkoposti, $puhelinnro, $asiakasID);
//Suoritetaan sql-lause
mysqli_stmt_execute($stmt);

$tilaID=isset($_POST["tilaID"]) ? $_POST["tilaID"] : "";
$tilan_nimi=isset($_POST["tilan_nimi"]) ? $_POST["tilan_nimi"] : "";

if (empty($tilan_nimi) ||empty($tilaID)){
    header("Location:../html/tietuettaeiloydy.html");
    exit;
}

$sql="update TILA set tilan_nimi=?, where tilaID=?";
$stmt=mysqli_prepare($yhteys, $sql);
mysqli_stmt_bind_param($stmt, 'si', $tilan_nimi, $tilaID);
mysqli_stmt_execute($stmt);

$varaustunnus=isset($_POST["varaustunnus"]) ? $_POST["varaustunnus"] : "";
$varauspvm=isset($_POST["varauspvm"]) ? $_POST["varauspvm"] : "";
$varausaika=isset($_POST["varausaika"]) ? $_POST["varausaika"] : "";

if (empty($varauspvm) ||empty($varausaika) ||empty($varaustunnus)){
    header("Location:../html/tietuettaeiloydy.html");
    exit;
}

$sql="update VARAUKSET set varauspvm=?, varausaika=?, where varaustunnus=?";
$stmt=mysqli_prepare($yhteys, $sql);
mysqli_stmt_bind_param($stmt, 'ssi', $varauspvm, $varausaika, $varaustunnus);
mysqli_stmt_execute($stmt);

//Suljetaan tietokantayhteys
mysqli_close($yhteys);

//header("Location:./henkilolista.php");
?>