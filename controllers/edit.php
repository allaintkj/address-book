<?php

/**
 * Edit contact entry using posted form data
 */
class Edit_Controller {
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
            $form = $_POST;
            $form['id'] = $req_body['contact_id'];

            $this->try_editing($form);
        }

        // get the proper contact from list model
        $contacts = new Contacts_Model();
        $edit_contact = $contacts->get_single_contact($req_body['contact_id']);

        // pass contact to view model to populate form
        $contact_form = new View_Model($this->template);
        $contact_form->assign('form_action', '?edit&contact_id=' . $req_body['contact_id'] . '&rep=' . $req_body['rep']);
        $contact_form->assign('contact', $edit_contact);

        // check for error message
        if (isset($_SESSION['msg_class']) && strpos($_SESSION['msg_class'], 'error') !== false) {
            // pass session form to view and nullify
            $contact_form->assign('contact', $_SESSION['form']);
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
     * Attempt to edit a contact using the provided form data
     *
     * @param  array $form POSTed form data
     */
    private function try_editing($form) {
        // nullify just in case
        $_SESSION['form'] = null;
        // new model, validate fields
        $new_contact = new Contact_Model();
        $contact_valid = $new_contact->validate_contact($form);

        if (!$contact_valid) {
            // validation failed, render filled form with validation msg
            $_SESSION['msg'] = 'Please fill out all the fields';
            $_SESSION['msg_class'] = 'bg-color-error';
            $_SESSION['form'] = $form;

            header('Location: ' . $_SERVER['PHP_SELF'] . '?edit&contact_id=' . $form['id'] . '&rep=' . $form['rep']);
            die();
        }

        // validation passed, try to update contact
        $contact_updated = $new_contact->edit_contact($contact_valid);

        if (!$contact_updated) {
            // db failed, render form with exception
            $_SESSION['msg'] = $new_contact->dberr;
            $_SESSION['msg_class'] = 'bg-color-error';
            $_SESSION['form'] = $form;

            header('Location: ' . $_SERVER['PHP_SELF'] . '?edit&contact_id=' . $form['id'] . '&rep=' . $form['rep']);
            die();
        }

        // if success render list with success message
        $_SESSION['msg'] = 'Contact updated successfully';
        $_SESSION['msg_class'] = 'bg-color-success';

        header('Location: ' . $_SERVER['PHP_SELF'] . '?contacts');
        die();
    }
}

?>
