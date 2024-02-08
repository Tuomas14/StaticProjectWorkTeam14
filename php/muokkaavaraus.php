<?php
include ("./connect.php");

// Tehdään funktio varauksen poistamiseen
function poistaVaraus($yhteys, $poistettava) {
    $sql="DELETE FROM varaukset WHERE id=?";
    $stmt=mysqli_prepare($yhteys, $sql);
    // Sijoitetaan muuttuja SQL-lauseeseen
    mysqli_stmt_bind_param($stmt,"i", $poistettava);
    // Suoritetaan SQL-lause
    mysqli_stmt_execute($stmt);
    if(mysqli_stmt_affected_rows($stmt)> 0) {
    print "Varaus on poistettu onnistuneesti!";
    } else {
        print "Virhe: Varausta ei voitu poistaa.";
}
}

$muokattava=isset($_GET["muokattava"]) ? $_GET["muokattava"] : "";
$poistettava=isset($_GET["poistettava"]) ? $_GET["poistettava"] : "";

//Jos tietoa ei ole annettu, palataan listaukseen
if (empty($muokattava)){
    header("Location:../pages/tietuettaeiloydy.html");
    exit;
}

$sql="select * from ASIAKAS where asiakasID=?";
$stmt=mysqli_prepare($yhteys, $sql);
//Sijoitetaan muuttuja sql-lauseeseen
mysqli_stmt_bind_param($stmt, 'i', $muokattava);
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

<?php

// Jos tieto on annettu, suoritetaan poisto tietokannasta
if (!empty($poistettava)) {
    // Kutsutaan funktiota varauksen poistamiseen
    poistaVaraus($yhteys, $poistettava);    
} else {
    //Jos varaustunnusta ei ole määritelty, palautetaan virheviesti
    print "Virhe: Varaustunnusta ei ole määritelty.";
}

mysqli_close($yhteys);

// Ohjataan takaisin index.html listaukseen
header("Location:../index.html");
exit;
?>