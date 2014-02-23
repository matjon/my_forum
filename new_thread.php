<?php
try {
        include_once './init_classloader.php';
        include_once "./common_functions.php";
        include_once "./database_connection.php";
        include_once "./page_header.php";
        use domain\UserManager;
        use domain\ForumSection;

        $error = NULL;
        $name = "";
        $contents = "";
        $thread_name_error_msg = NULL;
        $contents_error_msg = NULL;

        $dbh = get_database_connection();
        $um = new UserManager($dbh);
        $user = $um->get_logged_in_user();

        if ($user === NULL) {
                $error = "You are not currently logged in";
        }

        if ($user !== NULL && array_key_exists('name', $_POST)) {
        //INPUT VALIDATION
                $name = sanitize_string_input($_POST['name']);
                $contents = sanitize_string_input($_POST['contents']);        
                $_POST=NULL;
                $_GET=NULL;
        //END OF INPUT VALIDATION

                $post = ForumPost::create_as_new($dbh, $contents, $user, $contents_error_msg);
                $thread = ForumThread::create_as_new($dbh, $name, $user, $thread_name_error_msg);
                if ($post !== null && $thread !== null) {
                        if ($thread->persist($thread_name_error_msg)) {
                                $post->thread_id = $thread->id;
                                if ($post->persist($contents_error_msg)) {
                                        my_redirect('show_thread.php?thread_id='.$thread->get_id());
                                }
                        }
                }
        }
} catch (PDOException $e) {
        $error = "Database error";
}

        generate_page_header("My forum - Create a new thread", $dbh);
?>


<h2>Create a new thread</h2>

<?php
        if ($error !== NULL) {
                echo '<h2>';
                echo $error;
                echo '</h2>';
        }
?>
<form action="new_thread.php" method="post" accept-charset="UTF-8">
        <?php  if ($thread_name_error_msg !== NULL) echo "<p>$thread_name_error_msg</p>" ?>
        <p>Name: <input type="text" name="name" maxlength="950" 
                value="<?php echo escape_str_in_usual_html_pl($name) ?>">
        </p>
        <?php  if ($contents_error_msg !== NULL) echo "<p>$contents_error_msg</p>" ?>
        <p><textarea rows="5" cols="100" name="contents" maxlength="9990"><?php echo escape_str_in_usual_html_pl($contents) ?></textarea></p>
        <p><input type="submit" value="Submit a new thread"></p>
</form>
<form action="threadlist.php" method="get">
        <p><input type="submit" value="Go back to the thread list"></p>
</form>
</body>
</html>
