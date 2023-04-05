<?php
    // Check if config.php file already exists
    if (file_exists('config.php')) {
        die('The config.php file already exists!');
    }

    // Config file name
    $config_file = "config.php";

    // Check if form was submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        // Get form input values
        $title = $_POST["title"];
        $host = $_POST["host"];
        $database = $_POST["database"];
        $username = $_POST["username"];
        $password = $_POST["password"];
        $tablename = $_POST["tablename"];

        // Create a connection to the MySQL database
        $conn = mysqli_connect($host, $username, $password, $database);

        // Check connection
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        // Check if table name is empty or generate a default name
        if (empty($tablename)) {
            $tablename = "x345_urlresolver";
        }

        // Check if table name already exists
        $table_check = mysqli_query($conn, "SHOW TABLES LIKE '$tablename'");

        if(mysqli_num_rows($table_check) == 0) {
            // Table does not exist, create table
            $table_create = mysqli_query($conn, "CREATE TABLE `$tablename` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `urls` text NOT NULL,
              `resolved_url` varchar(255) DEFAULT NULL,
              `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

            if(!$table_create) {
                die("Table creation failed: " . mysqli_error($conn));
            }
        }

        // Write config file
        $config_content = "<?php\n";
        $config_content .= "\$config['title'] = '$title';\n";
        $config_content .= "\$config['host'] = '$host';\n";
        $config_content .= "\$config['database'] = '$database';\n";
        $config_content .= "\$config['username'] = '$username';\n";
        $config_content .= "\$config['password'] = '$password';\n";
        $config_content .= "\$config['tablename'] = '$tablename';\n";
        $config_content .= "?>";

        // Write config file to disk
        if (file_put_contents($config_file, $config_content) === false) {
            die("Config file creation failed");
        }

        // Close MySQL connection
        mysqli_close($conn);

        // Output success message
        echo "Config file was created successfully!";
        exit;
    }

    // Output HTML form
?>
<!DOCTYPE html>
<html>
<head>
  <title>Create Config File</title>
</head>
<body>
  <h1>Create Config File</h1>
  <form method="post">
    <label>Title:</label>
    <input type="text" name="title" required><br><br>
    <label>Host:</label>
    <input type="text" name="host" required><br><br>
    <label>Database:</label>
    <input type="text" name="database" required><br><br>
    <label>Username:</label>
    <input type="text" name="username" required><br><br>
    <label>Password:</label>
    <input type="password" name="password" required><br><br>
    <label>tablename:</label>
    <input type="text" name="tablename"><br><br>
    <input type="submit" value="Config-Datei erstellen">
  </form>
</body>
</html>