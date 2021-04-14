<?php

/**
 * Model containing - and responsible for fetching - the list of contacts
 */
class Contacts_Model {
    private $contacts = array();

    public function __construct() {
        // fetch all contacts from DB immediately
        $this->get_all_contacts();
    }

    /**
     * Fetches all content from contacts table
     */
    private function get_all_contacts() {
        $pdo = new PDO(DB_CONN, DB_USER, DB_PASS);
        $sql = 'SELECT * FROM contacts';
        $stmt = $pdo->prepare($sql);

        $stmt->execute();
        $contacts = $stmt -> fetchAll();

        foreach ($contacts as $contact) {
            array_push($this->contacts, $contact);
        }
    }

    /**
     * Pluck contacts from list where the sales rep is current user
     *
     * @param  int $id DB id of current user
     */
    public function get_reps_contacts($id, $birthday = false) {
        $this_reps_contacts = array();

        foreach ($this->contacts as $contact) {
            if ($birthday) {
                // compare dates, push contact if this month is their birthday
                $this_month = date('n', time());
                $contact_bday_month = date('n', strtotime($contact['birthday']));

                if ($contact['rep'] === $id && $contact_bday_month === $this_month) {
                    array_push($this_reps_contacts, $contact);
                }
            } else if ($contact['rep'] === $id) {
                // birthday flag disabled, push all contacts for this rep
                array_push($this_reps_contacts, $contact);
            }
        }

        return $this_reps_contacts;
    }

    /**
     * Get a single contact from list using provided id
     *
     * @param  int $id target contact's id
     */
    public function get_single_contact($id) {
        foreach ($this->contacts as $contact) {
            if ($contact['id'] === $id) {
                return $contact;
            }
        }

        return false;
    }
}

?>
