<?php

class Interceptor
{

    public function __construct()
    {
        add_action('admin_init', array($this, 'intercept_post_request' ));
    }

    /* ------------------------------------------------------------------------ * 
    * Intercepect Send Email Bacheca Request To User
    * ------------------------------------------------------------------------ */
    function intercept_post_request()
    {
        // Controlla se stai inviando il tuo form specifico
        if (isset($_POST['dest_email']) && !empty($_POST['dest_email'])) {

            // Ottieni l'oggetto della pagina "Bacheca"
            $pagina_bacheca = get_page_by_path('bacheca');

            // Verifica che la pagina esista
            if ($pagina_bacheca) {

                // Valida e sanifica l'email inviata
                $sanitized_email = sanitize_email($_POST['dest_email']);

                // Genera un token di attivazione univoco
                $activation_code = sha1($sanitized_email . time());

                // Ottieni l'URL della pagina "Bacheca"
                $bacheca_url = get_permalink($pagina_bacheca->ID);

                // Crea il link di attivazione aggiungendo il token di attivazione come parametro query
                $activation_link = add_query_arg(array('key' => $activation_code), $bacheca_url);

                // Invia l'email di attivazione
                $this->send_email($sanitized_email, $activation_link);
            }
        }
    }

    function send_email($user_email, $activation_link)
    {
        $subject = 'Attiva il tuo account';
        $message = 'Clicca su questo link per attivare il tuo account: ' . $activation_link;
        $headers = 'From: Your Name <your-email@example.com>' . "\r\n";

        wp_mail($user_email, $subject, $message, $headers);
    }
}

?>