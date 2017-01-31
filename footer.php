<?php

/**
 * Footer template
 *
 * This template closes the content <div> and displays the footer for the
 * site. It also calls wp_footer().
 */

?>

</div>

<div class="footer" role="contentinfo">
    <p>Copyright &copy; <?php

    // Print a comma-separated list of authors
    wp_list_authors(array(
        'exclude_admin' => false,
        'hide_empty' => false,
        'show_fullname' => true,
        'html' => false,
    ));

    // Print the date
    echo ' ' . date('Y');

    ?></p>
</div>

<?php wp_footer(); ?>

</body>

</html>
