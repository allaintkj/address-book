<?php

/**
 * Generate email from form data and send to all of this rep's contacts
 */
class Email_Controller {
    public $template = 'email_form';

    /**
     * Called by router.
     *
     * @param array $req_body GET/POST variables received by index.php
     */
    public function main(array $req_body) {
        // check authentication
        if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== 'true' || !isset($_SESSION['user_id'])) {
            $this->redirect_to_login('You are not authorized to access that page', 'bg-color-warning');
        }

        // authenticated, confirm it's the correct user
        if (isset($_SESSION['user_id']) && isset($req_body['rep'])) {
            if ($_SESSION['user_id'] !== $req_body['rep']) {
                $this->redirect_to_login('You are not authorized to access that page', 'bg-color-warning');
            }
        }

        if (isset($_POST) && !empty($_POST)) {
            $this->send_email($_POST);
        }

        // show mail form
        $email_view = new View_Model($this->template);

        // check for error message
        if (isset($_SESSION['msg_class']) && strpos($_SESSION['msg_class'], 'error') !== false) {
            // pull form from session and give it to the model
            $email_view->assign('email', $_SESSION['form']);
            // clear form from session
            $_SESSION['form'] = null;
        }
    }

    /**
     * Clear session variables and send the user back to login
     * @param  string $msg       validation message to display
     * @param  string $msg_class class applied to validation message
     */
    private function redirect_to_login($msg, $msg_class) {
        $_SESSION['authenticated'] = '';
        $_SESSION['user_id'] = '';
        $_SESSION['msg'] = $msg;
        $_SESSION['msg_class'] = $msg_class;

        header('Location: ' . $_SERVER['PHP_SELF']);
        die();
    }

    /**
     * Fetch contacts for this rep and send email to all
     *
     * @param  array $form form containing email data
     */
    private function send_email($form) {
        $email_addresses = array();

        if (!isset($form['subject']) || !isset($form['body']) || !isset($form['rep'])) {
            $_SESSION['msg'] = 'Please fill out all the fields';
            $_SESSION['msg_class'] = 'bg-color-error';
            $_SESSION['form'] = $form;

            header('Location: ' . $_SERVER['PHP_SELF'] . '?email&rep=' . $_SESSION['user_id']);
            die();
        }

        // get contact for current rep
        $contacts_model = new Contacts_Model();
        $this_reps_contacts = $contacts_model->get_reps_contacts($_SESSION['user_id']);

        // loop contacts and pull emails
        foreach ($this_reps_contacts as $contact) {
            array_push($email_addresses, $contact['email']);
        }

        // sanitize subject and message
        $email_subject = filter_var($form['subject'], FILTER_SANITIZE_STRING);
        $email_message = filter_var($form['body'], FILTER_SANITIZE_STRING);

        // attach SMTP server here

        $_SESSION['msg'] = 'Email sent to ' . sizeof($this_reps_contacts) . ' contacts';
        $_SESSION['msg_class'] = 'bg-color-success';

        header('Location: ' . $_SERVER['PHP_SELF'] . '?contacts');
        die();
    }
}

?>
