<?php

namespace App\Controllers;

use Framework\Database;
use Framework\Validation;
use Framework\Session;

class UserController
{
    private $db;

    public function __construct()
    {
        $config = require basePath('config/db.php');
        $this->db = new Database($config);
    }

    /**
     * Show register page
     * @return void
     */
    public function create()
    {
        loadView('users/create');
    }

    /**
     * Show login page
     * @return void
     */
    public function login()
    {
        loadView('users/login');
    }

    /**
     * Store (create/register) a new user
     * @return void
     */
    public function store()
    {
        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $city = $_POST['city'] ?? '';
        $state = $_POST['state'] ?? '';
        $password = $_POST['password'] ?? '';
        $passwordConfirmation = $_POST['password_confirmation'] ?? '';

        $errors = [];

        if (!Validation::string($name, 2, 50)) {
            $errors['name'] = 'Name must be between 2 and 50 characters';
        }

        if (!Validation::email($email)) {
            $errors['email'] = 'Invalid email address';
        }

        if (!Validation::string($password, 6, 50)) {
            $errors['password'] = 'Password must be at least 6 characters';
        }

        if (!Validation::match($password, $passwordConfirmation)) {
            $errors['password_confirmation'] = 'Passwords do not match';
        }

        if ($errors) {
            loadView('users/create', [
                'errors' => $errors,
                'user' => [
                    'name' => $name,
                    'email' => $email,
                    'city' => $city,
                    'state' => $state
                ]
            ]);
            exit;
        }

        // check if email already exists
        $user = $this->db->query('SELECT * FROM users WHERE email = :email', ['email' => $email])->fetch();
        if ($user) {
            $errors['email'] = 'Email already exists';
            loadView('users/create', [
                'errors' => $errors,
                'user' => [
                    'name' => $name,
                    'email' => $email,
                    'city' => $city,
                    'state' => $state
                ]
            ]);
            exit;
        }

        // create user
        $queryParams = [
            'name' => $name,
            'email' => $email,
            'city' => $city,
            'state' => $state,
            'password' => password_hash($password, PASSWORD_DEFAULT)
        ];
        $keys = implode(', ', array_keys($queryParams));
        $values = ':' . implode(', :', array_keys($queryParams));

        $this->db->query("INSERT INTO users ({$keys}) VALUES ({$values})", $queryParams);

        // get new user id and set user in session
        $userId = $this->db->connection->lastInsertId();
        Session::set('user', [
            'id' => $userId,
            'name' => $name,
            'email' => $email,
            'city' => $city,
            'state' => $state
        ]);

        redirect('/');
    }

    /**
     * Authenticate (login) a user
     * @return void
     */
    public function authenticate()
    {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        $errors = [];
        // validate email and password
        if (!Validation::email($email)) {
            $errors['email'] = 'Invalid email address';
        }
        if (!Validation::string($password, 6, 50)) {
            $errors['password'] = 'Password must be at least 6 characters';
        }

        if ($errors) {
            loadView('users/login', [
                'errors' => $errors,
                'email' => $email
            ]);
            exit;
        }

        // check if email exists and if password matches
        $queryParams = ['email' => $email];
        $user = $this->db->query('SELECT * FROM users WHERE email = :email', $queryParams)->fetch();

        if (!$user || !password_verify($password, $user->password)) {
            $errors['email'] = 'Incorrect email or password';
            loadView('users/login', [
                'errors' => $errors,
                'email' => $email
            ]);
            exit;
        }

        // set user in session (login)
        Session::set('user', [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'city' => $user->city,
            'state' => $user->state
        ]);

        redirect('/');
    }

    /**
     * Logout a user, delete session and reset session cookie
     * @return void
     */
    public function logout()
    {
        Session::destroy();

        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);

        redirect('/');
    }
}
