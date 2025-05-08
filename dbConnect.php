<?php

// Database details
$dbHost = '127.0.0.1';
$dbName = 'test_db';
$dbUser = 'root';
$dbPassword = '';

// Data source name
$dsn = 'mysql:dbname=' . $dbName . ';host=' . $dbHost;

// PDO Object
$pdo = new PDO($dsn, $dbUser, $dbPassword);
