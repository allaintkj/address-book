<!DOCTYPE html>
<html lang='en'>

<head>
    <meta charset='utf-8'>
    <meta name='viewport' content='width=device-width,initial-scale=1'>
    <meta http-equiv='X-UA-Compatible' content='ie=edge'>
    <title><?php echo APP_TITLE; ?></title>
    <link rel='stylesheet' required='true' type='text/css' href='css/style.min.css'>
</head>

<body>

    <div class='pure-g justify-center'>
        <div class='pure-u-1 pure-u-sm-4-5 pure-u-md-3-4 pure-u-lg-2-3 pure-u-xl-1-2'>
            <h1 class='page-title text-center'>
                <?php

                $action = $_SERVER['PHP_SELF'] . $this->data['form_action'];
                $btn_value = strpos($this->data['form_action'], 'add') ? 'Add' : 'Update';
                echo APP_TITLE;

                ?>
            </h1>

            <div class='pure-u-1 pure-u-sm-4-5 pure-u-md-3-4 d-block mx-auto'>
                <p class='page-subtitle'><?php echo $btn_value; ?> a contact</p>

                <?php

                $msg_class = isset($_SESSION['msg_class']) ? $_SESSION['msg_class'] : '';
                $msg = isset($_SESSION['msg']) ? $_SESSION['msg'] : '';

                if (!empty($msg) && !empty($msg_class)): ?>
                    <div class='field'>
                        <span class='pure-form-message <?php echo $msg_class; ?>'>
                            <?php echo $msg; ?>
                        </span>
                    </div>

                <?php

                    $_SESSION['msg'] = '';
                    $_SESSION['msg_class'] = '';

                endif;

                $back = $_SERVER['PHP_SELF'] . '?contacts';
                $contact_fields = array(
                    'fname' => '',
                    'surname' => '',
                    'phone' => '',
                    'email' => '',
                    'address' => '',
                    'city' => '',
                    'province' => '',
                    'postal' => '',
                    'birthday' => ''
                );

                if (isset($this->data['contact']) && !empty($this->data['contact'])) {
                    $contact = $this->data['contact'];

                    foreach ($contact as $key => $value) {
                        $contact_fields[$key] = $value;
                    }
                }

                ?>

                <form action='<?php echo $action; ?>' class='pure-form pure-form-stacked pure-g justify-between' method='post'>
                    <div class='field pure-u-1 pure-u-lg-11-24'>
                        <label>First Name</label>
                        <input name='fname'
                            placeholder='John'
                            type='text' value='<?php echo $contact_fields['fname']; ?>' />
                    </div>

                    <div class='field pure-u-1 pure-u-lg-11-24'>
                        <label>Last Name</label>
                        <input name='surname'
                            placeholder='Doe'
                            required='true' type='text'
                            value='<?php echo $contact_fields['surname']; ?>'
                        />
                    </div>

                    <div class='field pure-u-1 pure-u-lg-11-24'>
                        <label>Phone Number</label>
                        <input name='phone'
                            placeholder='123-456-7890'
                            required='true' type='tel'
                            value='<?php echo $contact_fields['phone']; ?>'
                        />
                    </div>

                    <div class='field pure-u-1 pure-u-lg-11-24'>
                        <label>Email</label>
                        <input name='email'
                            placeholder='example@provider.com'
                            required='true' type='email'
                            value='<?php echo $contact_fields['email']; ?>'
                        />
                    </div>

                    <div class='field pure-u-1'>
                        <label>Street Address</label>
                        <input name='address'
                            placeholder='123 Somewhere Street'
                            required='true' type='text'
                            value='<?php echo $contact_fields['address']; ?>'
                        />
                    </div>

                    <div class='field pure-u-1 pure-u-lg-11-24'>
                        <label>City</label>
                        <input name='city'
                            placeholder='Halifax'
                            required='true' type='text'
                            value='<?php echo $contact_fields['city']; ?>'
                        />
                    </div>

                    <div class='field pure-u-1 pure-u-lg-11-24'>
                        <label>Province</label>
                        <select name='province' required='true'>
                            <?php

                            $provs = array('Alberta', 'British Columbia', 'Manitoba', 'New Brunswick', 'Newfoundland and Labrador',
                                'Nova Scotia', 'Ontario', 'Prince Edward Island', 'Quebec', 'Saskatchewan');

                            foreach ($provs as $province):

                            ?>

                            <option value='<?php echo $province; ?>'><?php echo $province; ?></option>

                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class='field pure-u-1 pure-u-lg-11-24'>
                        <label>Postal Code</label>
                        <input name='postal'
                            placeholder='A1A 1A1'
                            required='true' type='text'
                            value='<?php echo $contact_fields['postal']; ?>'
                        />
                    </div>

                    <div class='field pure-u-1 pure-u-lg-11-24'>
                        <label>Birthday</label>
                        <input name='birthday'
                            placeholder='MM-DD-YYYY'
                            required='true' type='date'
                            value='<?php echo $contact_fields['birthday']; ?>'
                        />
                    </div>

                    <input type='hidden' name='rep' value='<?php echo $_SESSION['user_id']; ?>' />

                    <div class='field pure-u-1'>
                        <div class='pure-g justify-between'>
                            <a class='pure-button button-warning pure-u-1 pure-u-md-7-24' href='<?php echo $back; ?>'>
                                &laquo; Back
                            </a>

                            <?php if (strpos($this->data['form_action'], 'edit')): ?>

                            <a class='pure-button button-error pure-u-1 pure-u-md-7-24'
                                href='<?php echo $_SERVER['PHP_SELF'] . '?delete&contact_id=' . $_GET['contact_id']; ?>'>
                                Delete
                            </a>

                            <?php endif; ?>

                            <input class='pure-button button-success pure-u-1 pure-u-md-7-24'
                                required='true'
                                type='submit'
                                value='<?php echo $btn_value; ?> &raquo;'
                            />
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>
</html>
