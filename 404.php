<?php

/**
 * 404 template
 */

get_header();

?>

<div class="main" role="main">
    <h1>Page not found</h1>

    <p>The page you were looking for could not be found. Please try one of the links on this page or use the search box below.</p>

    <?php get_search_form(); ?>
</div>

<?php

get_sidebar();
get_footer();
