<?php

namespace Framework\Middleware;

use Framework\Session;

class Authorize
{
    /**
     * Check if user is authorized (logged in)
     * @return bool
     */
    public function isAuthorized()
    {
        return Session::has('user');
    }

    /**
     * Handle the user's request
     * @param $role
     * @return void
     */
    public function handle($role)
    {
        // user is logged in and trying to access a guest page (e.g. login page)
        if ($this->isAuthorized() && $role === 'guest') {
            return redirect('/');
        }
        // user is not logged in and trying to access an auth (user) page (e.g. create post page)
        if (!$this->isAuthorized() && $role === 'auth') {
            return redirect('/auth/login');
        }
    }
}
