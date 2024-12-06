<?php
$config = require basePath('config/db.php');

use Framework\Database;

// instantiate the database
$db = new Database($config);

$stmt = $db->query('SELECT * FROM listings LIMIT 6');
$listings = $stmt->fetchAll();



loadView('home', ['listings' => $listings]);
