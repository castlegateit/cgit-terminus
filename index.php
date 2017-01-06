<?php

/**
 * Main template
 *
 * This file displays the main posts page when home.php does not exist. It
 * also displays a page when nothing more specific matches a query.
 */

get_header();

?>

<div class="main" role="main">
    <?php

    if (have_posts()) {
        while (have_posts()) {
            the_post();
            get_template_part('content');
        }

        echo \Cgit\Terminus::pagination();
    } else {
        get_template_part('content', 'none');
    }

    ?>
</div>

<?php

get_sidebar();
get_footer();
