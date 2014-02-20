<?php
        include_once "page_header.php";
        include_once "common_functions.php";
        include_once "database_connection.php";
        include_once "thread_class.php";

        $thread_id = sanitize_nonzero_integer_input($_REQUEST['thread_id'], 'threadlist.php');

        $dbh = get_database_connection();
        $thread = new ForumThread($thread_id);

        $text_error = NULL;
        if (array_key_exists('text', $_POST)) {
                $text = sanitize_string_input($_POST['text']);
                $_POST=NULL;

                if (!$thread->add_post($dbh, $text)) {
                        $text_error = $thread->get_last_error();       
                };
        };

        $thread_name = $thread->get_name($dbh);
        if ($thread_name === NULL) {
                $thread_name = "";
        }
        
        generate_page_header(escape_str_in_usual_html_pl($thread_name));
?>
<table style="width: 70%">
        <h1> 
                <?php echo escape_str_in_usual_html_pl($thread_name); ?>
        </h1>
<?php
        $stmt = $thread->get_all_posts($dbh);
        if (!is_null($stmt)) {
                while($row = $stmt->fetch()) {
                        echo '<tr><td style="border:1px solid black; padding:10px">';
                                echo escape_str_in_usual_html_pl($row[0]);
                        echo '</td></tr>';
                }
        } 
?>
</table>
<h2> Add a new post </h2>
	<form action="show_thread.php" method="post" accept-charset="UTF-8">
        <?php if ($text_error !== NULL) echo "<p>$text_error</p>" ?> 
        <p><textarea rows="5" cols="20" name="text"><?php if ($text_error !== NULL) echo escape_str_in_usual_html_pl($text) ?></textarea></p>
	<input type="hidden" name="thread_id" value="<?php echo $thread_id ?>">
	<p><input type="submit" value="Submit"></p>
</form>
<form action="threadlist.php" method="get">
	<p><input type="submit" value="Go back to the thread list"></p>
</form>

</body>
</html>