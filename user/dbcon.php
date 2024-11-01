<?php
try {
    $server = "mysql:host=localhost;dbname=terryessence";
    $user = "root";
    $password = "";
    
    // Create a PDO instance
    $pdo = new PDO($server, $user, $password);
    
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Optional: Display a connection success message
    // echo "Connected successfully"; 

} catch (PDOException $e) {
    // Display and log the error message
    echo "Connection failed: " . $e->getMessage();
    error_log($e->getMessage()); // Logs the error to the server's error log
}
?>
