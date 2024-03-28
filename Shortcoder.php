<?php

class Shortcoder
{
    // Costruttore che registra lo shortcode
    public function __construct() {
        add_shortcode('bacheca', array($this, 'render_bacheca'));
    }

    /** 
     * A shortcode for rendering the login form. 
     * 
     * @param array $attributes Shortcode attributes. 
     * @param string $content The text content for shortcode. Not used. 
     * 
     * @return string The shortcode output 
     */
    public function render_bacheca($attributes, $content = null) {
        // Parse shortcode attributes 
        $default_attributes = array('show_title' => false);
        $attributes = shortcode_atts($default_attributes, $attributes);
        $show_title = $attributes['show_title'];

        // Logic for shortcode rendering here...

        // Render the login form using an external template 
        return $this->get_template_html('bacheca_form', $attributes);
    }

    /** 
     * Renders the contents of the given template to a string and returns it. 
     * 
     * @param string $template_name The name of the template to render (without .php) 
     * @param array $attributes The PHP variables for the template 
     * 
     * @return string The contents of the template. 
     */
    public function get_template_html($template_name, $attributes = null) {
        if (!$attributes) {
            $attributes = array();
        }
        ob_start();
        do_action('personalize_form_before_' . $template_name);
        require('templates/' . $template_name . '.php');
        do_action('personalize_form_after_' . $template_name);
        $html = ob_get_contents();
        ob_end_clean();
        return $html;
    }
}

?>

