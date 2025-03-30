<?php
class Digito_IS_Admin {

    public function __construct() {
        add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
        add_action( 'admin_init', array( $this, 'register_settings' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );
    }

    public function add_admin_menu() {
        add_options_page(
            'Digito Instruction Steps Settings',
            'Instruction Steps',
            'manage_options',
            'digito-is-settings',
            array( $this, 'render_settings_page' )
        );
    }

    public function register_settings() {
        register_setting( 'digito_is_settings_group', 'digito_is_options', array( $this, 'sanitize_options' ) );

        add_settings_section(
            'digito_is_general_section',
            'General Settings',
            null,
            'digito-is-settings'
        );

        add_settings_field(
            'digito_is_cpt_select',
            'Select Post Types',
            array( $this, 'render_cpt_select' ),
            'digito-is-settings',
            'digito_is_general_section'
        );

        add_settings_field(
            'digito_is_instructor_images',
            'Instructor Images',
            array( $this, 'render_instructor_images' ),
            'digito-is-settings',
            'digito_is_general_section'
        );
    }

    public function render_settings_page() {
        ?>
        <div class="wrap">
            <h1>Digito Instruction Steps Settings</h1>
            <form method="post" action="options.php">
                <?php
                settings_fields( 'digito_is_settings_group' );
                do_settings_sections( 'digito-is-settings' );
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }

    public function render_cpt_select() {
        $options = get_option( 'digito_is_options' );
        $selected_cpts = isset( $options['cpt_select'] ) ? (array) $options['cpt_select'] : array();
        $post_types = get_post_types( array( 'public' => true ), 'objects' );

        foreach ( $post_types as $post_type ) {
            $checked = in_array( $post_type->name, $selected_cpts ) ? 'checked' : '';
            echo '<label><input type="checkbox" name="digito_is_options[cpt_select][]" value="' . esc_attr( $post_type->name ) . '" ' . $checked . '> ' . esc_html( $post_type->label ) . '</label><br>';
        }
    }

    public function render_instructor_images() {
        $options = get_option( 'digito_is_options' );
        $images = isset( $options['instructor_images'] ) ? (array) $options['instructor_images'] : array_fill( 0, 6, '' );

        for ( $i = 1; $i <= 6; $i++ ) {
            $image_url = $images[ $i - 1 ];
            echo '<div>';
            echo '<label>Image ' . $i . '</label><br>';
            echo '<input type="text" name="digito_is_options[instructor_images][]" value="' . esc_url( $image_url ) . '" class="instructor-image-url" readonly>';
            echo '<button class="button upload-image-button">Upload Image</button>';
            echo '</div>';
        }
    }

    public function sanitize_options( $input ) {
        $sanitized_input = array();
        if ( isset( $input['cpt_select'] ) ) {
            $sanitized_input['cpt_select'] = array_map( 'sanitize_text_field', $input['cpt_select'] );
        }
        if ( isset( $input['instructor_images'] ) ) {
            $sanitized_input['instructor_images'] = array_map( 'esc_url_raw', $input['instructor_images'] );
        }
        return $sanitized_input;
    }

    public function enqueue_admin_scripts() {
        wp_enqueue_media();
        wp_enqueue_script( 'digito-is-admin', plugin_dir_url( __FILE__ ) . 'js/admin.js', array( 'jquery' ), '1.0', true );
    }
}
