<?php

/**
 * Comments template
 */

if (post_password_required()) {
    return;
}

?>

<div id="comments" class="comments">

<?php

if (have_comments()) {
    ?>
    <h2>Comments: <?php comments_number('0', '1', '%'); ?></h2>

    <ol><?php

    wp_list_comments([
        'type' => 'comment',
        'style' => 'ol',
        'login_text' => 'Log in to reply',
    ]);

    ?></ol>
    <?php

    if (get_option('page_comments') && get_comment_pages_count() > 1) {
        ?>
        <div class="comments-pagination">
            <?php previous_comments_link('Older comments'); ?>
            <?php next_comments_link('Newer comments'); ?>
        </div>
        <?php
    }
}

if (comments_open()) {
    $commenter = wp_get_current_commenter();
    $required = get_option( 'require_name_email' ) ? ' (required)' : '';

    $fields = [
        'author' => '<p><label for="author">Name' . $required . '</label>'
            . '<input type="text" name="author" id="author" value="'
            . $commenter['comment_author'] . '" /></p>',

        'email' => '<p><label for="email">Email' . $required . '</label>'
            . '<input type="text" name="email" id="email" value="'
            . $commenter['comment_author_email'] . '" /></p>',

        'url' => '<p><label for="url">Website</label>'
            . '<input type="text" name="url" id="url" value="'
            . $commenter['comment_author_url'] . '" /></p>'
    ];

    $message = '<p><label for="comment">Comment</label>'
        . '<textarea name="comment" id="comment"></textarea></p>';

    comment_form([
        'fields' => apply_filters('comment_form_default_fields', $fields),
        'comment_field' => $message,
        'title_reply' => 'Leave a reply',
        'title_reply_to' => 'Leave a reply to %s',
        'cancel_reply_link' => 'Cancel reply',
        'label_submit' => 'Post Comment',
    ]);
} else {
    echo '<p>Comments are closed.</p>';
}

?>

</div>
