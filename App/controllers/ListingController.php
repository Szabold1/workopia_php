<?php

namespace App\Controllers;

use Framework\Database;

class ListingController
{
    private $db;

    public function __construct()
    {
        $config = require basePath('config/db.php');
        $this->db = new Database($config);
    }

    /**
     * Show all listings
     * @return void
     */
    public function index()
    {
        $stmt = $this->db->query('SELECT * FROM listings');
        $listings = $stmt->fetchAll();

        loadView('/listings/index', ['listings' => $listings]);
    }

    /**
     * Show create listing form
     * @return void
     */
    public function create()
    {
        loadView('listings/create');
    }

    /**
     * Show a listing
     * @return void
     */
    public function show()
    {
        $id = $_GET['id'] ?? '';

        $params = ['id' => $id];
        $stmt = $this->db->query("SELECT * FROM listings WHERE id = :id", $params);
        $listing = $stmt->fetch();

        loadView('listings/show', ['listing' => $listing]);
    }
}
