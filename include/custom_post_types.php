<?php

/**
* Add custom post types
*/
add_action( 'init', 'wp_bootstrap_team_create_post_type' );
add_action( 'add_meta_boxes', 'wp_bootstrap_team_metaboxes' );

function wp_bootstrap_team_create_post_type() {
    register_post_type( 'team_member',
		array(
			'labels' => array(
				'name' => __( 'Team' ),
				'singular_name' => __( 'Person' ),
				'add_new' => 'Add New',
				'add_new_item' => 'Add New Person',    		    
			),
    		'public' => true,
    		'has_archive' => false,
    		'show_ui' => true,
    		'supports' => array(
    		    'title','editor','thumbnail'
    	    ),
    	    'rewrite' => array(
    	        'slug' => 'team-members',
    	        'with_front' => false,
    	        'ep_mask' => EP_PAGES
    	    ),
    	    'capability_type' => 'page'
		)
	);
	
	register_taxonomy( 'team_department', array( 'team_member' ), array(
            'hierarchical'      => true,
    		'labels'            => array(
        		'name'              => __( 'Departments' ),
        		'singular_name'     => __( 'Department' ),        		
        		'add_new_item' => 'Add New Department',    		    
        	),
    		'show_ui'           => true,
    		'show_admin_column' => true,
    		'query_var'         => true,
    		'rewrite'           => array( 'slug' => 'team-departments' ),    	    
	    )
	);
}

function wp_bootstrap_team_metaboxes() {
    add_meta_box(
        'wp_bootstrap_team_member_options',
        __( 'Member Info', 'wp_bootstrap_team_textdomain' ),
        'wp_bootstrap_team_member_options_metabox_html',
        "team_member",
        "normal"
    );
}

function wp_bootstrap_team_member_options_metabox_html($post) {
    
    wp_nonce_field( 'wp_bootstrap_team_member_options', 'wp_bootstrap_team_member_options_metabox_nonce' );
    
    $title = get_post_meta( $post->ID, '_wp_bootstrap_team_title', true );      
    $phone = get_post_meta( $post->ID, '_wp_bootstrap_team_phone', true );      
    $email = get_post_meta( $post->ID, '_wp_bootstrap_team_email', true );      
    ?>
    <p>
    <label for="wp_bootstrap_team_title">Title</label>
    <input class="widefat" type="text" id="wp_bootstrap_team_title" name="wp_bootstrap_team_title" value="<?php echo esc_attr( $title ); ?>" />
    </p>
    <p>
    <label for="wp_bootstrap_team_phone">Phone</label>
    <input class="widefat" type="text" id="wp_bootstrap_team_phone" name="wp_bootstrap_team_phone" value="<?php echo esc_attr( $phone ); ?>" />
    </p>
    <p>
    <label for="wp_bootstrap_team_title">Email</label>
    <input class="widefat" type="text" id="wp_bootstrap_team_email" name="wp_bootstrap_team_email" value="<?php echo esc_attr( $email ); ?>" />
    </p>
    <?php		
}

/**
 * When the post is saved, saves our custom data.
 *
 * @param int $post_id The ID of the post being saved.
 */
function wp_bootstrap_team_save_postdata( $post_id ) {
    // Check if our nonce is set.
    
    if ( ! isset( $_POST['wp_bootstrap_team_member_options_metabox_nonce'] ) ) {
        return $post_id;
    }
    $nonce = $_POST['wp_bootstrap_team_member_options_metabox_nonce'];
    if ( ! wp_verify_nonce( $nonce, 'wp_bootstrap_team_member_options' ) ) {
        return $post_id;
    }

    // If this is an autosave, our form has not been submitted, so we don't want to do anything.
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return $post_id;
    }

    // Check the user's permissions.
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return $post_id;    
    }

    $title = sanitize_text_field( $_POST['wp_bootstrap_team_title'] );
    update_post_meta( $post_id, '_wp_bootstrap_team_title', $title );    
    $phone = sanitize_text_field( $_POST['wp_bootstrap_team_phone'] );
    update_post_meta( $post_id, '_wp_bootstrap_team_phone', $phone );    
    $email = sanitize_text_field( $_POST['wp_bootstrap_team_email'] );
    update_post_meta( $post_id, '_wp_bootstrap_team_email', $email );    
}
add_action( 'save_post', 'wp_bootstrap_team_save_postdata' );
