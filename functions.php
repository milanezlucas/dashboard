<?php
define( 'MI_TEMPLATEPATH', 	get_template_directory() );
define( 'MI_PREFIX', 		'mi_' );
define( 'MI_URL',           get_home_url() );
define( 'MI_URL_WP',        get_site_url() );
define( 'MI_URL_THEME',     get_template_directory_uri() );
define( 'MI_SITE_NAME',     get_bloginfo( 'title' ) );
define( 'MI_SITE_EMAIL',    get_bloginfo( 'admin_email' ) );

// Custom Posts Name
define( 'MI_CPT_TEST', 'test' );

// Mailchimp
// define( 'MI_MC_API',    get_option( MI_PREFIX . 'mc_api' ) );
// define( 'MI_MC_DOUBLE', get_option( MI_PREFIX . 'mc_double' ) );
// define( 'MI_MC_SINGLE', get_option( MI_PREFIX . 'mc_single' ) );

// Framework
require_once( MI_TEMPLATEPATH . '/dashboard/class/Dashboard.php' );
require_once( MI_TEMPLATEPATH . '/dashboard/class/Form.php' );
require_once( MI_TEMPLATEPATH . '/dashboard/class/CustomPost.php' );

$dash = new Dashboard;

// Custom Posts
require_once( MI_TEMPLATEPATH . '/lib/CPTTest.php' );

// Start Custom Posts
new CPTTest;

add_action( 'after_setup_theme', array( 'Dashboard', 'mi_theme_setup' ) );