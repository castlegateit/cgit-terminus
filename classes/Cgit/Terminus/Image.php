<?php

namespace Cgit\Terminus;

/**
 * Consistent access to image attachments
 *
 * This class provides a consistent way of accessing images attachments,
 * featured images, and ACF image fields.
 */
class Image
{
    /**
     * Image ID
     *
     * @var int
     */
    private $id;

    /**
     * Image meta data
     *
     * @var array
     */
    private $meta = [];

    /**
     * Default post ID
     *
     * @var int
     */
    private $defaultPostId = 0;

    /**
     * Constructor
     *
     * @param mixed $image
     * @param mixed $post
     * @return void
     */
    public function __construct($image = 0, $post = 0)
    {
        $this->setDefaultPostId();
        $this->set($image, $post);
    }

    /**
     * Set default post ID to current post
     *
     * If we are getting a featured image or a custom field and the post ID has
     * not been specified, this will be used instead.
     *
     * @return void
     */
    private function setDefaultPostId()
    {
        global $post;

        if (is_a($post, 'WP_Post')) {
            $this->defaultPostId = $post->ID;
        }
    }

    /**
     * Sanitize image attributes
     *
     * When provided with an array of key/value pairs representing HTML
     * attributes, this removes any that are not permitted in an HTML image
     * element.
     *
     * @param array $atts
     * @return array
     */
    private static function sanitizeAttributes($atts)
    {
        $permitted = ['alt', 'class', 'id', 'style', 'title'];

        foreach ($atts as $key => $value) {
            if (!in_array($key, $permitted) && strpos($key, 'data-') !== 0) {
                unset($atts[$key]);
            }
        }

        return $atts;
    }

    /**
     * Format HTML attributes
     *
     * Converts an associative array of attribute names and values into a string
     * of HTML attribute(s). Nested arrays are converted into space-separated
     * lists.
     *
     * @param array $atts
     * @return string
     */
    private static function formatAttributes($atts)
    {
        $items = [];

        foreach ($atts as $key => $value) {
            if (is_array($value)) {
                $value = implode(' ', $value);
            }

            $items[] = $key . '="' . $value . '"';
        }

        return implode(' ', $items);
    }

    /**
     * Set image meta data
     *
     * @return void
     */
    private function setImageMeta()
    {
        // Retrieve the raw post information from WordPress
        $obj = get_post($this->id);
        $obj_meta = get_post_meta($this->id);
        $obj_type = get_post_mime_type($this->id);
        $obj_file = $obj_meta['_wp_attached_file'][0];

        // Retrieve alt text if available
        $alt = '';

        if (isset($obj_meta['_wp_attachment_image_alt'])) {
            $alt = $obj_meta['_wp_attachment_image_alt'][0];
        }

        // Assign the relevant information to the instance
        $this->meta = [
            'url' => $this->url(),
            'file_name' => basename($obj_file),
            'file_path' => wp_upload_dir()['basedir'] . '/' . $obj_file,
            'mime_type' => $obj_type,
            'title' => $obj->post_title,
            'alt' => $alt,
            'caption' => $obj->post_excerpt,
            'description' => apply_filters('the_content', $obj->post_content),
        ];
    }

    /**
     * Set image ID
     *
     * Given a string, sets the image to an ACF custom field with that name.
     * Otherwise, checks whether the ID or object represents an image attachment
     * or something else and sets the image to the attachment or the featured
     * image respectively.
     *
     * @param mixed $image
     * @param mixed $post
     * @return void
     */
    public function set($image, $post = 0)
    {
        if (is_string($image)) {
            return $this->setField($image, $post);
        }

        if (get_post_type($image) == 'attachment') {
            return $this->setImage($image);
        }

        return $this->setPost($image);
    }

    /**
     * Set image ID based on image attachment
     *
     * @param mixed $image
     * @return void
     */
    public function setImage($image)
    {
        $this->id = get_post($image)->ID;
        $this->setImageMeta();
    }

    /**
     * Set image ID based on post featured image
     *
     * @param mixed $post
     * @return void
     */
    public function setPost($post = 0)
    {
        if (!$post) {
            $post = $this->defaultPostId;
        }

        $this->setImage(get_post_thumbnail_id($post));
    }

    /**
     * Set image ID based on ACF custom field
     *
     * @param string $field
     * @param int $post
     * @return void
     */
    public function setField($field, $post = 0)
    {
        if (!function_exists('get_field')) {
            return trigger_error('ACF not available');
        }

        $post = $post ?: $this->defaultPostId;
        $value = get_field($field, $post);

        // The return value of the custom field might be the image ID or it
        // might be an array of data that includes that image ID.
        if (is_array($value)) {
            $value = $value['id'];
        }

        $this->useImage($value);
    }

    /**
     * Return image URL
     *
     * @param string $size
     * @return string
     */
    public function url($size = 'full')
    {
        return wp_get_attachment_image_src($this->id, $size)[0];
    }

    /**
     * Return image meta
     *
     * @param string $field
     * @return mixed
     */
    public function meta($field = null)
    {
        if (is_null($field)) {
            return $this->meta;
        }

        return $this->meta[$field];
    }

    /**
     * Return HTML element
     *
     * Provided with a single size, this will create an HTML <img> element with
     * the specified attributes. Provided with an array of sizes, this will
     * create a responsive <picture> element.
     *
     * @param string $size
     * @param array $atts
     * @return string
     */
    public function element($size = 'full', $atts = [])
    {
        if (is_array($size)) {
            return $this->responsiveElement($size, $atts);
        }

        // Restrict the attributes to valid image attributes and set the image
        // src and alt attributes.
        $atts = self::sanitizeAttributes($atts);
        $atts['src'] = $this->url($size);

        if (!isset($atts['alt'])) {
            $atts['alt'] = $this->meta('alt');
        }

        // Put the required image attributes at the start of the list and
        // arrange the others in alphabetical order.
        ksort($atts);
        $atts = ['alt' => $atts['alt']] + $atts;
        $atts = ['src' => $atts['src']] + $atts;

        // Return the image element
        return '<img ' . self::formatAttributes($atts) . ' />';
    }

    /**
     * Return responsive HTML element
     *
     * Provided with an array of sizes, this will create a responsive <picture>
     * element with <source> elements for each size.
     *
     * @param array $sizes
     * @param array $atts
     * @return string
     */
    private function responsiveElement($sizes, $atts = [])
    {
        // List of source elements
        $sources = [];

        // Make sure that the alt text is in the array of image attributes, not
        // the array of picture element attributes.
        $picture_atts = array_diff_key($atts, ['alt' => 0]);
        $image_atts = [];

        if (isset($atts['alt'])) {
            $image_atts['alt'] = $atts['alt'];
        }

        ksort($picture_atts);

        // Assemble the list of source elements based on the sizes and media
        // queries submitted.
        foreach ($sizes as $size => $media) {
            $source_atts = [
                'srcset' => $this->url($size),
                'media' => $media,
            ];

            $sources[] = '<source ' . self::formatAttributes($source_atts)
                . ' />';
        }

        // Add an image element to the end of the list of sources, using the
        // last source size as the image size.
        $image_size = array_slice(array_keys($sizes), -1)[0];
        $sources[] = $this->element($image_size, $image_atts);

        // Assemble and return the HTML output
        return '<picture ' . self::formatAttributes($picture_atts) . '>'
            . implode(PHP_EOL, $sources) . '</picture>';
    }
}
