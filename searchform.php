<?php

/**
 * Search form
 */

?>
<form action="<?= esc_url(home_url('/')) ?>" method="get" role="search">
    <p>
        <label for="s">Search</label>
        <input type="search" name="s" id="s" value="<?= get_search_query() ?>" />
    </p>

    <p>
        <button>Search</button>
    </p>
</form>
