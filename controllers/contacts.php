<?php

/**
 * Serves up table of contacts for the logged-in sales rep.
 */
class Contacts_Controller {
    public $template = 'contacts';

    /**
     * Called by router.
     * Display table of contacts, filtered by
     * birthdays occurring this month, if so desired
     * by the user
     *
     * @param array $req_body GET/POST variables received by index.php
     */
    public function main(array $req_body) {
        // check auth status
        if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== 'true') {
            $_SESSION['authenticated'] = '';
            $_SESSION['user_id'] = '';
            $_SESSION['msg'] = 'You are not authorized to access that page';
            $_SESSION['msg_class'] = 'bg-color-warning';

            // back to login
            header('Location: ' . $_SERVER['PHP_SELF']);
            die();
        }

        // logged in; get rep's id from session and construct models
        $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';
        $contacts_model = new Contacts_Model();
        $contact_list = new View_Model($this->template);

        if (!empty($user_id)) {
            // var to filter contacts by birthday
            $birthday = false;

            if (isset($req_body['monthly']) && $req_body['monthly'] === 'true') {
                $birthday = true;
            }

            // get contacts via model for rep with user_id
            // assign to view so we can display in the table
            $this_reps_contacts = $contacts_model->get_reps_contacts($user_id, $birthday);
            $contact_list->assign('this_reps_contacts', $this_reps_contacts);
        }
    }
}

?>
