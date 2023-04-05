<?php
// Lade Konfigurationsdatei
require_once 'config.php';

// Verbinde mit Datenbank
$conn = mysqli_connect($config['host'], $config['username'], $config['password'], $config['database']);

// Prüfe Verbindung
if (!$conn) {
    die("Verbindung fehlgeschlagen: " . mysqli_connect_error());
}

// Funktion zum Generieren einer eindeutigen ID
function generateRandomString($length = 10){
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

// Prüfe, ob ID-Parameter übergeben wurde
$ID = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRING);
if(isset($ID) && $ID != ""){
    // ID-Parameter wurde übergeben
    // Suche nach ID in Datenbank
    $query = "SELECT * FROM `".$config['tablename']."` WHERE resolved_url = '".$ID."'";
    $result = mysqli_query($conn, $query);
    if(mysqli_num_rows($result) > 0){
        // Eintrag mit ID existiert bereits in der Datenbank
        $row = mysqli_fetch_assoc($result);
        $urls = $row['urls'];
    }
	else {
        // Eintrag mit ID existiert noch nicht in der Datenbank, erzeuge neuen Eintrag
        $resolved_url = $ID;
        $urls = "";
        $query = "INSERT INTO `".$config['tablename']."` (resolved_url, urls) VALUES ('".$resolved_url."', '".$urls."')";
        mysqli_query($conn, $query);
    }
}
else {
    // ID-Parameter wurde nicht übergeben, erzeuge neuen Eintrag
    $resolved_url = generateRandomString();

    // Suche nach vorhandenen Datensätzen mit der generierten resolved_url
    $query = "SELECT * FROM `".$config['tablename']."` WHERE resolved_url = '".$resolved_url."'";
    $result = mysqli_query($conn, $query);

    // Wenn die generierte resolved_url bereits vorhanden ist, suche nach einer neuen ID
    while (mysqli_num_rows($result) > 0) {
        $resolved_url = generateRandomString();
        $query = "SELECT * FROM `".$config['tablename']."` WHERE resolved_url = '".$resolved_url."'";
        $result = mysqli_query($conn, $query);
    }

    $urls = "";
    $query = "INSERT INTO `".$config['tablename']."` (resolved_url, urls) VALUES ('".$resolved_url."', '".$urls."')";
	mysqli_query($conn, $query);
    $ID = $resolved_url;
    header("Location: ?id=".$ID);
}


// Überprüfe, ob das Formular abgesendet wurde
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Formular wurde abgesendet
    $urls = $_POST['urls'];
    
    // Aktualisiere Eintrag in der Datenbank
    $query = "UPDATE `".$config['tablename']."` SET urls = '".$urls."' WHERE resolved_url = '".$ID."'";
    mysqli_query($conn, $query);
}

// Generiere HTML-Formular
?>
<!DOCTYPE html>
<html>
<head>
    <title>URL-Eingabe</title>
</head>
<body>
    <form method="post">
        <label for="urls">URLs (separated by , ):</label><br>
        <textarea id="urls" name="urls"><?php echo $urls; ?></textarea><br><br>
        <input type="submit" value="save">
		<?php if ($_SERVER['REQUEST_METHOD'] == 'POST') {echo '<input type="button" value="view" onclick="window.open(\'/blocklist.php?id='.$ID.'\')">
';} ?>
    </form>
</body>
</html>
<?php
// Schließe Verbindung zur Datenbank
mysqli_close($conn);
?>
