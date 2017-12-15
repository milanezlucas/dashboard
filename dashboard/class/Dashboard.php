<?php
class Dashboard
{
    
    function __construct()
    {
        $this->set_dashboard_formats();
    }

    // Scripts (css and javascript)
	private function set_dashboard_formats()
	{
		if ( is_admin() ) {
			wp_enqueue_style( 'jquery-ui-style', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.1/themes/smoothness/jquery-ui.css', true );
	        wp_enqueue_script( 'admin', MI_URL_THEME . '/dashboard/js/admin.js', array( 'jquery', 'jquery-ui-datepicker' ), '', true );

	        // Url Scripts
			wp_localize_script( 'admin', 'WPURLS', array(
		        'siteurl'   => MI_URL,
		        'urlajax'   => MI_URL_WP . '/wp-admin/admin-ajax.php',
		        'urltheme'  => MI_URL_THEME
		    ));
	    }
	}

    // Ajax Register
	public function register_ajax( $ajax )
	{
		$action = array();
		$action = array_merge( $action, $ajax );

	    for ( $i=0; $i < count( $action ); $i++ ) {
	        add_action( 'wp_ajax_' . $action[ $i ],        array( 'Ajax', 'send_' . $action[ $i ] ) );
	        add_action( 'wp_ajax_nopriv_' . $action[ $i ], array( 'Ajax', 'send_' . $action[ $i ] ) );
	    }
	}

	/*
	 * Lista todos os posts de um determinado Post Type
	 *
	 * @param [String] $post_type
	 * @return Array
	 */
	public function get_all_posts( $post_type )
	{
		$rs_post = new WP_Query( array(
			'post_type'			=> $post_type,
			'posts_per_page'	=> -1
		));
		$posts = array();
		while ( $rs_post->have_posts() ) {
			$rs_post->the_post();

			$posts[get_the_ID()] = get_the_title();
		}
		wp_reset_postdata();

		return $posts;
	}

	/**
	 * Carrega configurações do tema
	 *
	 * @return void
	 */
	public function mi_theme_setup()
	{
		add_filter( 'show_admin_bar', '__return_false' );
		
		add_post_type_support( 'page', 'excerpt' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'title-tag' );
			
		// Remove code head
		remove_action( 'wp_head',           'wp_generator' );
		remove_action( 'wp_head',           'rsd_link' );
		remove_action( 'wp_head',           'wlwmanifest_link' );
		remove_action( 'wp_head',           'index_rel_link' );
		remove_action( 'wp_head',           'start_post_rel_link', 10, 0 );
		remove_action( 'wp_head',           'adjacent_posts_rel_link', 10, 0 );
		remove_action( 'wp_head',           'parent_post_rel_link', 10, 0 );
		remove_action( 'wp_head',           'feed_links', 2 );
		remove_action( 'wp_head',           'feed_links_extra', 3 );
		remove_action( 'wp_head',           'print_emoji_detection_script', 7 );
		remove_action( 'wp_print_styles',   'print_emoji_styles' );

		// Shortcodes
		// add_action( 'init',         'unregister_shortcode' );
		// add_action( 'init',         'register_shortcode' );
		// add_action( 'admin_head',   array( 'Dashboard', 'editor_button' ) );
	}

	// Shortcodes editor
	public function editor_button()
	{
	    if ( get_user_option( 'rich_editing' ) == 'true' ) {
	        add_filter( 'mce_external_plugins', array( 'Dashboard', 'shortcode_tinymce_plugin' ) );
	        add_filter( 'mce_buttons_3',        'editor_register_shortcode' );
	    }
	}

	public function shortcode_tinymce_plugin( $buttons )
	{
	    $buttons[ 'editor_button' ] = MI_URL_THEME . '/js/shortcode.js';
	    return $buttons;
	}

	// Images Size
	public function img_sizes( $images )
	{
		global $post;
	    $post_type = '';
	    if ( isset( $_POST[ 'post_id' ] ) ) {
	        $post_type = get_post_type( $_POST[ 'post_id' ] );
	    } else if ( isset( $post ) && isset( $post->post_parent ) && ( $post->post_parent > 0 ) ) {
	        $post_type = get_post_type( $post->post_parent );
	    }

		$sizes = array( 'thumbnail', 'medium', 'large' );
	   	foreach ( $images as $name => $args ) {
	   		if ( $args[ 'local' ] == $post_type || $args[ 'local' ] == 'all' ) {
	   			array_push( $sizes, $name );
				add_image_size( $name, $args[ 'size' ][ 0 ], $args[ 'size' ][ 1 ], true );
	   		}
	   	}

	    return $this->filter_img_sizes( $sizes );
	}

	public function filter_img_sizes( $sizes )
	{
		return $sizes;
	}

	// Get Image Thumbnail
	public function get_thumbnail( $post_id, $size )
	{
		return $this->get_the_images( $post_id, 'thumbnail', $size );
	}

	// Get Image
	public function get_image( $img_id, $size )
	{
		$img_id = explode( ',', $img_id );
		for ( $i=0; $i < count( $img_id ); $i++ ) {
			if ( $img_id[ $i ] ) {
				return $this->get_the_images( $img_id[ $i ], 'image', $size );
			}
		}
	}

	private function get_the_images( $id, $type, $size )
	{
		$img_id = ( $type == 'thumbnail' ) ? get_post_thumbnail_id( $id ) : $id;

		$img_src 	= wp_get_attachment_image_src( $img_id, $size );
		$img_large 	= wp_get_attachment_image_src( $img_id, 'large' );

		@$image->src 	= $img_src[ 0 ];
		@$image->large 	= $img_large[ 0 ];
		@$image->width 	= $img_src[ 1 ];
		@$image->height = $img_src[ 2 ];
		@$image->title 	= get_the_title( $img_id );

		return $image;
	}
}
