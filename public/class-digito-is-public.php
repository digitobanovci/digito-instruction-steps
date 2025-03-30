<?php
class Digito_IS_Public {

    public function __construct() {
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
        add_action( 'wp_footer', array( $this, 'render_instruction_steps' ) );
    }

    public function render_instruction_steps() {
        if ( is_singular() ) {
            global $post;
            $steps = get_post_meta( $post->ID, '_digito_is_steps', true );
            if ( ! empty( $steps ) ) {
                ?>
                <div id="digito-is-dialog" style="display:none;">
                    <div class="digito-is-content">
                        <img src="" class="digito-is-instructor" alt="Instructor">
                        <h3 class="digito-is-title"></h3>
                        <p class="digito-is-description"></p>
                        <button class="digito-is-prev">Previous</button>
                        <button class="digito-is-next">Next</button>
                        <button class="digito-is-finish">Finish</button>
                    </div>
                </div>
                <?php
            }
        }
    }

    public function enqueue_scripts() {
        wp_enqueue_style( 'digito-is-public', plugin_dir_url( __FILE__ ) . 'css/public.css', array(), '1.0' );
        wp_enqueue_script( 'digito-is-public', plugin_dir_url( __FILE__ ) . 'js/public.js', array( 'jquery' ), '1.0', true );
        if ( is_singular() ) {
            wp_localize_script( 'digito-is-public', 'digitoIS', array(
                'steps' => get_post_meta( get_the_ID(), '_digito_is_steps', true ),
                'images' => get_option( 'digito_is_options' )['instructor_images']
            ) );
        }
    }
}
