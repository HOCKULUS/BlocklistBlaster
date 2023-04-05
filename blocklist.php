<?php
// Load configuration file
require_once 'config.php';

// Connect to database
$conn = mysqli_connect($config['host'], $config['username'], $config['password'], $config['database']);

// Check connection
if (!$conn) {
    die("Verbindung fehlgeschlagen: " . mysqli_connect_error());
}

// Get the 'id' parameter from the URL and sanitize it
$ID = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRING);
// Check if 'id' parameter is set and not empty
if(isset($ID) && $ID != ""){
    // 'id' parameter is passed, get contents of URLs
    $id = $_GET['id'];
    $query = "SELECT urls FROM `".$config['tablename']."` WHERE resolved_url='".$id."'";
    $result = mysqli_query($conn, $query);
	// Check if only one entry is found
    if (mysqli_num_rows($result) == 1) {
        // Entry found, get URLs
        $row = mysqli_fetch_assoc($result);
        $urls = explode(',', $row['urls']);
        $content = '';
        $unique_lines = array();
        foreach ($urls as $url) {
			// Trim leading/trailing whitespace from URL
            $url = trim($url);
            if (!empty($url)) {
				// Get content of URL
                $blocklist = file_get_contents($url);
                if ($blocklist !== false) {
                    $blocklist_lines = explode("\n", $blocklist);
                    foreach ($blocklist_lines as $line) {
                        $line = trim($line);
                        if (!empty($line) && !isset($unique_lines[$line])) {
                            // Add line to content if it's not a duplicate
                            $content .= $line . "\n";
                            $unique_lines[$line] = true;
                        }
                    }
                }
            }
        }
        // Output content as plain text
        header('Content-Type: text/plain');
        echo $content;
    }
    else {
        // Entry not found, output error message
		header('Content-Type: text/plain');
        echo "# ID not found!";
    }
}
else {
    // 'id' parameter not set, output error message
	header('Content-Type: text/plain');
    echo "# ID not specified!";
}

?>
