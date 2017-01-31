<?php

/**
 * Search template
 */

get_header();

?>

<div class="main" role="main">
    <h1>Search: <?= get_search_query() ?></h1>

    <?php

    if (have_posts()) {
        while (have_posts()) {
            the_post();
            get_template_part('excerpt');
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
