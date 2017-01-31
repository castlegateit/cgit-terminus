<?php

/**
 * Excerpt template
 */

?>

<div id="post-<?php the_ID(); ?>" <?php post_class(); ?> role="article">
    <?php

    $url = get_permalink();
    the_title('<h2><a href="' . get_permalink() . '">', '</a></h2>');
    the_excerpt();
    get_template_part('meta');

    ?>
</div>
