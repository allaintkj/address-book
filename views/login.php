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

            <div class='pure-u-1 pure-u-sm-4-5 pure-u-md-3-4 pure-u-lg-2-3 pure-u-xl-1-2 d-block mx-auto'>
                <p class='page-subtitle'>Please login...</p>

                <form class='pure-form pure-form-stacked' method='post' action='<?php echo $_SERVER['PHP_SELF']; ?>'>
                    <div class='field'>
                        <label>Username</label>
                        <input name='username' placeholder='username' type='text' />
                    </div>

                    <div class='field'>
                        <label>Password</label>
                        <input name='password' placeholder='password' type='password' />
                    </div>

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

                    <div class='field'>
                        <input class='pure-button button-success' type='submit' value='Login &raquo;'>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>
</html>
