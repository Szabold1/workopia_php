<?php

namespace App\Controllers;

use Framework\Database;
use Framework\Validation;

class ListingController
{
    private $db;
    private $allowedFields = ['title', 'description', 'salary', 'requirements', 'benefits', 'tags', 'company', 'address', 'city', 'state', 'phone', 'email'];
    private $requiredFields = ['title', 'description', 'salary', 'city', 'state', 'email'];


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
        // check only allowed fields are submitted and sanitize data
        $newListingData = array_intersect_key($_POST, array_flip($this->allowedFields));
        $newListingData = array_map('sanitize', $newListingData);

        // add user_id to new listing data
        $newListingData['user_id'] = 1;

        // check for required fields
        $errors = [];
        foreach ($this->requiredFields as $field) {
            $currentValue = $newListingData[$field];
            if (empty($currentValue) || !Validation::string($currentValue)) {
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

    /**
     * Summary of destroy
     * @param array $params
     * @return void
     */
    public function destroy($params = [])
    {
        $id = $params['id'] ?? '';

        $queryParams = ['id' => $id];

        // check if listing exists
        $listing = $this->db->query("SELECT * FROM listings WHERE id = :id", $queryParams)->fetch();

        if (!$listing) {
            ErrorController::notFound('Listing not found');
            return;
        }

        $this->db->query("DELETE FROM listings WHERE id = :id", $queryParams);

        $_SESSION['success_message'] = 'Listing deleted successfully';

        redirect('/listings');
    }

    /**
     * Show edit listing form
     * @param array $params
     * @return void
     */
    public function edit($params = [])
    {
        $id = $params['id'] ?? '';

        $queryParams = ['id' => $id];
        $stmt = $this->db->query("SELECT * FROM listings WHERE id = :id", $queryParams);
        $listing = $stmt->fetch();

        if (!$listing) {
            ErrorController::notFound('Listing not found');
            return;
        }

        loadView('listings/edit', ['listing' => $listing]);
    }

    /**
     * Update a listing
     * @param array $params
     * @return void
     */
    public function update($params = [])
    {
        $id = $params['id'] ?? '';

        $queryParams = ['id' => $id];
        $listing = $this->db->query("SELECT * FROM listings WHERE id = :id", $queryParams)->fetch();

        if (!$listing) {
            ErrorController::notFound('Listing not found');
            return;
        }

        // check only allowed fields are submitted and sanitize data
        $updatedData = array_intersect_key($_POST, array_flip($this->allowedFields));
        $updatedData = array_map('sanitize', $updatedData);

        // check for required fields
        $errors = [];
        foreach ($this->requiredFields as $field) {
            $currentValue = $updatedData[$field];
            if (empty($currentValue) || !Validation::string($currentValue)) {
                $errors[$field] = ucfirst($field) . ' is required';
            }
        }

        // if there was an error, reload the form with the error message(s)
        // otherwise, save the listing to the database
        if (!empty($errors)) {
            loadView('listings/edit', [
                'errors' => $errors,
                'listing' => $listing
            ]);
        } else {
            $fieldsToUpdate = [];

            foreach ($updatedData as $field => $value) {
                $fieldsToUpdate[] = "{$field} = :{$field}";
                if (empty($value)) {
                    $updatedData[$field] = null;
                }
            }

            $updatedData['id'] = $id;

            $fieldsToUpdate = implode(', ', $fieldsToUpdate);

            $sql = "UPDATE listings SET {$fieldsToUpdate} WHERE id = :id";
            $this->db->query($sql, $updatedData);

            $_SESSION['success_message'] = 'Listing updated successfully';

            redirect("/listings/{$id}");
        }
    }
}
