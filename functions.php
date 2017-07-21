<?php

/**
 * Terminus functions and definitions
 *
 * This file contains the main settings for the Terminus WordPress theme and
 * provides various functions that can be used in the other template files.
 * It also activates support for various WordPress features.
 */

require_once __DIR__ . '/classes/autoload.php';

/**
 * Load the theme class
 *
 * The theme class can be overridden by defining a constant in the child theme
 * with the name of the replacement class. This could be an entirely new class
 * or a class extended from the original theme class.
 */
$theme_class = '\Cgit\Terminus\Theme';

if (defined('TERMINUS_THEME_CLASS')) {
    $theme_class = TERMINUS_THEME_CLASS;
}

$theme = new $theme_class;

/**
 * Enqueue CSS and JavaScript
 *
 * This is a wrapper for the enqueue method in the Terminus utility class and is
 * provided for backward compatibility.
 *
 * @deprecated 2.0
 */
function terminus_enqueue($src, $deps = [], $script = null, $parent = null)
{
    return \Cgit\Terminus::enqueue($src, $deps, $script, $parent);
}

/**
 * Return post pagination
 *
 * This is a wrapper for the pagination method in the Terminus utility class and
 * is provided for backward compatibility. The output of the pagination method
 * can be modified by passing parameters, but these were not available in the
 * original pagination function.
 *
 * @deprecated 2.0
 */
function terminus_pagination()
{
    return \Cgit\Terminus::pagination();
}

/**
 * Return post taxonomy
 *
 * This is a wrapper for the taxonomy method in the Terminus utility class and
 * is provided for backward compatibility. The taxonomy method uses an array to
 * set options, in contrast to the large number of parameters passed to the
 * original taxonomy function.
 *
 * @deprecated 2.0
 */
function terminus_taxonomy()
{
    $args = func_get_args();
    $keys = ['taxonomy', 'before', 'sep', 'after', 'exclude'];
    $keys = array_slice($keys, 0, count($args));

    return \Cgit\Terminus::taxonomy(array_combine($keys, $args));
}
