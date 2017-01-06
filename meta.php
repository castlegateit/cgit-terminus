<div class="meta">
    <p><?php

    // Author
    // the_author_link();

    // Date
    the_time(get_option('date_format'));

    // Categories
    echo \Cgit\Terminus::taxonomy([
        'taxonomy' => 'category',
        'before' => ' | Categories: ',
    ]);

    // Tags
    echo \Cgit\Terminus::taxonomy([
        'taxonomy' => 'post_tag',
        'before' => ' | Tags: ',
    ]);

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
