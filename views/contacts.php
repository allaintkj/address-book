<!DOCTYPE html>
<html lang='en'>

<head>
    <meta charset='utf-8'>
    <meta name='viewport' content='width=device-width,initial-scale=1'>
    <meta http-equiv='X-UA-Compatible' content='ie=edge'>
    <title><?php echo APP_TITLE; ?></title>
    <link rel='stylesheet' type='text/css' href='css/style.min.css'>
</head>

<body>

    <div class='pure-g justify-center'>
        <div class='pure-u-1 pure-u-sm-4-5 pure-u-md-3-4 pure-u-lg-2-3 pure-u-xl-1-2'>
            <h1 class='page-title text-center'><?php echo APP_TITLE; ?></h1>

            <div class='pure-u-1 d-block mx-auto'>
                <?php if (isset($this->data['this_reps_contacts'])): ?>

                    <div class='text-center d-block mx-auto pure-g'>
                        <div class='pure-u-1 field'>
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

                            ?>

                            <a class='pure-button pure-button-primary'
                                href='<?php echo $_SERVER['PHP_SELF'] . '?login&logout=true'; ?>'>
                                Logout
                            </a>

                            <?php

                            $bday_button_href = isset($_GET['monthly']) ? '' : '&monthly=true';
                            $bday_button_value = isset($_GET['monthly']) ? 'See All Contacts' : 'Birthdays This Month';

                            ?>

                            <a class='pure-button button-warning'
                                href='<?php echo $_SERVER['PHP_SELF'] . '?contacts' . $bday_button_href; ?>'>
                                <?php echo $bday_button_value; ?>
                            </a>

                            <a class='pure-button button-secondary'
                                href='<?php echo $_SERVER['PHP_SELF'] . '?email&rep=' . $_SESSION['user_id']; ?>'>
                                Email All Contacts
                            </a>

                            <a class='pure-button button-success'
                                href='<?php echo $_SERVER['PHP_SELF'] . '?add&rep=' . $_SESSION['user_id']; ?>'>
                                Add a Contact
                            </a>
                        </div>

                        <div class='pure-u-1 pure-u-lg-2-3'>
                            <form class='pure-form pure-form-stacked' action='<?php echo $_SERVER['PHP_SELF']; ?>'>
                                <input type='file' name='sql_import' />
                                <input class='pure-button' type='button' value='Import' />
                            </form>
                        </div>
                    </div>

                    <div class='contacts-table'>
                        <table class='pure-table pure-table-striped mx-auto'>
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Phone</th>
                                    <th>Email</th>
                                    <th>Birthday</th>
                                    <th>Location</th>
                                    <th>Postal Code</th>
                                </tr>
                            </thead>

                            <?php

                            foreach ($this->data['this_reps_contacts'] as $contact):
                                $address = $contact['address'] . ', ' .
                                    $contact['city'] . ', ' .
                                    $contact['province'];
                                $birthday = date('F j, Y', strtotime($contact['birthday']));
                                $name = $contact['fname'] . ' ' . $contact['surname'];
                                $phone = '(' . substr($contact['phone'], 0, 3) . ') ' .
                                    substr($contact['phone'], 3, 3) . '-' .
                                    substr($contact['phone'], 6, 4);

                                $query_string = $_SERVER['PHP_SELF'] . '?edit&contact_id=' .
                                    $contact['id'] . '&rep=' . $contact['rep'];

                            ?>

                                <tr>
                                    <td>
                                        <a href='<?php echo $query_string; ?>'>
                                            <?php echo $name; ?>
                                        </a>
                                    </td>
                                    <td><?php echo $phone; ?></td>
                                    <td><?php echo $contact['email']; ?></td>
                                    <td><?php echo $birthday; ?></td>
                                    <td><?php echo $address; ?></td>
                                    <td><?php echo $contact['postal']; ?></td>
                                </tr>

                            <?php endforeach; ?>
                        </table>
                    </div>

                <?php endif; ?>
            </div>
        </div>
    </div>

</body>
</html>
