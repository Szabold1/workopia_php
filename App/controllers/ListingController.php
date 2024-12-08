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
     * @param array $params
     * @return void
     */
    public function show($params = [])
    {
        $id = $params['id'] ?? '';

        $queryParams = ['id' => $id];
        $stmt = $this->db->query("SELECT * FROM listings WHERE id = :id", $queryParams);
        $listing = $stmt->fetch();

        if (!$listing) {
            ErrorController::notFound('Listing not found');
            return;
        }

        loadView('listings/show', ['listing' => $listing]);
    }
}
