<?php
class Digito_Instruction_Steps {

    public function run() {
        $this->load_dependencies();
        $this->set_locale();
        $this->define_admin_hooks();
        $this->define_public_hooks();
    }

    private function load_dependencies() {
        require_once DIGITO_IS_PLUGIN_DIR . 'admin/class-digito-is-admin.php';
        require_once DIGITO_IS_PLUGIN_DIR . 'public/class-digito-is-public.php';
    }

    private function set_locale() {
        // Load translations
        load_plugin_textdomain( 'digito-instruction-steps', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
    }

    private function define_admin_hooks() {
        $admin = new Digito_IS_Admin();
        add_action( 'admin_menu', array( $admin, 'add_admin_menu' ) );
    }

    private function define_public_hooks() {
        $public = new Digito_IS_Public();
        add_action( 'wp_enqueue_scripts', array( $public, 'enqueue_scripts' ) ); // Uklonjen 'enqueue_styles'
    }
}
