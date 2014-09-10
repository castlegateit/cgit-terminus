<?php

/**
 * Terminus functions and definitions
 *
 * This file contains the main settings for the Terminus WordPress theme and
 * provides various functions that can be used in the other template files.
 * It also activates support for various WordPress features. The functions
 * defined here can be overridden by defining them first in a child theme.
 */

/**
 * Setup theme and register basic features
 */
if ( ! function_exists('terminus_init') ) {

    function terminus_init () {
        add_editor_style();
        add_theme_support('automatic-feed-links');
        add_theme_support('post-thumbnails');
    }

}

add_action('after_setup_theme', 'terminus_init');

/**
 * Remove default actions
 */
if ( ! function_exists('terminus_head_init') ) {

    function terminus_head_init () {
        remove_action('wp_head', 'rsd_link');
        remove_action('wp_head', 'wlwmanifest_link');
        remove_action('wp_head', 'wp_generator');
    }

}

add_action('init', 'terminus_head_init');

/**
 * Add styles and scripts
 *
 * Use get_template_directory_uri() to return the parent theme URI; use
 * get_stylesheet_directory_uri() to return the child theme URI.
 */
if ( ! function_exists('terminus_scripts_init') ) {

    function terminus_scripts_init () {

        $uri = get_template_directory_uri();

        // Add basic CSS reset
        wp_enqueue_style('terminus-style', "$uri/style.css");

        // If using Terminus itself, add basic layout
        if ( ! is_child_theme() ) {
            wp_enqueue_style('terminus-style-default', "$uri/style-default.css");
        }

        // Add comment reply script
        if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
            wp_enqueue_script('comment-reply');
        }

    }

}

add_action('wp_enqueue_scripts', 'terminus_scripts_init');

/**
 * Register navigation menus
 */
if ( ! function_exists('terminus_nav_init') ) {

    function terminus_nav_init () {

        register_nav_menus(array(
            'main-nav' => 'Main Navigation',
        ));

    }

}

add_action('init', 'terminus_nav_init');

/**
 * Register widget areas
 */
if ( ! function_exists('terminus_widgets_init') ) {

    function terminus_widgets_init () {

        register_sidebar(array(
            'name'          => 'Main Sidebar',
            'id'            => 'main-sidebar',
            'description'   => 'Main sidebar that appears after the main content.',
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h2>',
            'after_title'   => '</h2>',
        ));

    }

}

add_action('widgets_init', 'terminus_widgets_init');

/**
 * Generate page title
 */
if ( ! function_exists('terminus_title') ) {

    function terminus_title ($title, $sep) {

        global $page, $paged;

        if ( is_feed() ) {
            return $title;
        }

        $title       .= get_bloginfo('name');
        $description  = get_bloginfo('description');

        if ( $description && ( is_home() || is_front_page() ) ) {
            $title = "$title $sep $description";
        }

        if ( $page > 1 || $paged > 1 ) {
            $max   = max($page, $paged);
            $title = "$title $sep Page $max";
        }

        return $title;

    }

}

add_filter('wp_title', 'terminus_title', 10, 2);

/**
 * Return post pagination
 */
if ( ! function_exists('terminus_pagination') ) {

    function terminus_pagination () {

        global $wp_query;

        $max  = 999999;
        $link = get_pagenum_link($max);
        $page = get_query_var('paged') ? intval( get_query_var('paged') ) : 1;

        $args = array(
            'base'      => str_replace($max, '%#%', $link),
            'current'   => $page,
            'total'     => $wp_query->max_num_pages,
            'mid_size'  => 1,
            'prev_text' => 'Previous',
            'next_text' => 'Next',
        );

        $links = paginate_links($args);

        if ($links) {
            return "<div class='pagination'>$links</div>";
        }

    }

}

/**
 * Return a post taxonomy
 *
 * This returns a list of terms in a taxonomy for a single post without any
 * additional rel attributes. By default, it lists the category taxonomy; use
 * post_tag to list tags. This must be used within the loop. The exclude
 * argument accepts a single post ID, a comma-separated list of IDs, or an
 * array of IDs.
 */
if ( ! function_exists('terminus_taxonomy') ) {

    function terminus_taxonomy ($taxon = 'category', $before = '', $sep = '', $after = '', $exclude = FALSE) {

        global $post;
        $terms = get_the_terms($post->ID, $taxon);

        if ( ! empty($terms) && $exclude ) {

            if ( is_int($exclude) ) {
                $exclude = array($exclude);
            } elseif ( is_string($exclude) ) {
                $exclude = explode(',', $exclude);
            }

            foreach ($terms as $k => $v) {
                if ( in_array($v->term_id, $exclude) ) {
                    unset($terms[$k]);
                }
            }

        }

        $output = '';

        if ( ! empty($terms) ) {

            $output .= $before;
            $list    = array();

            foreach($terms as $term) {
                $link   = get_term_link($term);
                $name   = $term->name;
                $list[] = "<a href='$link'>$name</a>";
            }

            $output .= implode($sep, $list);
            $output .= $after;

        }

        return $output;

    }

}
