<?php

namespace Cgit;

/**
 * Theme development utilities
 *
 * This class acts as a container for various static methods used by Terminus
 * and its child themes to make theme development easier. You cannot create an
 * instance of this class.
 */
class Terminus
{
    /**
     * Enqueued resource handles
     *
     * A list of resource handles enqueued using the Terminus enqueue method,
     * with the original source strings as the array keys.
     *
     * @var array
     */
    private static $resources = [];

    /**
     * Private constructor
     *
     * This class is more of a container for static methods that may be useful
     * for theme development, so there should be no reason to create an instance
     * of it.
     *
     * @return void
     */
    private function __construct()
    {
    }

    /**
     * Enqueue CSS and JavaScript
     *
     * An easier and cache-busting method to enqueue CSS and JavaScript files
     * with version numbers based on the last modified time of the source file.
     * Existing registered file handles (e.g. "jquery") will be enqueued as
     * normal. Absolute paths and URLs will be enqueued unmodified. Relative
     * paths will be enqueued relative to the active theme directory.
     *
     * You can also specify an array of dependencies, specify the resource type
     * (JavaScript true or false), and choose to enqueue relative paths from the
     * parent theme instead of the child theme. Note that, by default, the
     * function will attempt to detect the file type based on its extension.
     *
     * @param mixed $source
     * @param array $deps
     * @param boolean $script
     * @param boolean $parent
     * @return void
     */
    public static function enqueue(
        $source,
        $deps = [],
        $script = null,
        $parent = null
    ) {
        // Enqueue an array of resources, where each one depends on the previous
        // resources in the array.
        if (is_array($source)) {
            foreach ($source as $str) {
                self::enqueue($str, $deps, $script, $parent);
                $deps[] = $str;
            }

            return;
        }

        // Create and enqueue a new resource
        $resource = new \Cgit\Terminus\Resource($source, $deps);

        if (!is_null($script)) {
            $resource->setScript($script);
        }

        if (!is_null($parent)) {
            $resource->setParent($parent);
        }

        $resource->enqueue();

        // Save the resource handle for future reference
        self::$resources[$source] = $resource->getHandle();
    }

    /**
     * Return enqueued resource handle
     *
     * You might need to enqueue a resource using the original WordPress
     * functions, but with a dependency enqueued by Terminus. In these
     * situations, you will need to get the resource handle used internally by
     * Terminus.
     *
     * @param string $source
     * @return array
     */
    public static function getResourceHandle($source)
    {
        return self::$resources[$source];
    }

    /**
     * Return post pagination
     *
     * Provides an interface the default WordPress pagination function with
     * sensible default options. Options can be added or overridden in the
     * options array passed to the method.
     *
     * @param array $args
     * @return string
     */
    public static function pagination($args = [])
    {
        global $wp_query;

        $defaults = [
            'current' => intval(get_query_var('paged')) ?: 1,
            'total' => $wp_query->max_num_pages,
            'mid_size' => 1,
            'prev_text' => 'Previous',
            'next_text' => 'Next',
        ];

        $links = paginate_links(array_merge($defaults, $args));

        if ($links) {
            return '<div class="pagination">' . $links . '</div>';
        }

        return '';
    }

    /**
     * Return a post taxonomy
     *
     * Returns a comma-separated list of HTML taxonomy links for a particular
     * post. By default, it lists the category taxonomy; use post_tag to list
     * tags. You can exclude one or more terms using the exclude option.
     *
     * @param array $args
     * @return string
     */
    public static function taxonomy($args = [])
    {
        global $post;

        $defaults = [
            'taxonomy' => 'category',
            'before' => '',
            'after' => '',
            'sep' => ', ',
            'exclude' => false,
        ];

        $args = array_merge($defaults, $args);
        $terms = get_the_terms($post, $args['taxonomy']) ?: [];
        $exclude = self::explode($args['exclude']);

        // Remove excluded terms
        foreach ($terms as $key => $term) {
            if (in_array($term->term_id, $exclude)) {
                unset($terms[$key]);
            }
        }

        // Check that there are still some terms in the array
        if (empty($terms)) {
            return;
        }

        // Build the HTML list of terms
        $links = [];

        foreach ($terms as $term) {
            $links[] = '<a href="' . get_term_link($term) . '">' . $term->name
                . '</a>';
        }

        return $args['before'] . implode($args['sep'], $links) . $args['after'];
    }

    /**
     * Convert a variable to an array
     *
     * Provides behaviour similar to the native PHP explode function, but making
     * an assumption about the default delimiter.
     *
     * @param mixed $obj
     * @param string $delimiter
     * @return array
     */
    public static function explode($obj, $delimiter = ',')
    {
        if (is_array($obj)) {
            return $obj;
        }

        return explode($delimiter, strval($obj));
    }
}
