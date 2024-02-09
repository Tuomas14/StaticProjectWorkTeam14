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