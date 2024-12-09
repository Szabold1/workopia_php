<?php

namespace App\Controllers;

use Framework\Database;
use Framework\Validation;

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

    /**
     * Store a listing
     * @return void
     */
    public function store()
    {
        $allowedFields = ['title', 'description', 'salary', 'requirements', 'benefits', 'tags', 'company', 'address', 'city', 'state', 'phone', 'email'];

        // check only allowed fields are submitted and sanitize data
        $newListingData = array_intersect_key($_POST, array_flip($allowedFields));
        $newListingData = array_map('sanitize', $newListingData);

        // add user_id to new listing data
        $newListingData['user_id'] = 1;

        // check for required fields
        $requiredFields = ['title', 'description', 'salary', 'city', 'state', 'email'];
        $errors = [];
        foreach ($requiredFields as $field) {
            $currentField = $newListingData[$field];
            if (empty($currentField) || !Validation::string($currentField)) {
                $errors[$field] = ucfirst($field) . ' is required';
            }
        }

        // if there was an error, reload the form with the error message(s)
        // otherwise, save the listing to the database
        if (!empty($errors)) {
            loadView('listings/create', [
                'errors' => $errors,
                'listing' => $newListingData
            ]);
        } else {
            $fields = [];
            $paramNames = [];

            foreach ($newListingData as $field => $value) {
                $fields[] = $field;
                if (empty($value)) {
                    $newListingData[$field] = null;
                }
                $paramNames[] = ":{$field}";
            }

            $fields = implode(', ', $fields);
            $paramNames = implode(', ', $paramNames);

            $sql = "INSERT INTO listings ({$fields}) VALUES ({$paramNames})";
            $queryParams = $newListingData;
            $this->db->query($sql, $queryParams);

            redirect('/listings');
        }
    }
}
