<?php

/**
 * Author archive template
 */

get_header();

?>

<div class="main" role="main">
    <h1>Author: <?php the_author(); ?></h1>
    <?php

    if (have_posts()) {
        while (have_posts()) {
            the_post();
            get_template_part('content');
        }
        echo terminus_pagination();
    } else {
        get_template_part('content', 'none');
    }

    ?>
</div>

<?php

get_sidebar();
get_footer();
