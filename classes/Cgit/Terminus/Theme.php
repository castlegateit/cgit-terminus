<?php

namespace Cgit\Terminus;

/**
 * Terminus theme configuration
 *
 * A class that registers the basic features of the Terminus theme, including
 * actions, menus, widgets, and scripts. For backward-compatibility these can be
 * overridden by functions defined in a child theme. The entire class can also
 * be extended or replaced with a compatible class defined in the child theme by
 * defining the TERMINUS_THEME_CLASS constant.
 */
class Theme
{
    /**
     * Constructor
     *
     * Registers the basic features of the Terminus theme via WordPress actions
     * and sets the page title format via a filter. For backward compatibility,
     * these methods can be overridden by defining particular functions in the
     * child theme.
     *
     * @return void
     */
    public function __construct()
    {
        add_action('init', [$this, 'registerFeatures']);
        add_action('init', [$this, 'registerActions']);
        add_action('init', [$this, 'registerMenus']);
        add_action('widgets_init', [$this, 'registerWidgets']);
        add_action('wp_enqueue_scripts', [$this, 'registerScripts']);
    }

    /**
     * Register basic theme features
     *
     * @return void
     */
    public function registerFeatures()
    {
        if (function_exists('terminus_init')) {
            return terminus_init();
        }

        add_theme_support('automatic-feed-links');
        add_theme_support('post-thumbnails');
        add_theme_support('title-tag');
    }

    /**
     * Register actions
     *
     * Removes various unnecessary actions, particularly those that add stuff to
     * the document head.
     *
     * @return void
     */
    public function registerActions()
    {
        if (function_exists('terminus_head_init')) {
            return terminus_head_init();
        }

        remove_action('wp_head', 'print_emoji_detection_script', 7);
        remove_action('wp_head', 'rsd_link');
        remove_action('wp_head', 'wlwmanifest_link');
        remove_action('wp_head', 'wp_generator');
        remove_action('wp_print_styles', 'print_emoji_styles');
    }

    /**
     * Register navigation menus
     *
     * @return void
     */
    public function registerMenus()
    {
        if (function_exists('terminus_nav_init')) {
            return terminus_nav_init();
        }

        register_nav_menus([
            'main-nav' => 'Main Navigation',
        ]);
    }

    /**
     * Register widget areas
     *
     * @return void
     */
    public function registerWidgets()
    {
        if (function_exists('terminus_widgets_init')) {
            return terminus_widgets_init();
        }

        register_sidebar([
            'name' => 'Main Sidebar',
            'id' => 'main-sidebar',
            'description' => 'Main sidebar that appears after the main content.',
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h2>',
            'after_title' => '</h2>',
        ]);
    }

    /**
     * Register scripts and styles
     *
     * @return void
     */
    public function registerScripts()
    {
        if (function_exists('terminus_scripts_init')) {
            return terminus_scripts_init();
        }

        if (is_singular() && comments_open() && get_option('thread_comments')) {
            wp_enqueue_script('comment-reply');
        }
    }
}
