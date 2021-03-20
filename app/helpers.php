<?php
require_once 'db_config.php';

if (!function_exists('old')) {

    /**
     * Returns last input value of field
     * 
     * @param string $field_name The field name
     * @return string The input's last value or empty string 
     */
    function old($field_name)
    {
        return isset($_REQUEST[$field_name]) ? $_REQUEST[$field_name] : '';
    }
}


if (!function_exists('csrf')) {

    /**
     * Generate random string for csrf security
     */
    function csrf()
    {
        $token = sha1(rand(1, 100000)) . '$$' . rand(1, 1000) . 'icar';
        $_SESSION['csrf_token'] = $token;

        // // with cookies
        // setcookie('csrf_token', $token, time() + 60 * 60, '/', "", false, true);
        return $token;
    }
}

if (!function_exists('validate_csrf')) {

    function validate_csrf()
    {
        if (isset($_REQUEST['csrf_token']) && isset($_SESSION['csrf_token'])) {
            return $_SESSION['csrf_token'] === $_REQUEST['csrf_token'];
        }

        return false;

        // // with cookies 
        // if (isset($_COOKIES['csrf_token']) && isset($_SESSION['csrf_token']) &&  $_SESSION['csrf_token'] === $_REQUEST['csrf_token']) {
        //     csrf();
        //     return true;
        // }

        // csrf();
        // return false;
    }
}

if (!function_exists('email_exists')) {

    function email_exists($link, $email)
    {
        $email = mysqli_real_escape_string($link, $email);
        $sql = "SELECT email FROM users WHERE email = '$email' LIMIT 1";
        $result = mysqli_query($link, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            return true;
        }

        return false;
    }
}


if (!function_exists('user_auth')) {

    function user_auth()
    {
        if (
            isset($_SESSION['user_id']) &&
            isset($_SESSION['user_ip']) &&
            $_SESSION['user_ip'] === $_SERVER['REMOTE_ADDR'] &&
            isset($_SESSION['user_agent']) &&
            $_SESSION['user_agent'] === $_SERVER['HTTP_USER_AGENT']
        ) {
            return true;
        }

        return false;
    }
}


if (!function_exists('login_user')) {
    function login_user($id, $name, $location)
    {
        $_SESSION['user_id'] = $id;
        $_SESSION['user_name']  = $name;
        $_SESSION['user_ip'] = $_SERVER['REMOTE_ADDR'];
        $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];


        if (isset($location)) {
            header("location: $location");
            exit();
        }
    }
}
