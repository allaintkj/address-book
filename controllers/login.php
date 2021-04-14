<?php

// hope to break this out into a user model

/**
 * Takes care of authentication, de-authentication, and redirection
 * based on authentication status
 */
class Login_Controller {
    public $form_template = 'login';

    /**
     * This is the default function that will be called by router.php
     *
     * @param array $req_body GET/POST variables received by index.php
     */
    public function main(array $req_body) {
        // check if we've been directed here from logout button
        if (isset($req_body['logout']) && $req_body['logout'] === 'true') {
            if (isset($_SESSION['authenticated']) && $_SESSION['authenticated'] === 'true') {
                $_SESSION['authenticated'] = '';
                $_SESSION['user_id'] = '';
                $_SESSION['msg'] = 'You have been logged out';
                $_SESSION['msg_class'] = 'bg-color-secondary';

                header('Location: ' . $_SERVER['PHP_SELF']);
                die();
            }
        }

        // check if already authenticated
        if (isset($_SESSION['authenticated']) && $_SESSION['authenticated'] === 'true') {
            $_SESSION['msg'] = '';
            $_SESSION['msg_class'] = '';

            header('Location: ' . $_SERVER['PHP_SELF'] . '?contacts');
            die();
        }

        // if $_POST values exist, form has been submitted
        // attempt to log the user in
        if (isset($req_body['username']) && isset($req_body['password'])) {
            $this->try_login($req_body['username'], $req_body['password']);
        }

        // no submission, render the empty login form
        $login_form = new View_Model($this->form_template);
    }

    /**
     * Attempt to authenticate with provided credentials
     *
     * @param string $username Username entered in login form
     * @param string $password Password entered in login form
     */
    public function try_login($username, $password) {
        $home_loc = 'Location: ' . $_SERVER['PHP_SELF'];
        // status var to minimize copy/pasting redirects
        $return = false;
        $username = htmlspecialchars($username);

        // check for empty fields
        if (empty($username) || empty($password)) {
            $return = true;
            $msg = 'Please fill out both fields';
            $msg_class = 'bg-color-error';

            header($home_loc);
            die();
        }

        // for determining the hash to enter in table
        // var_dump($password);
        // var_dump(password_hash($password, PASSWORD_BCRYPT));
        // die();

        // input passed validation, setup connection
        $pdo = new PDO(DB_CONN, DB_USER, DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // prepare statement
        $sql = 'SELECT id, username, password FROM sales_reps where username = :username';
        $stmt = $pdo->prepare($sql);

        // execute prepared statement
        $stmt->execute(['username' => $username]);
        $user = $stmt -> fetch();
        $stored_hash = $user['password'];

        if (!$user) {
            $return = true;
            $msg = 'That user does not exist';
            $msg_class = 'bg-color-error';
        }

        if (!password_verify($password, $stored_hash) && !$return) {
            $return = true;
            $msg = 'That password is incorrect';
            $msg_class = 'bg-color-error';
        }

        if ($return) {
            // either no user or incorrect password
            // redirect to form with message
            $_SESSION['msg'] = $msg;
            $_SESSION['msg_class'] = $msg_class;

            header($home_loc);
            die();
        }

        // at this point auth must have succeeded
        // set session var
        $_SESSION['authenticated'] = 'true';
        $_SESSION['msg'] = 'Welcome';
        $_SESSION['msg_class'] = 'bg-color-secondary';
        $_SESSION['user_id'] = $user['id'];
        // destroy pdo to make sure connection dies
        // php ends the connection when the script dies, but
        // this is safer for multiple requests in a script
        $pdo = null;

        // redirect to contacts controller, now authed
        header($home_loc . '?contacts');
        die();
    }
}

?>
