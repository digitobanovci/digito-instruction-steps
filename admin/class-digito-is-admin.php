<?php
class Digito_IS_Admin {

    public function __construct() {
        add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
        add_action( 'admin_init', array( $this, 'register_settings' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );
        add_action( 'add_meta_boxes', array( $this, 'add_metabox' ) ); // Dodaj hook za metabox
        add_action( 'save_post', array( $this, 'save_metabox' ) );     // Dodaj hook za čuvanje
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

    // METABOX FUNKCIJE UNUTAR KLASE
    public function add_metabox() {
        $options = get_option( 'digito_is_options' );
        $selected_cpts = isset( $options['cpt_select'] ) ? $options['cpt_select'] : array();

        if ( ! empty( $selected_cpts ) ) {
            foreach ( $selected_cpts as $cpt ) {
                add_meta_box(
                    'digito_is_metabox',
                    'Instruction Steps',
                    array( $this, 'render_metabox' ),
                    $cpt,
                    'normal',
                    'high'
                );
            }
        }
    }

    public function render_metabox( $post ) {
        wp_nonce_field( 'digito_is_save_meta', 'digito_is_nonce' );
        $steps = get_post_meta( $post->ID, '_digito_is_steps', true );
        $steps = $steps ? $steps : array();

        echo '<div id="digito-is-steps">';
        foreach ( $steps as $index => $step ) {
            $this->render_step_fields( $index, $step );
        }
        echo '</div>';
        echo '<button id="digito-is-add-step" class="button">Add Step</button>';
    }

    private function render_step_fields( $index, $step ) {
        $name = isset( $step['name'] ) ? $step['name'] : '';
        $description = isset( $step['description'] ) ? $step['description'] : '';
        $orientation = isset( $step['orientation'] ) ? $step['orientation'] : '';
        $selector = isset( $step['selector'] ) ? $step['selector'] : '';

        echo '<div class="digito-is-step">';
        echo '<h4>Step ' . ( $index + 1 ) . '</h4>';
        echo '<p><label>Step Name</label><br><input type="text" name="digito_is_steps[' . $index . '][name]" value="' . esc_attr( $name ) . '"></p>';
        echo '<p><label>Description</label><br><textarea name="digito_is_steps[' . $index . '][description]">' . esc_textarea( $description ) . '</textarea></p>';
        echo '<p><label>Instructor Orientation</label><br>';
        echo '<select name="digito_is_steps[' . $index . '][orientation]">';
        $orientations = array( 'top-left', 'top-center', 'top-right', 'bottom-left', 'bottom-center', 'bottom-right' );
        foreach ( $orientations as $ori ) {
            $selected = ( $orientation == $ori ) ? 'selected' : '';
            echo '<option value="' . $ori . '" ' . $selected . '>' . ucfirst( str_replace( '-', ' ', $ori ) ) . '</option>';
        }
        echo '</select></p>';
        echo '<p><label>Element Selector</label><br><input type="text" name="digito_is_steps[' . $index . '][selector]" value="' . esc_attr( $selector ) . '"></p>';
        echo '<button class="button digito-is-remove-step">Remove Step</button>';
        echo '</div>';
    }

    public function save_metabox( $post_id ) {
        if ( ! isset( $_POST['digito_is_nonce'] ) || ! wp_verify_nonce( $_POST['digito_is_nonce'], 'digito_is_save_meta' ) ) {
            return;
        }

        if ( isset( $_POST['digito_is_steps'] ) ) {
            $steps = array();
            foreach ( $_POST['digito_is_steps'] as $step ) {
                $steps[] = array(
                    'name' => sanitize_text_field( $step['name'] ),
                    'description' => sanitize_textarea_field( $step['description'] ),
                    'orientation' => sanitize_text_field( $step['orientation'] ),
                    'selector' => sanitize_text_field( $step['selector'] ),
                );
            }
            update_post_meta( $post_id, '_digito_is_steps', $steps );
        }
    }
}
