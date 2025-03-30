<?php
class Digito_IS_Admin {

    public function add_settings_page() {
        add_options_page(
            __( 'Digito Instruction Steps Settings', 'digito-instruction-steps' ),
            __( 'Instruction Steps', 'digito-instruction-steps' ),
            'manage_options',
            'digito-is-settings',
            array( $this, 'render_settings_page' )
        );
    }

    public function render_settings_page() {
        echo '<div class="wrap">';
        echo '<h1>' . esc_html__( 'Digito Instruction Steps Settings', 'digito-instruction-steps' ) . '</h1>';
        echo '<p>Ovde će biti podešavanja za plugin.</p>';
        echo '</div>';
    }
}
