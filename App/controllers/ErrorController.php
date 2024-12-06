<?php

namespace App\Controllers;

class ErrorController
{
    /**
     * Show the 404 not found page
     * @param string $message
     * @return void
     */
    public static function notFound($message = 'Page not found')
    {
        http_response_code(404);

        loadView('error', [
            'statusCode' => 404,
            'message' => $message
        ]);
    }

    /**
     * Show the 403 unauthorized page
     * @param string $message
     * @return void
     */
    public static function unauthorized($message = 'You are not authorized to view this page')
    {
        http_response_code(403);

        loadView('error', [
            'statusCode' => 403,
            'message' => $message
        ]);
    }
}
