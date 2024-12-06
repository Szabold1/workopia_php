<?php
$config = require basePath('config/db.php');

use Framework\Database;

// instantiate the database
$db = new Database($config);

$stmt = $db->query('SELECT * FROM listings');
$listings = $stmt->fetchAll();

loadView('/listings/index', ['listings' => $listings]);
