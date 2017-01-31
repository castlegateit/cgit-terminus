<?php

/**
 * Sidebar template
 */

?>

<div class="side" role="complementary">
    <?php

    if (is_active_sidebar('main-sidebar')) {
        dynamic_sidebar('main-sidebar');
    }

    ?>
</div>
