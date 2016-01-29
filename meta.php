<div class="meta">
    <p><?php

    // Author
    // the_author_link();

    // Date
    the_time(get_option('date_format'));

    // Categories
    echo terminus_taxonomy('category', ' | Categories: ', ', ');

    // Tags
    echo terminus_taxonomy('post_tag', ' | Tags: ', ', ');

    // Comments
    if (get_comments_number() > 0) {
        echo ' | ';
        comments_popup_link(
            'Comments: 0',
            'Comments: 1',
            'Comments: %',
            '',
            'Comments disabled'
        );
    }

    // Edit
    edit_post_link('Edit', ' | ', '');

    ?></p>
</div>
