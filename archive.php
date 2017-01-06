<?php

/**
 * Archive template
 *
 * This template will be used for all archives, including categories, tags, and
 * author archives. You can override these individually with category.php,
 * tag.php, and author.php respectively.
 */

get_header();

?>

<div class="main" role="main">
    <?php

    the_archive_title('<h1>', '</h1>');

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
