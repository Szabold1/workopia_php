<?php

/**
 * Get the base path
 * @param string $path
 * @return string
 */
function basePath($path = '')
{
    return __DIR__ . '/' . $path;
}

/**
 * Load a view
 * @param string $name
 * @param array $data
 * @return void
 */
function loadView($name, $data = [])
{
    $path = basePath("App/views/{$name}.view.php");

    if (file_exists($path)) {
        extract($data);
        require $path;
    } else {
        echo "View not found: {$name}";
    }
}

/**
 * Load a partial
 * @param string $name
 * @return void
 */
function loadPartial($name)
{
    $path = basePath("App/views/partials/{$name}.php");

    if (file_exists($path)) {
        require $path;
    } else {
        echo "Partial not found: {$name}";
    }
}

/**
 * Inspect a value(s)
 * @return mixed $value
 * @return void
 */
function inspect($value)
{
    echo '<pre>';
    var_dump($value);
    echo '</pre>';
}

/**
 * Inspect a value(s) and die
 * @return mixed $value
 * @return void
 */
function inspectAndDie($value)
{
    echo '<pre>';
    var_dump($value);
    echo '</pre>';
    die();
}

/**
 * Format salary as currency
 * @param string $salary
 * @return string formatted salary
 */
function formatSalary($salary)
{
    return '$' . number_format(floatval($salary), 0);
}

/**
 * Sanitize data
 * @param string $dirty
 * @return string
 */
function sanitize($dirty)
{
    return filter_var(trim($dirty), FILTER_SANITIZE_SPECIAL_CHARS);
}

/**
 * Redirect to a given url
 * @param string $url
 * @return void
 */
function redirect($url)
{
    header("Location: {$url}");
    exit();
}
