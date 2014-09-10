<?php

/**
 * Search form
 */

?>
<form action="<?php echo esc_url( home_url('/') ); ?>" method="get" role="search">
    <p>
        <label for="s">Search</label>
        <input type="search" name="s" id="s" value="<?php echo get_search_query(); ?>" />
    </p>
    <p>
        <button>Search</button>
    </p>
</form>
