<?php

/**
 * Remove entry from contact table based on the id passed
 */
class Delete_Controller {
    public $template = 'contact_form';

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

        // attempt deletion
        if (isset($req_body) && !empty($req_body)) {
            $this->try_delete($req_body['contact_id']);
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
     * Attempt to delete contact entry based on provided id
     *
     * @param  int $id contact's corresponding database id
     */
    private function try_delete($id) {
        $delete_contact = new Contact_Model();
        $contact_deleted = $delete_contact->delete_contact($id);

        if (!$contact_deleted) {
            // failed, throw back exception
            $_SESSION['msg'] = $delete_contact->dberr;
            $_SESSION['msg_class'] = 'bg-color-error';

            header('Location: ' . $_SERVER['PHP_SELF'] . '?edit&contact_id=' . $form['id'] . '&rep=' . $form['rep']);
            die();
        }

        // removed, send back to contacts list
        $_SESSION['msg'] = 'Contact deleted successfully';
        $_SESSION['msg_class'] = 'bg-color-success';

        header('Location: ' . $_SERVER['PHP_SELF'] . '?contacts');
        die();
    }
}

?>
