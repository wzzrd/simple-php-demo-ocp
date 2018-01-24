<html>
<title>Simple database connection demo in OpenShift</title>
<body>
<?php

$servername = getenv(DB_HOST);
$username = getenv(DB_USER);
$password = getenv(DB_PASSWORD);
$dbname = getenv(DB_NAME);

if ($_ENV["DEBUG"] == "yes") {
    echo "Servername: $servername <br/>";
    echo "Username:   $username <br/>";
    echo "Password:   $password <br/>";
    echo "Database:   $dbname <br/>";
    echo "<br/>";
}

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error() + "<br/>");
}
echo "Connected successfully...<br/>";

if ($_ENV["DEBUG"] == "yes") {
    if (mysqli_query($conn, "DROP TABLE names") == TRUE) {
        echo "Dropped table...<br/>";
    } else {
        echo "Error dropping table...<br/>";
        die(mysqli_errno($conn) . ": " . mysqli_error($conn). "<br/>");
    }
}

/* Create table doesn't return a resultset */
if (mysqli_query($conn, "CREATE TABLE IF NOT EXISTS names (id INT(3) UNSIGNED AUTO_INCREMENT PRIMARY KEY, name VARCHAR(30) NOT NULL)") == TRUE) {
    echo "Created table...<br/>";
} else {
    echo "Error creating table...<br/>";
    die(mysqli_errno($conn) . ": " . mysqli_error($conn). "<br/>");
}

/* Seed some data */ 
if (mysqli_query($conn, "INSERT INTO names (name) VALUES ('Sterling'), ('Cheryl'), ('Lana'), ('Cyril'), ('Pam'), ('Malory'), ('Other Barry')") == TRUE) {
    echo "Pushed data into table...<br/>";
} else { 
    echo "Unable to push data into table...</br>";
    die(mysqli_errno($conn) . ": " . mysqli_error($conn). "<br/>");
}

/* Pull names back out */
if ($result = mysqli_query($conn, "SELECT * FROM names")) {
    echo "Fetched data from table...<br/>";
} else {
    echo "Unable to fetch data from table...<br/>";
    die(mysqli_errno($conn) . ": " . mysqli_error($conn). "<br/>");
}

echo "Printing results...<br/><pre>";
while($row = mysqli_fetch_array($result)) {
    print_r($row);
}
echo "</pre>";

?>

</body>
</html>
