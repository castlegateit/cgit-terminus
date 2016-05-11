<?php

namespace Terminus;

class Resource
{
    /**
     * Resource name
     *
     * The original string used to identify the resource, which might be a
     * registered resource name, a relative path to a resource, an absolute
     * path, or a URL.
     *
     * @var string
     */
    private $resource;

    /**
     * Resource identifier
     *
     * The unique, sanitized resource identifier used within WordPress, which
     * might be an existing registered script or style name.
     *
     * @var string
     */
    private $id;

    /**
     * Dependencies
     *
     * List of the original strings used to identify the resources or the
     * registered resource identifiers.
     *
     * @var array
     */
    private $deps = [];

    /**
     * Is the resource a script?
     *
     * @var bool
     */
    private $script = false;

    /**
     * Is the resource stored in the parent theme?
     *
     * @var bool
     */
    private $parent = false;

    /**
     * Names of functions
     *
     * The functions used to enqueue resources or to retrieve theme directory
     * details are different for different resources, so their names are stored
     * as properties here.
     *
     * @var array
     */
    private $functions = [
        'enqueue' => 'wp_enqueue_style',
        'theme' => 'get_stylesheet_directory',
        'theme_uri' => 'get_stylesheet_directory_uri',
    ];

    /**
     * Constructor
     *
     * Stores the original resource identifier and the sanitized,
     * WordPress-compatible resource identifier, then attempts to detect the
     * resource type.
     *
     * @return void
     */
    public function __construct($resource)
    {
        $this->resource = $resource;
        $this->id = self::sanitizeName($resource);

        $this->detectType();
    }

    /**
     * Sanitize resource name
     *
     * Remove non-alphanumeric characters from resource names for use as
     * WordPress-compatible resource identifiers.
     *
     * @param string $str
     * @return string
     */
    private static function sanitizeName($str)
    {
        return trim(preg_replace('/[^a-z0-9]+/i', '-', $str), '-');
    }

    /**
     * Sanitize list of resource names
     *
     * @param array $list
     * @return array
     */
    private static function sanitizeList($list)
    {
        $sanitized = [];

        foreach ($list as $str) {
            $sanitized[] = self::sanitizeName($str);
        }

        return $sanitized;
    }

    /**
     * Detect resource type
     *
     * A crude attempt to determine the resource type based on the file
     * extension: if it ends in ".js", it must be JavaScript :)
     *
     * @return void
     */
    private function detectType()
    {
        $this->setScript(substr($this->resource, -3) == '.js');
    }

    /**
     * Set dependencies
     *
     * The names of dependencies are sanitized in the same way as the original
     * resource identifier. Therefore, you should be able to specify either the
     * original names or the sanitized names in the list of dependencies.
     *
     * @param array $deps
     * @return void
     */
    public function setDependencies($deps)
    {
        $this->deps = self::sanitizeList($deps);
    }

    /**
     * Set resource type
     *
     * @param bool $script
     * @return void
     */
    public function setScript($script)
    {
        $this->script = $script ? true : false;

        if ($this->script) {
            $this->functions['enqueue'] = 'wp_enqueue_script';
        }
    }

    /**
     * Set resource path to be relative to parent theme
     *
     * @param bool $parent
     * @return void
     */
    public function setParent($parent)
    {
        $this->parent = $parent ? true : false;

        if ($this->parent) {
            $this->functions['theme'] = 'get_template_directory';
            $this->functions['theme_uri'] = 'get_template_directory_uri';
        }
    }

    /**
     * Enqueue resource
     *
     * Registered resources are enqueued from WordPress. Absolute paths and URLs
     * are enqueued unmodified. Relative paths are enqueued with a version
     * number based on the time the file was last modified.
     *
     * @return void
     */
    public function enqueue()
    {
        // Enqueue registered CSS
        if (wp_style_is($this->resource, 'registered')) {
            return wp_enqueue_style($this->resource);
        }

        // Enqueue registered JavaScript
        if (wp_script_is($this->resource, 'registered')) {
            return wp_enqueue_script($this->resource);
        }

        // Enqueue absolute resource
        if (
            substr($this->resource, 0, 1) == '/' ||
            filter_var($this->resource, FILTER_VALIDATE_URL)
        ) {
            return $this->functions['enqueue'](
                $this->id,
                $this->resource,
                $this->deps
            );
        }

        // Enqueue local resource with version number
        $path = $this->functions['theme']() . '/' . $this->resource;
        $uri = $this->functions['theme_uri']() . '/' . $this->resource;

        if (!file_exists($path)) {
            return trigger_error('Resource not found: ' . $this->resource);
        }

        $version = filemtime($path);
        $this->functions['enqueue']($this->id, $uri, $this->deps, $version);
    }
}
