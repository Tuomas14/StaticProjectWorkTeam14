<?php
echo '<form method="post" action="">';
echo 'input type="hidden" name="poistettava" value="' . $varaustunnus . '">';
echo '<input type="submit" name="poista" value="Poista varaus">';
echo </form>;

echo </form>;
} else {
    echo "Virhe: Varaustunnusta ei löytynyt";
}
}

// Jos poista-nappia on painettu
if (isset($_POST['poista'])) {
    $poistettava=$_POST['$poistettava'];
}

// Poista varaus tietokannasta
$sql="DELETE FROM varaukset WHERE varaustunnus=?";
$stmt=$yhteys->prepare($sql);
$stmt->bind_param("s",$poistettava);

if ($stmt->execute()) {
    echo "Varaus on poistettu onnistuneesti!";
} else {
    echo "Virhe: Varausta ei voitu poistaa.";
}
}

$yhteys->close();


?>

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
    <!-- Lisätään poistanappi -->
    <form method="post" action="">
        <input type="hidden" name="poistettava" value="<?php echo isset($_POST['varaustunnus']) ? $_POST['varaustunnus'] : ''; ?>">
        <input type="submit" name="poista" value="Poista varaus">
    </form>
</body>
</html>