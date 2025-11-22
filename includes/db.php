<?php
$servername = "localhost";
$username = "u931839000_stosrho";
$password = "AOZht4Opug"; 
$database = "u931839000_stosrho";
$port = 3309;  

// Create connection
$conn = new mysqli($servername, $username, $password, $database, $port);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
