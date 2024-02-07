<?php
include ("./connect.php");

$poistettava=isset($_GET["poistettava"]) ? $_GET["poistettava"] : "";

//Jos tieto on annettu, poistetaan tietokannasta
if (!empty($poistettava)){
    $sql="DELETE FROM henkilo WHERE id=?";
    $stmt=mysqli_prepare($yhteys, $sql);
    //Sijoitetaan muuttuja sql-lauseeseen
    mysqli_stmt_bind_param($stmt, 'i', $poistettava);
    //Suoritetaan sql-lause
    mysqli_stmt_execute($stmt);
    print "Varaus on poistettu onnistuneesti!";
} else {
    // Palautetaan virheviesti, jos varaustunnusta ei ole määritelty
    print "Virhe: Varaustunnusta ei ole määritelty.";
}

//Suljetaan tietokantayhteys
mysqli_close($yhteys);

//ja ohjataan pyyntö takaisin listaukseen
header("Location:../index.html");
exit;
?>