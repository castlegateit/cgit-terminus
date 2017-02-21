<?php

/**
 * Header template
 *
 * This file contains the <head> element and the page header. It also calls
 * the wp_head() function.
 */

?><!DOCTYPE html>

<html <?php language_attributes(); ?>>

<head>

<meta charset="<?php bloginfo('charset'); ?>" />
<meta name="viewport" content="width=device-width, initial-scale=1" />

<?php wp_head(); ?>

</head>

<body <?php body_class(); ?>>

<div class="header" role="banner">
    <h1>
        <a href="<?= esc_url(home_url('/')) ?>">
            <?php bloginfo('name'); ?>
        </a>
    </h1>

    <p><?php bloginfo('description'); ?></p>

    <div class="nav" role="navigation">
        <?php

        wp_nav_menu([
            'theme_location' => 'main-nav',
        ]);

        ?>
    </div>
</div>

<div class="content">
