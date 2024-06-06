<?php

class SessionManager
{

    public function logout():bool
    {
        session_start();

        if (isset($_SESSION['user_id'])) {
            // destroy session
            session_destroy();
            // Unset all session variables
            $_SESSION = array();
            // If session cookies are used for session storage
            if (ini_set('session.use_cookies', '0') === TRUE) {
                // Delete the session cookie
                $params = session_get_cookie_params();
                setcookie(
                    session_name(),
                    '',
                    time() - 42000,
                    $params['path'],
                    $params['domain'],
                    $params['secure'],
                    $params['httponly']
                );
            }
        }

        return true;
    }
}




