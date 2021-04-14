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
            <h1 class='page-title text-center'><?php echo APP_TITLE; ?></h1>

            <div class='pure-u-1 pure-u-sm-4-5 pure-u-md-3-4 d-block mx-auto'>
                <p class='page-subtitle'>Email your contacts</p>

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
                $email_fields = array(
                    'body' => '',
                    'subject' => ''
                );

                // if data exists, we're probably sent back to form after error
                // re-populate fields
                if (isset($this->data['email']) && !empty($this->data['email'])) {
                    $email = $this->data['email'];

                    foreach ($email as $key => $value) {
                        $email_fields[$key] = $value;
                    }
                }

                $action = $_SERVER['PHP_SELF'] . '?email';

                ?>

                <form action='<?php echo $action; ?>' class='pure-form pure-form-stacked pure-g justify-between' method='post'>
                    <div class='field pure-u-1'>
                        <label>Subject</label>
                        <input name='subject'
                            placeholder='Type the subject here..'
                            required='true'
                            type='text' value='<?php echo $email_fields['subject']; ?>' />
                    </div>

                    <div class='field pure-u-1'>
                        <label>Message</label>
                        <textarea class='pure-u-1' name='body'
                            placeholder='Type your message here..'
                            required='true'
                            rows='10'
                            value='<?php echo $email_fields['body']; ?>'></textarea>
                    </div>

                    <input type='hidden' name='rep' value='<?php echo $_SESSION['user_id']; ?>' />

                    <div class='field pure-u-1'>
                        <div class='pure-g justify-between'>
                            <a class='pure-button button-warning pure-u-1 pure-u-md-11-24' href='<?php echo $back; ?>'>
                                &laquo; Back
                            </a>

                            <input class='pure-button button-success pure-u-1 pure-u-md-11-24'
                                required='true'
                                type='submit'
                                value='Send &raquo;'
                            />
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>
</html>
