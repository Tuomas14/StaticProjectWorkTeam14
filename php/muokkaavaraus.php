<?php
include ("./connect.php");

$muokattava=isset($_GET["varaustunnus"]) ? $_GET["varaustunnus"] : "";

//Jos tietoa ei ole annettu, palataan listaukseen
if (empty($muokattava)){
    header("Location:../pages/tietuettaeiloydy.html");
    exit;
}

$sql="select * from ASIAKAS where varaustunnus=?";
$stmt=mysqli_prepare($yhteys, $sql);
//Sijoitetaan muuttuja sql-lauseeseen
mysqli_stmt_bind_param($stmt, 's', $muokattava);
//Suoritetaan sql-lause
mysqli_stmt_execute($stmt);
//Koska luetaan prepared statementilla, tulos haetaan 
//metodilla mysqli_stmt_get_result($stmt);
$tulos=mysqli_stmt_get_result($stmt);
if (!$rivi=mysqli_fetch_object($tulos)){
    header("Location:../pages/tietuettaeiloydy.html");
    exit;
}
?>

<!-- Lomake tavallisena html-koodina php tagien ulkopuolella -->
<!-- Lomake sisältää php-osuuksia, joilla tulostetaan syötekenttiin luetun tietueen tiedot -->
<!-- id-kenttä on readonly, koska sitä ei ole tarkoitus muuttaa -->

<form action='./paivita.php' method='post'>
id:<input type='text' name='id' value='<?php print $rivi->id;?>' readonly><br>
etunimi:<input type='text' name='etunimi' value='<?php print $rivi->etunimi;?>'><br>
sukunimi:<input type='text' name='sukunimi' value='<?php print $rivi->sukunimi;?>'><br>
<input type='submit' name='ok' value='ok'><br>
</form>

<!-- loppuun uusi php-osuus -->
<?php
//Suljetaan tietokantayhteys
mysqli_close($yhteys);
?>