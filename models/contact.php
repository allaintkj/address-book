<?php

/**
 * Methods for adding, editing, and deleting individual contacts
 */
class Contact_Model {
    public $dberr = '';
    public $validation_err = '';

    public function __construct() {
    }

    /**
     * DB operation for attempting to add a contact
     *
     * @param array $form POSTed form data containing contact info
     */
    public function add_contact($form) {
        $this->dberr = '';

        try {
            // setup db connection
            $pdo = new PDO(DB_CONN, DB_USER, DB_PASS);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // prepare statement
            $sql = 'INSERT INTO contacts (fname, surname, phone, email, address, city, province, postal, birthday, rep) ' .
                'VALUES '.
                '(:fname, :surname, :phone, :email, :address, :city, :province, :postal, :birthday, :rep)';
            $stmt = $pdo->prepare($sql);

            // add
            $stmt->execute($form);
            $pdo = null;

            return true;
        } catch (PDOException $exception) {
            $this->dberr = $exception;
            $pdo = null;

            return false;
        }
    }

    /**
     * DB operation for attempting to delete a contact
     *
     * @param  int $id database id of the chosen contact
     */
    public function delete_contact($id) {
        $this->dberr = '';

        try {
            // setup db connection
            $pdo = new PDO(DB_CONN, DB_USER, DB_PASS);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // prepare statement
            $sql = 'DELETE FROM contacts WHERE id = :id';
            $stmt = $pdo->prepare($sql);

            // add
            $stmt->execute(['id' => $id]);
            $pdo = null;

            return true;
        } catch (PDOException $exception) {
            $this->dberr = $exception;
            $pdo = null;

            return false;
        }
    }

    /**
     * DB operation for attempting to edit a contact
     * @param  array $form POSTed form data containing contact info
     */
    public function edit_contact($form) {
        $this->dberr = '';

        try {
            // setup db connection
            $pdo = new PDO(DB_CONN, DB_USER, DB_PASS);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // prepare statement
            $sql = 'UPDATE contacts SET ' .
                'fname = :fname, ' . 'surname = :surname, ' .
                'phone = :phone, ' . 'email = :email, ' .
                'address = :address, ' . 'city = :city, ' .
                'province = :province, ' . 'postal = :postal, ' .
                'birthday = :birthday, ' . 'rep = :rep ' .
                'WHERE id = :id';
            $stmt = $pdo->prepare($sql);

            // add
            $stmt->execute($form);
            $pdo = null;

            return true;
        } catch (PDOException $exception) {
            $this->dberr = $exception;
            $pdo = null;

            return false;
        }
    }

    /**
     * Run POSTed contact modification form through some validation functions
     *
     * @param  array $form POSTed form containing contact info
     */
    public function validate_contact($form) {
        $valid_contact = $form;

        foreach ($form as $form_field => $field_value) {
            if (empty($field_value)) {
                $this->validation_err = 'Please fill out all of the fields';
                return false;
            }

            // currently procedural
            // would be better to validate everything and
            // return all messages
            switch ($form_field) {
                case 'email':
                    $email = filter_var($field_value, FILTER_SANITIZE_EMAIL);

                    if (!$email) {
                        $this->validation_err = 'Invalid email address';
                        return false;
                    }

                    $valid_contact['email'] = $email;

                    break;
                case 'fname':
                    if (strlen($field_value) > 100) {
                        $this->validation_err = 'First name too long';
                        return false;
                    }

                    break;
                case 'phone':
                    $phone = preg_replace('/[^0-9]/', '', $field_value);

                    if (strlen($phone) != 10) {
                        $this->validation_err = 'Phone number must be ten digits (1234567890, 123-456-7890, (123) 456-7890)';
                        return false;
                    }

                    $valid_contact['phone'] = $phone;

                    break;
                case 'postal':
                    $postal = preg_replace('/\s+/', '', $field_value);

                    if (strlen($postal) != 6) {
                        $this->validation_err = 'Invalid postal code (A1A 1A1, A1A1A1)';
                        return false;
                    }

                    $valid_contact['postal'] = strtoupper($postal);

                    break;
                case 'surname':
                    if (strlen($field_value) > 100) {
                        $this->validation_err = 'Last name too long';
                        return false;
                    }

                    break;
                default:
                    break;
            }
        }

        return $valid_contact;
    }
}

?>
