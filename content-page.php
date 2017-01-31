<?php

/**
 * Content template: page
 */

?>

<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <?php

    the_title('<h1>', '</h1>');
    the_content('Continue reading &hellip;');

    ?>
</div>
