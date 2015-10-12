<?php

/**
 * Loads the image management javascript
 */
function wp_bootstrap_team_enqueue() {
    global $typenow;
    if( $typenow == 'team_member' ) {
        wp_enqueue_media();
 
        // Registers and enqueues the required javascript.
        wp_register_script( 'meta-box-image', plugin_dir_url( __FILE__ ) . 'js/admin.js', array( 'jquery' ) );
        wp_localize_script( 'meta-box-image', 'meta_image',
            array(
                'title' => __( 'Choose or Upload an Image', 'wp_bootstrap_team' ),
                'button' => __( 'Use this image', 'wp_bootstrap_team' ),
            )
        );
        wp_enqueue_script( 'meta-box-image' );
    }
}

add_action( 'admin_enqueue_scripts', 'wp_bootstrap_team_enqueue' );