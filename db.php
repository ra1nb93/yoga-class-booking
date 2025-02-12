<?php
// db.php

$host = 'monorail.proxy.rlwy.net';
$port = '28114';   // Se serve, altrimenti puoi lasciare 3306 se in dash è riportato diversamente
$dbname = 'railway';
$username = 'root';
$password = 'SWmEOopHFfJeJkrDqRsXGvsPhxFMjIST';

try {
    // Nota l'aggiunta di `port=$port` se la porta non è la 3306
    $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8";
    $pdo = new PDO($dsn, $username, $password);

    // Imposta il PDO in modalità eccezione
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
