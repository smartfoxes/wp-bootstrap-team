<?php
  /*
    Plugin Name: WP Bootstrap Team Pages
    Plugin URI: https://github.com/smartfoxes/wp-bootstrap-team.git
    Description: Team management and shortcode for the bootstrap based themes
    Version: 0.9
    Author: Smart Foxes Inc
    Author URI: http://www.smartfoxes.ca
    License: MIT
  */

require_once dirname( __FILE__ ) . '/include/custom_post_types.php';
require_once dirname( __FILE__ ) . '/include/shortcodes.php';

add_image_size( 'team-member-photo', 300, 300, false );

if ( is_admin() ):
	require_once dirname( __FILE__ ) . '/admin.php';
endif;
