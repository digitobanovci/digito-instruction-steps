<?php
class Digito_IS_Public {

    public function enqueue_styles() {
        wp_enqueue_style( 'digito-is-public', plugin_dir_url( __FILE__ ) . '../assets/css/public.css', array(), DIGITO_IS_VERSION );
    }

    public function enqueue_scripts() {
        wp_enqueue_script( 'digito-is-public', plugin_dir_url( __FILE__ ) . '../assets/js/public.js', array( 'jquery' ), DIGITO_IS_VERSION, true );
    }
}
