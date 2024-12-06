<?php
$config = require basePath('config/db.php');

use Framework\Database;

$db = new Database($config);

$id = $_GET['id'] ?? '';

$params = ['id' => $id];
$stmt = $db->query("SELECT * FROM listings WHERE id = :id", $params);
$listing = $stmt->fetch();

loadView('listings/show', ['listing' => $listing]);
