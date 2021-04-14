<?php

/**
 * Add contact entry using posted form data
 */
class Add_Controller {
    public $template = 'contact_form';

    /**
     * Called by router
     *
     * @param  array  $req_body GET/POST variables received by index.php
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
            // form submitted
            $this->try_adding($_POST);
        }

        // construct empty form model
        $contact_form = new View_Model($this->template);
        $contact_form->assign('form_action', '?add');
        $contact_form->assign('contact', array());

        // check for error message
        if (isset($_SESSION['msg_class']) && strpos($_SESSION['msg_class'], 'error') !== false) {
            // pull form from session and give it to the model
            $contact_form->assign('contact', $_SESSION['form']);
            // clear form from session
            $_SESSION['form'] = null;
        }
    }

    /**
     * De-authenticate and send the user back to login form
     *
     * @param  string $msg       validation message to display
     * @param  string $msg_class css class to apply to validation message display
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
     * Attempt to add a contact using the provided form data
     *
     * @param  array $form POSTed form data
     */
    private function try_adding($form) {
        // nullify session form, just in case
        $_SESSION['form'] = null;
        // build new model and validate the form
        $new_contact = new Contact_Model();
        $contact_valid = $new_contact->validate_contact($form);

        if (!$contact_valid) {
            // validation failed, render filled form with validation msg
            $_SESSION['msg'] = $new_contact->validation_err;
            $_SESSION['msg_class'] = 'bg-color-error';
            $_SESSION['form'] = $form;

            header('Location: ' . $_SERVER['PHP_SELF'] . '?add&rep=' . $_SESSION['user_id']);
            die();
        }

        // validation passed, try to add contact
        $contact_added = $new_contact->add_contact($contact_valid);

        if (!$contact_added) {
            // db failed, render form with exception
            $_SESSION['msg'] = $new_contact->dberr;
            $_SESSION['msg_class'] = 'bg-color-error';
            $_SESSION['form'] = $form;

            header('Location: ' . $_SERVER['PHP_SELF'] . '?add&rep=' . $_SESSION['user_id']);
            die();
        }

        // if success render list with success
        $_SESSION['msg'] = 'Contact added successfully';
        $_SESSION['msg_class'] = 'bg-color-success';

        header('Location: ' . $_SERVER['PHP_SELF'] . '?contacts');
        die();
    }
}

?>
