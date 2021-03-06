<?php
        include_once './common_functions.php';

        use domain\UserManager;
        use domain\User;

        function generate_page_header_with_user($title, $user = false)
        {
                header('Content-Type: text/html; charset=utf-8');

                echo "<!DOCTYPE html>\n";
                echo "<html>\n";
                echo '<head><meta charset="utf-8"><title>';

                if ($title === NULL) {
                        echo 'My forum';
                } else {
                        echo escape_str_in_usual_html_pl($title, false);
                }
                echo "</title></head>\n";
                echo "<body>\n";

                if ($user !== false) {
                        echo '<p style="text-align: right">';
                        if ($user !== NULL) {
                                echo "Welcome, {$user->login} ";
                                echo '<a href="logout.php">Log out</a> ';
                                echo '<a href="change_password.php">Change password</a>';
                        } else {
                                echo '<a href="new_user_form.php">Sign up</a> ';
                                echo '<a href="login_form.php">Log in</a>';
                        }
                        echo "</p>\n";
                }

                echo "<h1>My forum</h1>\n";

        }
