<?php
/*
Plugin Name: Bacheca
Author: bitpatrick
Version: 1.0
*/

require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
require_once('Interceptor.php');
require_once('Shortcoder.php');



class Bacheca {
    /** 
* Initializes the plugin. 
* 
* To keep the initialization fast, only add filter and action 
* hooks in the constructor. 
*/
    public function __construct() {
        register_activation_hook(__FILE__, 'crea_tabella_e_pagina_bacheca');
        register_deactivation_hook(__FILE__, 'elimina_tabella_bacheca_e_pagina');

        // add actions
        add_action('admin_menu', array($this, 'add_bacheca_menu'));
        add_action('admin_init', array($this, 'bacheca_initialize_theme_options'));
        
    }

    // Funzione per creare la tabella custom e aggiungere una pagina "Bacheca"
public function crea_tabella_e_pagina_bacheca()
{
    global $wpdb;

    // Crea tabella
    $tabella = $wpdb->prefix . 'bacheca_keys';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $tabella (
        `key` VARCHAR(255) NOT NULL,
        PRIMARY KEY (`key`)
    ) $charset_collate;";

    dbDelta($sql);

    // Aggiungi pagina "Bacheca"
    if (!get_page_by_path('bacheca')) {
        wp_insert_post([
            'post_title'    => 'Bacheca',
            'post_type'     => 'page',
            'post_name'     => 'bacheca',
            'post_status'   => 'publish',
        ]);
    }
}

// Funzione per eliminare la tabella custom e la pagina "Bacheca"
public function elimina_tabella_bacheca_e_pagina()
{
    global $wpdb;
    $tabella = $wpdb->prefix . 'bacheca_keys';

    // Elimina tabella
    $sql = "DROP TABLE IF EXISTS $tabella;";
    $wpdb->query($sql);

    // Elimina pagina "Bacheca"
    $pagina_bacheca = get_page_by_path('bacheca');
    if ($pagina_bacheca) {
        wp_delete_post($pagina_bacheca->ID, true);
    }
}

/* ------------------------------------------------------------------------ * 
* Setting Menu 
* ------------------------------------------------------------------------ */

/** 
 * Adds a new top-level menu to the bottom of the WordPress administration menu. 
 */
public function add_bacheca_menu()
{
    add_menu_page(
        'Bacheca Options',      // The title to be displayed on the corresponding page for this menu 
        'Bacheca',          // The text to be displayed for this actual menu item 
        'administrator',      // Which type of users can see this menu 
        'bacheca',          // The unique ID - that is, the slug - for this menu item 
        array($this, 'bacheca_menu_page_display'), // Funzione che mostra il contenuto della pagina, // The name of the function to call when rendering the menu for this page 
        ''
    );
}

/** 
 * Renders the basic display of the menu page for the theme. 
 */
public function bacheca_menu_page_display()
{

?>
    <div class="wrap">
        <h2>Bacheca</h2>
        <form method="POST" action="options.php">
            <?php
            settings_fields('bacheca-options'); // pass slug name of page, also referred to in Settings API as option group name
            do_settings_sections('bacheca-options');  // pass slug name of page
            submit_button('Send Email'); // submit button
            ?>
        </form>
    </div>

<?php

}

/* ------------------------------------------------------------------------ * 
* Setting Registration 
* ------------------------------------------------------------------------ */
public function bacheca_initialize_theme_options()
{
    // First, we register a section. This is necessary since all future options must belong to a 
    add_settings_section(
        'bacheca_settings_section',      // ID used to identify this section and with which to register options 
        'Bacheca Options',          // Title to be displayed on the administration page 
        array($this, 'bacheca_general_options_callback'),  // Callback used to render the description of the section 
        'bacheca-options'              // Page on which to add this section of options 
    );

    add_settings_field(
        'dest_email',            // ID used to identify the field throughout the theme 
        'Email',              // The label to the left of the option interface element 
        array($this, 'bacheca_callback'),  // The name of the function responsible for rendering the option interface 
        'bacheca-options',              // The page on which this option will be displayed 
        'bacheca_settings_section',      // The name of the section to which this field belongs 
        array(                // The array of arguments to pass to the callback. In this case, just a description. 
            'Send email'
        )
    );
}

/* ------------------------------------------------------------------------ * 
* Section Callbacks 
* ------------------------------------------------------------------------ */
public function bacheca_general_options_callback()
{
    echo '<p>bacheca_general_options_callback</p>';
}

/* ------------------------------------------------------------------------ * 
* Field Callbacks 
* ------------------------------------------------------------------------ */
public function bacheca_callback($args)
{

    // Here, we'll take the first argument of the array and add it to a label next to the checkbox 
    $html = '<label for="dest_email"> '  . $args[0] . '</label>';

    // Generate HTML for the email input field
    $html .= '<input type="email" id="dest_email" name="dest_email"/>';

    echo $html;
}
    
}
// Initialize the plugin 
$bacheca = new Bacheca();

// Initialize the Setting Request Interceptor
$interceptor = new Interceptor();

// Creare una nuova istanza della classe Shortcoder per registrare lo shortcode
$shortcoder = new Shortcoder();

?>