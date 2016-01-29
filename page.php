<?php

/**
 * Page template
 */

get_header();

?>

<div class="main" role="main">
    <?php

    while (have_posts()) {
        the_post();
        get_template_part('content', 'page');

        if (comments_open() || get_comments_number()) {
            comments_template();
        }
    }

    ?>
</div>

<?php

get_sidebar();
get_footer();
