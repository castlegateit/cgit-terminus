# Terminus #

Terminus is a simple WordPress theme designed to act as a foundation for theme development. It should not be customized directly. Instead, you should create a child theme with your own templates and styles.

## What's in the box? ##

Terminus is designed to act as a starting point for theme development. Any of its features can be overridden by a child theme. However, here is what you get by default:

*   A basic CSS reset `style.css`, similar to [Normalize.css](http://necolas.github.io/normalize.css/). By default, this is loaded by child themes as well.
*   A simple CSS layout `style-default.css`. This is mainly used for testing and debugging Terminus. This will _not_ be loaded by child themes.
*   An editable navigation menu called `main-nav`.
*   A widget area (sidebar) called `main-sidebar`.

## Child themes ##

The best way to customize Terminus is to use a child theme. You can use this to replace any of the template files in Terminus. The file `functions.php` is a special case: the parent version will always be loaded, but only _after_ the child version. Therefore, you can use the child `functions.php` to override any of the functions in the main Terminus file. Here are the default functions that you can redefine:

*   `terminus_init()` is used to add theme features, such as editor styles, feed links, and featured images.
*   `terminus_head_init()` removes certain WordPress header actions.
*   `terminus_scripts_init()` loads CSS and JavaScript files. You should always enqueue CSS and JavaScript files properly. By default, this function will load a basic CSS reset. When used _without_ a child theme, it will also load a simple layout for Terminus.
*   `terminus_nav_init()` registers the navigation menu(s).
*   `terminus_widgets_init()` registers the widget area(s).
*   `terminus_title()` is used to generate the page title.
*   `terminus_pagination()` returns a pagination list for indexes and archives.
*   `terminus_taxonomy()` returns a list of terms in a taxonomy for a particular post, without the invalid WordPress `rel` attributes.

Defining functions with these names in your child theme will override the functions defined in Terminus. You can also add your own functions and leave these alone if, for example, you want to load the default scripts and some more of your own. Take a look at the [minimal child theme template](https://github.com/castlegateit/terminus-child-template) and the [example child theme](https://github.com/castlegateit/terminus-child-example) to see how all this works.

Note that Terminus must be installed in a folder named `terminus` for child themes to work.

## Development ##

Terminus should act as a foundation and it should be possible to update it in place without breaking child themes and plugins. Therefore, please be careful not to change core parts of theme in a way that will break other sites. Before making any changes, you should also read the official WordPress guidelines for theme development:

*   [Theme Check](http://make.wordpress.org/themes/guidelines/guidelines-theme-check/)
*   [Plugin Territory](http://make.wordpress.org/themes/guidelines/guidelines-plugin-territory/)

The most important thing is to remember that themes are only for presentation. They should not add functions or content. Anything that changes how something works, rather than how it looks, should be part of a plugin. You can test Terminus (and child themes) using the WordPress test data and theme check plugin:

*   [Theme Unit Test](http://codex.wordpress.org/Theme_Unit_Test)
*   [Theme Check](http://wordpress.org/plugins/theme-check/)

## Recommended plugins ##

*   [Lock Pages](https://wordpress.org/plugins/lock-pages/) restricts page editing by non-admin users.
*   [Login Lockdown](https://wordpress.org/plugins/login-lockdown/) restricts login attempts for additional security.
*   [WP Minify Fix](https://wordpress.org/plugins/wp-minify-fix/) combines and compresses enqueues CSS and JavaScript.

## Known issues ##

*   Terminus does not currently support localization.
*   The second and third levels of the default navigation menu are not particularly accessible. However, this is only an issue when Terminus is used without a child theme.
