<?php

namespace App\Controllers;

use Framework\Database;

class HomeController
{
    private $db;

    public function __construct()
    {
        $config = require basePath('config/db.php');
        $this->db = new Database($config);
    }

    /**
     * Show latest listings
     * @return void
     */
    public function index()
    {
        $stmt = $this->db->query('SELECT * FROM listings LIMIT 6');
        $listings = $stmt->fetchAll();

        loadView('home', ['listings' => $listings]);
    }
}
