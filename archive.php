<?php

/**
 * Archive template
 */

get_header();

?>

    <div class="main" role="main">
        <h1><?php

            if ( is_day() ) {
                printf( 'Archive: %s', get_the_date('j F Y') );
            } elseif ( is_month() ) {
                printf( 'Archive: %s', get_the_date('F Y') );
            } elseif ( is_year() ) {
                printf( 'Archive: %s', get_the_date('Y') );
            } else {
                echo 'Archive';
            }

        ?></h1>
        <?php

            if ( have_posts() ) {
                while ( have_posts() ) {
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
