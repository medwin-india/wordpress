<?php 
/*** Activate Theme ***/
function mymedi_theme_activation(){
	global $pagenow;
	if( is_admin() && 'themes.php' == $pagenow && isset($_GET['activated']) )
	{
		if( get_option( 'woocommerce_single_image_width' ) === false ){
			/* Single Image */
			update_option('woocommerce_single_image_width', 800);
			
			/* Thumbnail Image */
			update_option('woocommerce_thumbnail_image_width', 350);
			update_option('woocommerce_thumbnail_cropping', 'custom');
			update_option('woocommerce_thumbnail_cropping_custom_width', 350);
			update_option('woocommerce_thumbnail_cropping_custom_height', 350);
		}
		
		if( get_option( 'yith_woocompare_image_size' ) === false ){
			update_option( 'yith_woocompare_image_size', array( 'width' => '350', 'height' => '350', 'crop' => 1 ) );
		}
	}
}
add_action('admin_init', 'mymedi_theme_activation');

/*** Theme Setup ***/
function mymedi_theme_setup(){
	/* Add editor-style.css file*/
	add_editor_style();
	
	/* Add Theme Support */
	add_theme_support( 'post-formats', array( 'audio', 'gallery', 'quote', 'video' ) );		
	
	add_theme_support( 'post-thumbnails' );
	
	add_theme_support( 'automatic-feed-links' );
	
	add_theme_support( 'title-tag' );
	
	add_theme_support( 'custom-header' );
	
	$defaults = array(
		'default-color'         => ''
		,'default-image'        => ''
	);
	add_theme_support( 'custom-background', $defaults );
	
	add_theme_support( 'woocommerce' );
	
	add_theme_support( 'wc-product-gallery-slider' );
	
	if ( ! isset( $content_width ) ){ $content_width = 1200; }
	
	/* Translation */
	load_theme_textdomain( 'mymedi', get_template_directory() . '/languages' );
	
	/* Register Menu Location */
	register_nav_menus( array(
		'primary' 		=> esc_html__( 'Primary Navigation', 'mymedi' )
		,'mobile' 		=> esc_html__( 'Mobile Navigation', 'mymedi' )
		,'top_header' 	=> esc_html__( 'Top Header Navigation', 'mymedi' )
	) );
}
add_action( 'after_setup_theme', 'mymedi_theme_setup');

add_action('init', 'mymedi_support_wc_product_cloudzoom_lightbox', 20);
function mymedi_support_wc_product_cloudzoom_lightbox(){
	if( mymedi_get_theme_options('ts_prod_cloudzoom') ){
		add_theme_support( 'wc-product-gallery-zoom' );
	}
	if( mymedi_get_theme_options('ts_prod_lightbox') ){
		add_theme_support( 'wc-product-gallery-lightbox' );
	}
}

/*** Add Image Size ***/
function mymedi_add_image_size(){
	add_image_size('mymedi_menu_icon_thumb', (int) mymedi_get_theme_options('ts_menu_thumb_width'), (int) mymedi_get_theme_options('ts_menu_thumb_height'), true);
	
	add_image_size('mymedi_blog_thumb', 992, 525, true);
	
	add_image_size('mymedi_blog_thumb_shortcode', 496, 262, true);
	
	add_image_size('mymedi_product_cat', 310, 0, false);
}
add_action('init', 'mymedi_add_image_size');

add_filter('subcategory_archive_thumbnail_size', 'mymedi_product_categories_thumbnail_size');
function mymedi_product_categories_thumbnail_size(){
	return 'mymedi_product_cat';
}

/*** Get Theme Version ***/
function mymedi_get_theme_version(){
	$theme = wp_get_theme();
	if( $theme->parent() ){
		return $theme->parent()->get('Version');
	}
	else{
		return $theme->get('Version');
	}
}

/*** Register Front End Scripts  ***/
function mymedi_register_scripts(){
	$theme_version = mymedi_get_theme_version();
	wp_enqueue_style( 'font-awesome-5', get_template_directory_uri() . '/css/fontawesome.min.css', array(), $theme_version );
	
	wp_enqueue_style( 'mymedi-reset', get_template_directory_uri() . '/css/reset.css', array(), $theme_version );
	
	wp_enqueue_style( 'mymedi-style', get_stylesheet_uri(), array(), $theme_version );
	
	if( !get_option('ts_load_dynamic_style') ){
		wp_enqueue_style( 'mymedi-font-color', get_template_directory_uri() . '/css/font-color.css', array(), $theme_version );
	}
	
	if( mymedi_load_dokan_style() ){
		wp_enqueue_style( 'mymedi-dokan', get_template_directory_uri() . '/css/dokan.css', array(), $theme_version );
	}
	
	if( mymedi_get_theme_options('ts_responsive') ){
		wp_enqueue_style( 'mymedi-responsive', get_template_directory_uri() . '/css/responsive.css', array(), $theme_version );
	}
	
	wp_enqueue_style( 'owl-carousel', get_template_directory_uri() . '/css/owl.carousel.css', array(), $theme_version );
	
	if( !wp_style_is('woocommerce_prettyPhoto_css', 'enqueued') ){
		wp_enqueue_style( 'prettyphoto', get_template_directory_uri() . '/css/prettyPhoto.css', array(), $theme_version );
	}
	
	if( mymedi_get_theme_options('ts_enable_rtl') ){
		wp_enqueue_style( 'mymedi-rtl', get_template_directory_uri() . '/css/rtl.css', array(), $theme_version );
		if( mymedi_get_theme_options('ts_responsive') ){
			wp_enqueue_style( 'mymedi-rtl-responsive', get_template_directory_uri() . '/css/rtl-responsive.css', array(), $theme_version );
		}
	}
	
	if( mymedi_enable_loading_screen() ){
		wp_enqueue_script( 'mymedi-loading-screen', get_template_directory_uri() . '/js/loading-screen.js', array('jquery'), $theme_version, false );
		wp_localize_script( 'mymedi-loading-screen', 'ts_loading_screen_opt', array('loading_image' => mymedi_get_loading_screen_image()) );
	}
	
	wp_enqueue_script( 'jquery-throttle-debounce', get_template_directory_uri() . '/js/jquery.throttle-debounce.min.js', array('jquery'), $theme_version, true );
	
	wp_enqueue_script( 'owl-carousel', get_template_directory_uri() . '/js/owl.carousel.min.js', array(), $theme_version, true );
	
	wp_register_script( 'prettyphoto', get_template_directory_uri() . '/js/jquery.prettyPhoto.js', array(), $theme_version, true );
	
	wp_enqueue_script( 'mymedi-script', get_template_directory_uri() . '/js/main.js', array('jquery'), $theme_version, true );
	
	if( wp_is_mobile() && mymedi_get_theme_options('ts_add_to_cart_effect') == 'fly_to_cart' ){
		mymedi_change_theme_options('ts_add_to_cart_effect', '');
	}
	
	if( defined('ICL_LANGUAGE_CODE') ){
		$ajax_url = admin_url('admin-ajax.php?lang='.ICL_LANGUAGE_CODE, 'relative');
	}
	else{
		$ajax_url = admin_url('admin-ajax.php', 'relative');
	}
	
	$script_params = array(
		'ajax_url'					=> $ajax_url
		,'sticky_header'			=> (int)mymedi_get_theme_options('ts_enable_sticky_header')
		,'responsive'				=> (int)mymedi_get_theme_options('ts_responsive')
		,'ajax_search'				=> (int)mymedi_get_theme_options('ts_ajax_search')
		,'show_cart_after_adding'	=> (int)( mymedi_get_theme_options('ts_show_shopping_cart_after_adding') && mymedi_get_theme_options('ts_shopping_cart_sidebar') )
		,'ajax_add_to_cart'			=> (int)mymedi_get_theme_options('ts_prod_ajax_add_to_cart')
		,'add_to_cart_effect'		=> mymedi_get_theme_options('ts_add_to_cart_effect')
		,'shop_loading_type'		=> mymedi_get_theme_options('ts_prod_cat_loading_type')
		,'flexslider' 				=> apply_filters(
						'mymedi_quickshop_product_carousel_options',
						array(
							'rtl'            => is_rtl(),
							'animation'      => 'slide',
							'smoothHeight'   => true,
							'directionNav'   => false,
							'controlNav'     => 'thumbnails',
							'slideshow'      => false,
							'animationSpeed' => 500,
							'animationLoop'  => false, // Breaks photoswipe pagination if true.
							'allowOneSlide'  => false,
						)
					)
		,'zoom_options'				=> apply_filters( 'mymedi_quickshop_product_zoom_options', array() )
		,'product_name_min_height'	=> apply_filters( 'mymedi_product_name_min_height', 1 )
	);
	
	wp_localize_script( 'mymedi-script', 'mymedi_params', $script_params );
	
	if( is_singular('product') ){
		wp_enqueue_script( 'mymedi-single-product', get_template_directory_uri() . '/js/single-product.js', array('jquery'), $theme_version, true );
	}
	
	wp_register_script( 'threesixty', get_template_directory_uri() . '/js/threesixty.js', array(), $theme_version, true );
	
	if( !wp_is_mobile() && mymedi_get_theme_options('ts_smooth_scroll') ){
		wp_enqueue_script( 'smooth-scroll', get_template_directory_uri() . '/js/SmoothScroll.min.js', array(), $theme_version, true );
	}
	
	wp_register_script( 'jquery-mb-ytplayer', get_template_directory_uri() . '/js/jquery.mb.YTPlayer.min.js', array(), $theme_version, true );
	
	if( mymedi_get_theme_options('ts_enable_sticky_header') ){
		wp_enqueue_script( 'jquery-sticky', get_template_directory_uri() . '/js/jquery.sticky.js', array(), $theme_version, true );
	}
	
	if( ( is_tax( get_object_taxonomies( 'product' ) ) || is_post_type_archive('product') ) && mymedi_get_theme_options('ts_prod_cat_loading_type') != 'default' ){
		wp_enqueue_script( 'mymedi-shop-load-more', get_template_directory_uri() . '/js/shop-load-more.js', array(), $theme_version, true );
	}
	
	if( is_single() && get_option( 'thread_comments' ) ){ 	
		wp_enqueue_script( 'comment-reply' );
	}
	
	/* Load default google fonts */
	if( !class_exists('ReduxFramework') ){
		wp_enqueue_style( 'mymedi-google-fonts', '//fonts.googleapis.com/css?family=Jost:300,400,500,600,700' );
	}
	
	/* Custom JS */
	if( $custom_js = mymedi_get_theme_options('ts_custom_javascript_code') ){
		wp_add_inline_script( 'mymedi-script', stripslashes( trim( $custom_js ) ) );
	}
}
add_action('wp_enqueue_scripts', 'mymedi_register_scripts', 1000);

/* Loading Screen */
function mymedi_enable_loading_screen(){
	global $post;
	$theme_options = mymedi_get_theme_options();
	if( empty($theme_options['ts_loading_screen']) ){
		return false;
	}
	
	$enabled = false;
	
	$loading_screen_in = $theme_options['ts_display_loading_screen_in'];
	switch( $loading_screen_in ){
		case 'all-pages':
			if( is_page() ){
				$exclude_pages = !empty($theme_options['ts_loading_screen_exclude_pages'])?$theme_options['ts_loading_screen_exclude_pages']:array();
				if( isset($post->ID) && !in_array($post->ID, $exclude_pages) ){
					$enabled = true;
				}
			}
			else{
				$enabled = true;
			}
		break;
		case 'homepage-only':
			if( is_home() || is_front_page() ){
				$enabled = true;
			}
		break;
		case 'specific-pages':
			if( is_page() ){
				$specific_pages = !empty($theme_options['ts_loading_screen_specific_pages'])?$theme_options['ts_loading_screen_specific_pages']:array();
				if( isset($post->ID) && in_array($post->ID, $specific_pages) ){
					$enabled = true;
				}
			}
		break;
	}

	return apply_filters('mymedi_enable_loading_screen', $enabled);
}

function mymedi_get_loading_screen_image(){
	$theme_options = mymedi_get_theme_options();
	$loading_image = $theme_options['ts_custom_loading_image']['url'];
	if( !$loading_image ){
		$loading_image = get_template_directory_uri() . '/images/loading/loading_' . $theme_options['ts_loading_image'] . '.svg';
	}
	return $loading_image;
}

function mymedi_get_last_save_theme_options(){
	$transients = get_option('mymedi_theme_options-transients', array());
	if( isset($transients['last_save']) ){
		return $transients['last_save'];
	}
	return time();
}

function mymedi_register_custom_style(){
	$upload_dir = wp_upload_dir();
	$filename = trailingslashit($upload_dir['baseurl']) . strtolower(str_replace(' ', '', wp_get_theme()->get('Name'))) . '.css';
	if( is_ssl() ){
		$filename = str_replace('http://', 'https://', $filename);
	}
	$filename_dir = trailingslashit($upload_dir['basedir']) . strtolower(str_replace(' ', '', wp_get_theme()->get('Name'))) . '.css';

	$custom_css = mymedi_get_theme_options('ts_custom_css_code');
	if( file_exists( $filename_dir ) ){ 
		wp_enqueue_style( 'mymedi-dynamic-css', $filename, array(), mymedi_get_last_save_theme_options() );
		if( $custom_css ){
			wp_add_inline_style( 'mymedi-dynamic-css', $custom_css );
		}
	}
	else{
		ob_start();
		include_once get_template_directory() . '/framework/dynamic_style.php';
		$dynamic_css = ob_get_contents();
		ob_end_clean();
		wp_add_inline_style( 'mymedi-style', $dynamic_css );
		if( $custom_css ){
			wp_add_inline_style( 'mymedi-style', $custom_css );
		}
	}
}
add_action('wp_enqueue_scripts', 'mymedi_register_custom_style', 9999);

/* Add font style to compare popup - can not use wp_enqueue_scripts hook */
if( isset($_GET['action']) && $_GET['action'] == 'yith-woocompare-view-table' ){
	add_action('wp_print_styles', 'mymedi_add_font_style_to_compare_popup', 1000);
}
function mymedi_add_font_style_to_compare_popup(){
	wp_enqueue_style( 'redux-google-fonts-mymedi_theme_options' ); /* mymedi_theme_options is the variable/key of theme options, so it has to use _ */
	wp_enqueue_style( 'mymedi-reset' );
	wp_enqueue_style( 'mymedi-style' );
	wp_enqueue_style( 'font-awesome-5' );
	wp_enqueue_style( 'mymedi-font-color' );
	if( mymedi_get_theme_options('ts_enable_rtl') ){
		wp_enqueue_style( 'mymedi-rtl' );
	}
	wp_enqueue_style( 'mymedi-dynamic-css' );
}

/*** Register Back End Scripts ***/
function mymedi_register_admin_scripts(){
	$theme_version = mymedi_get_theme_version();
	
	wp_enqueue_media();
	
	wp_enqueue_style( 'font-awesome-5', get_template_directory_uri() . '/css/fontawesome.min.css', array(), $theme_version );
	
	wp_enqueue_style( 'mymedi-admin-style', get_template_directory_uri() . '/css/admin_style.css', array(), $theme_version );
	
	wp_enqueue_script( 'mymedi-admin-script', get_template_directory_uri() . '/js/admin_main.js', array('jquery'), $theme_version, true );
	
	$admin_texts = array(
		'select_images' 			=> esc_html__('Select Images', 'mymedi')
		,'use_images' 				=> esc_html__('Use images', 'mymedi')
		,'choose_an_image' 			=> esc_html__('Choose an image', 'mymedi')
		,'use_image' 				=> esc_html__('Use image', 'mymedi')
		,'delete_sidebar_confirm' 	=> esc_html__('Do you want to delete this sidebar?', 'mymedi')
		,'delete_sidebar_failed' 	=> esc_html__('Cant delete the sidebar. Please try again!', 'mymedi')
	);
	wp_localize_script('mymedi-admin-script', 'mymedi_admin_texts', $admin_texts);
}
add_action('admin_enqueue_scripts', 'mymedi_register_admin_scripts');

/*** Global Page Options ***/
if( !function_exists('mymedi_set_global_page_options') ){
	function mymedi_set_global_page_options( $page_id = 0 ){
		global $mymedi_page_options;
		$post_custom = get_post_custom( $page_id );
		if( !is_array($post_custom) ){
			$post_custom = array();
		}
		foreach( $post_custom as $key => $value ){
			if( isset($value[0]) ){
				$mymedi_page_options[$key] = $value[0];
			}
		}
		
		$default_options = array(
							'ts_layout_fullwidth'					=> 'default'
							,'ts_header_layout_fullwidth'			=> 1
							,'ts_main_content_layout_fullwidth'		=> 1
							,'ts_footer_layout_fullwidth'			=> 1
							,'ts_layout_style'						=> 'default'
							,'ts_page_layout'						=> '0-1-0'
							,'ts_left_sidebar'						=> ''
							,'ts_right_sidebar'						=> ''
							,'ts_header_layout'						=> 'default'
							,'ts_header_transparent'				=> 0
							,'ts_header_text_color'					=> 'default'
							,'ts_menu_id'							=> 0
							,'ts_breadcrumb_layout'					=> 'default'
							,'ts_breadcrumb_bg_parallax'			=> 'default'
							,'ts_bg_breadcrumbs'					=> ''
							,'ts_logo'								=> ''
							,'ts_logo_mobile'						=> ''
							,'ts_logo_sticky'						=> ''
							,'ts_show_breadcrumb'					=> 1
							,'ts_show_page_title'					=> 1
							,'ts_page_slider'						=> 0
							,'ts_page_slider_position'				=> 'before_main_content'
							,'ts_rev_slider'						=> 0
							,'ts_first_footer_area'					=> 0
							,'ts_second_footer_area'				=> 0
							);
		$mymedi_page_options = array_merge($default_options, (array) $mymedi_page_options);
		return $mymedi_page_options;
	}
}

if( !function_exists('mymedi_get_page_options') ){
	function mymedi_get_page_options( $key = '', $default = '' ){
		global $mymedi_page_options;
		if( !$key ){
			return $mymedi_page_options;
		}
		else if( isset($mymedi_page_options[$key]) ){
			return $mymedi_page_options[$key];
		}
		else{
			return $default;
		}
	}
}

/*** Vertical Menu Heading ***/
if( !function_exists ('mymedi_get_vertical_menu_heading') ){
	function mymedi_get_vertical_menu_heading(){
		$locations = get_nav_menu_locations();
		if( isset($locations['vertical']) ){
			$menu = wp_get_nav_menu_object($locations['vertical']);
			if( isset( $menu->name ) ){
				return $menu->name;
			}
		}
		return esc_html__('Shop by category', 'mymedi');
	}
}

/*** Get excerpt ***/
if( !function_exists ('mymedi_string_limit_words') ){
	function mymedi_string_limit_words($string, $word_limit){
		$words = explode(' ', $string, ($word_limit + 1));
		if( count($words) > $word_limit ){
			array_pop($words);
		}
		return implode(' ', $words);
	}
}

if( !function_exists ('mymedi_the_excerpt_max_words') ){
	function mymedi_the_excerpt_max_words( $word_limit = -1, $post = '', $strip_tags = true, $extra_str = '', $echo = true ) {
		if( $post ){
			$excerpt = mymedi_get_the_excerpt_by_id($post->ID);
		}
		else{
			$excerpt = get_the_excerpt();
		}
			
		if( !is_array($strip_tags) && $strip_tags ){
			$excerpt = wp_strip_all_tags($excerpt);
			$excerpt = strip_shortcodes($excerpt);
		}
		
		if( is_array($strip_tags) ){
			$excerpt = wp_kses($excerpt, $strip_tags); // allow, not strip
		}
			
		if( $word_limit != -1 ){
			$result = mymedi_string_limit_words($excerpt, $word_limit);
			if( $result != $excerpt ){
				$result .= $extra_str;
			}
		}	
		else{
			$result = $excerpt;
		}
			
		if( $echo ){
			echo do_shortcode($result);
		}
		return $result;
	}
}

if( !function_exists('mymedi_get_the_excerpt_by_id') ){
	function mymedi_get_the_excerpt_by_id( $post_id = 0 )
	{
		global $wpdb;
		$query = "SELECT post_excerpt, post_content FROM $wpdb->posts WHERE ID = %d LIMIT 1";
		$result = $wpdb->get_results( $wpdb->prepare($query, $post_id), ARRAY_A );
		if( $result[0]['post_excerpt'] ){
			return $result[0]['post_excerpt'];
		}
		else{
			$content = $result[0]['post_content'];
			if( false !== strpos( $content, '<!--nextpage-->' ) ){
				$pages = explode( '<!--nextpage-->', $content );
				return $pages[0];
			}
			return $content;
		}
	}
}

/* Get User Role */
if( !function_exists('mymedi_get_user_role') ){
	function mymedi_get_user_role( $user_id ){
		global $wpdb;
		$user = get_userdata( $user_id );
		$capabilities = $user->{$wpdb->prefix . 'capabilities'};
		if( empty($capabilities) ){
			return '';
		}
		if ( !isset( $wp_roles ) ){
			$wp_roles = new WP_Roles();
		}
		foreach ( $wp_roles->role_names as $role => $name ) {
			if ( array_key_exists( $role, $capabilities ) ) {
				return $role;
			}
		}
		return '';
	}
}

/*** Page Layout Columns Class ***/
if( !function_exists('mymedi_page_layout_columns_class') ){
	function mymedi_page_layout_columns_class($page_column, $left_sidebar_name = '', $right_sidebar_name = ''){
		$data = array();
		
		if( empty($page_column) ){
			$page_column = '0-1-0';
		}
		
		$layout_config = explode('-', $page_column);
		$left_sidebar = (int)$layout_config[0];
		$right_sidebar = (int)$layout_config[2];
		
		if( $left_sidebar_name && !is_active_sidebar( $left_sidebar_name ) ){
			$left_sidebar = 0;
		}
		
		if( $right_sidebar_name && !is_active_sidebar( $right_sidebar_name ) ){
			$right_sidebar = 0;
		}
		
		$main_class = ($left_sidebar + $right_sidebar) == 2 ?'ts-col-12':( ($left_sidebar + $right_sidebar) == 1 ?'ts-col-18':'ts-col-24' );			
		
		$data['left_sidebar'] = $left_sidebar;
		$data['right_sidebar'] = $right_sidebar;
		$data['main_class'] = $main_class;
		$data['left_sidebar_class'] = 'ts-col-6';
		$data['right_sidebar_class'] = 'ts-col-6';
		
		return $data;
	}
}

/*** Show Page Slider ***/
function mymedi_show_page_slider(){
	$page_options = mymedi_get_page_options();
	switch( $page_options['ts_page_slider'] ){
		case 'revslider':
			if( class_exists('RevSliderSlider') && $page_options['ts_rev_slider'] ){
				echo do_shortcode('[rev_slider alias="'.$page_options['ts_rev_slider'].'"]');
			}
		break;
		default:
		break;
	}
}

/*** Get Portfolio Page Info ***/
function mymedi_get_portfolio_page_info( $return_url = false ){
	$page_id = mymedi_get_theme_options('ts_portfolio_page');
	if( $page_id ){
		if( $return_url ){
			return get_permalink($page_id);
		}
		else{
			$page = get_post( $page_id );
			if( $page ){
				return array( 'title' => $page->post_title, 'url' => get_permalink($page_id) );
			}
		}
	}
	return '';
}

/*** Breadcrumbs ***/
if( !function_exists('mymedi_breadcrumbs') ){
	function mymedi_breadcrumbs(){
		$delimiter_char = '&#62;';
		if( class_exists('WooCommerce') ){
			if( function_exists('woocommerce_breadcrumb') && function_exists('is_woocommerce') && is_woocommerce() ){
				woocommerce_breadcrumb(array('wrap_before'=>'<div class="breadcrumbs"><div class="breadcrumbs-container">','delimiter'=>'<span class="brn_arrow">'.$delimiter_char.'</span>','wrap_after'=>'</div></div>'));
				return;
			}
		}

		$allowed_html = array(
			'a'		=> array('href' => array(), 'title' => array())
			,'span'	=> array('class' => array())
			,'div'	=> array('class' => array())
		);
		$output = '';

		$delimiter = '<span class="brn_arrow">'.$delimiter_char.'</span>';
		
		$ar_title = array(
					'home'			=> __('Home', 'mymedi')
					,'search' 		=> __('Search results for ', 'mymedi')
					,'404' 			=> __('Error 404', 'mymedi')
					,'tagged' 		=> __('Tagged ', 'mymedi')
					,'author' 		=> __('Articles posted by ', 'mymedi')
					,'page' 		=> __('Page', 'mymedi')
					);
					
		$before = '<span class="current">'; /* tag before the current crumb */
		$after = '</span>'; /* tag after the current crumb */
		global $wp_rewrite, $post;
		$rewriteUrl = $wp_rewrite->using_permalinks();
		if( !is_home() && !is_front_page() || is_paged() ){
			$output .= '<div class="breadcrumbs"><div class="breadcrumbs-container">';
	 
			$homeLink = esc_url( home_url('/') ); 
			$output .= '<a href="' . $homeLink . '">' . $ar_title['home'] . '</a> ' . $delimiter . ' ';
	 
			if( is_category() ){
				global $wp_query;
				$cat_obj = $wp_query->get_queried_object();
				$thisCat = $cat_obj->term_id;
				$thisCat = get_category($thisCat);
				$parentCat = get_category($thisCat->parent);
				if( $thisCat->parent != 0 ){ 
					$output .= get_category_parents($parentCat, true, ' ' . $delimiter . ' ');
				}
				$output .= $before . single_cat_title('', false) . $after;
			}
			elseif( is_search() ){
				$output .= $before . $ar_title['search'] . '"' . get_search_query() . '"' . $after;
			}elseif( is_day() ){
				$output .= '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' ';
				$output .= '<a href="' . get_month_link(get_the_time('Y'),get_the_time('m')) . '">' . get_the_time('F') . '</a> ' . $delimiter . ' ';
				$output .= $before . get_the_time('d') . $after;
			}elseif( is_month() ){
				$output .= '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' ';
				$output .= $before . get_the_time('F') . $after;
			}elseif( is_year() ){
				$output .= $before . get_the_time('Y') . $after;
			}elseif( is_single() && !is_attachment() ){
				if( get_post_type() != 'post' ){
					$post_type = get_post_type_object(get_post_type());
					$slug = $post_type->rewrite;
					$post_type_name = $post_type->labels->singular_name;
					if( is_singular('ts_portfolio') ){
						$portfolio_page_info = mymedi_get_portfolio_page_info();
						if( $portfolio_page_info ){
							$post_type_name = $portfolio_page_info['title'];
							$portfolio_url = $portfolio_page_info['url'];
						}
					}
					if( $rewriteUrl ){
						$output .= '<a href="' . (isset($portfolio_url)?$portfolio_url:$homeLink . $slug['slug'] . '/') . '">' . $post_type_name . '</a> ' . $delimiter . ' ';
					}else{
						$output .= '<a href="' . (isset($portfolio_url)?$portfolio_url:$homeLink . '?post_type=' . get_post_type()) . '">' . $post_type_name . '</a> ' . $delimiter . ' ';
					}
					$output .= $before . get_the_title() . $after;
			    }else{
					$cat = get_the_category(); $cat = $cat[0];
					$output .= get_category_parents($cat, true, ' ' . $delimiter . ' ');
					$output .= $before . get_the_title() . $after;
			    }
			}elseif( !is_single() && !is_page() && get_post_type() != 'post' && !is_404() ){
				$post_type = get_post_type_object(get_post_type());
				$slug = $post_type->rewrite;
				$post_type_name = $post_type->labels->singular_name;
			    if( isset($slug['slug']) && $slug['slug'] == 'portfolio' ){
					$portfolio_page_info = mymedi_get_portfolio_page_info();
					if( $portfolio_page_info ){
						$post_type_name = $portfolio_page_info['title'];
						$portfolio_url = $portfolio_page_info['url'];
					}
			    }
				if( is_tag() ){
					$output .= $before . $ar_title['tagged'] . '"' . single_tag_title('', false) . '"' . $after;
				}
				elseif( is_taxonomy_hierarchical(get_query_var('taxonomy')) ){
					if( $rewriteUrl ){
						$output .= '<a href="' . (isset($portfolio_url)?$portfolio_url:$homeLink . $slug['slug'] . '/') . '">' . $post_type_name . '</a> ' . $delimiter . ' ';
					}else{
						$output .= '<a href="' . (isset($portfolio_url)?$portfolio_url:$homeLink . '?post_type=' . get_post_type()) . '">' . $post_type_name . '</a> ' . $delimiter . ' ';
					}			
					
					$curTaxanomy = get_query_var('taxonomy');
					$curTerm = get_query_var( 'term' );
					$termNow = get_term_by( 'name', $curTerm, $curTaxanomy );
					$pushPrintArr = array();
					if( $termNow !== false ){
						while( (int)$termNow->parent != 0 ){
							$parentTerm = get_term((int)$termNow->parent,get_query_var('taxonomy'));
							array_push($pushPrintArr,'<a href="' . get_term_link((int)$parentTerm->term_id,$curTaxanomy) . '">' . $parentTerm->name . '</a> ' . $delimiter . ' ');
							$curTerm = $parentTerm->name;
							$termNow = get_term_by( 'name', $curTerm, $curTaxanomy );
						}
					}
					$pushPrintArr = array_reverse($pushPrintArr);
					array_push($pushPrintArr,$before  . get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) )->name  . $after);
					$output .= implode($pushPrintArr);
				}else{
					$output .= $before . $post_type_name . $after;
				}
			}elseif( is_attachment() ){
				if( (int)$post->post_parent > 0 ){
					$parent = get_post($post->post_parent);
					$cat = get_the_category($parent->ID);
					if( count($cat) > 0 ){
						$cat = $cat[0];
						$output .= get_category_parents($cat, true, ' ' . $delimiter . ' ');
					}
					$output .= '<a href="' . get_permalink($parent) . '">' . $parent->post_title . '</a> ' . $delimiter . ' ';
				}
				$output .= $before . get_the_title() . $after;
			}elseif( is_page() && !$post->post_parent ){
				$output .= $before . get_the_title() . $after;
			}elseif( is_page() && $post->post_parent ){
				$parent_id  = $post->post_parent;
				$breadcrumbs = array();
				while( $parent_id ){
					$page = get_post($parent_id);
					$breadcrumbs[] = '<a href="' . get_permalink($page->ID) . '">' . get_the_title($page->ID) . '</a>';
					$parent_id  = $page->post_parent;
			    }
				$breadcrumbs = array_reverse($breadcrumbs);
				foreach( $breadcrumbs as $crumb ){
					$output .= $crumb . ' ' . $delimiter . ' ';
				}
				$output .= $before . get_the_title() . $after;
			}elseif( is_tag() ){
				$output .= $before . $ar_title['tagged'] . '"' . single_tag_title('', false) . '"' . $after;
			}elseif( is_author() ){
				global $author;
				$userdata = get_userdata($author);
				$output .= $before . $ar_title['author'] . $userdata->display_name . $after;
			}elseif( is_404() ){
				$output .= $before . $ar_title['404'] . $after;
			}
			if( get_query_var('paged') || get_query_var('page') ){
				if( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() || is_page_template() ||  is_post_type_archive() || is_archive() ){ 
					$output .= $before .' ('; 
				}
				$output .= $ar_title['page'] . ' ' . ( get_query_var('paged')?get_query_var('paged'):get_query_var('page') );
				if( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() || is_page_template() ||  is_post_type_archive() || is_archive() ){ 
					$output .= ')'. $after; 
				}
			}
			$output .= '</div></div>';
	    }
		
		echo wp_kses($output, $allowed_html);
		
		wp_reset_postdata();
	}
}

if( !function_exists('mymedi_breadcrumbs_title') ){
	function mymedi_breadcrumbs_title( $show_breadcrumb = false, $show_page_title = false, $page_title = '', $extra_class_title = '' ){
		$theme_options = mymedi_get_theme_options();
		if( $show_breadcrumb || $show_page_title ){
			$breadcrumb_bg_option = is_array($theme_options['ts_bg_breadcrumbs'])?$theme_options['ts_bg_breadcrumbs']['url']:$theme_options['ts_bg_breadcrumbs'];
			$breadcrumb_bg = '';
			$classes = array();
			$classes[] = 'breadcrumb-title-wrapper breadcrumb-' . $theme_options['ts_breadcrumb_layout'];
			$classes[] = $show_breadcrumb?'':'no-breadcrumb';
			$classes[] = $show_page_title?'':'no-title';
			if( $theme_options['ts_enable_breadcrumb_background_image'] && $theme_options['ts_breadcrumb_layout'] == 'v3' ){
				if( $breadcrumb_bg_option == '' ){ /* No Override */
					$breadcrumb_bg = get_template_directory_uri() . '/images/bg_breadcrumb_'.$theme_options['ts_breadcrumb_layout'].'.jpg';
				}	
				else{
					$breadcrumb_bg = $breadcrumb_bg_option;
				}
			}
			
			$style = '';
			if( $breadcrumb_bg != '' ){
				$style = 'style="background-image: url('. esc_url($breadcrumb_bg) .')"';
				if( $theme_options['ts_breadcrumb_bg_parallax'] ){
					$classes[] = 'ts-breadcrumb-parallax';
				}
			}
			echo '<div class="'.esc_attr(implode(' ', array_filter($classes))).'" '.$style.'><div class="breadcrumb-content"><div class="breadcrumb-title">';
			
			if( $show_breadcrumb ){
				mymedi_breadcrumbs();
			}
			if( $show_page_title ){
				$count_html = '';
				if( is_tax('product_cat') ){
					$count_html = '<span class="count">('.wc_get_loop_prop( 'total', 0 ).')</span>';
				}
				
				if( function_exists('is_cart') && is_cart() ){
					$count_html = '<span class="count">('.WC()->cart->get_cart_contents_count().')</span>';
				}
				
				echo '<h1 class="heading-title page-title entry-title '.$extra_class_title.'">'.$page_title.$count_html.'</h1>';
			}
			
			echo '</div></div></div>';
		}
	}
}

add_filter('woocommerce_add_to_cart_fragments', 'mymedi_update_page_title_cart_number');
if( !function_exists('mymedi_update_page_title_cart_number') ){
	function mymedi_update_page_title_cart_number( $fragments ){
		$fragments['.woocommerce-cart .breadcrumb-title-wrapper .count'] = '<span class="count">('.WC()->cart->get_cart_contents_count().')</span>';
		return $fragments;
	}
}

/*** Pagination ***/
if( !function_exists('mymedi_pagination') ){
	function mymedi_pagination( $query = null ){
		global $wp_query;
		$max_num_pages = $wp_query->max_num_pages;
		$paged = $wp_query->get( 'paged' );
		if( $query != null ){
			$max_num_pages = $query->max_num_pages;
			$paged = $query->get( 'paged' );
		}
		if( !$paged ){
			$paged = 1;
		}
		?>
		<nav class="ts-pagination">
			<?php
			echo paginate_links( array(
				'base'         	=> esc_url_raw( str_replace( 999999999, '%#%', get_pagenum_link( 999999999, false ) ) )
				,'format'       => ''
				,'add_args'     => ''
				,'current'      => max( 1, $paged )
				,'total'        => $max_num_pages
				,'prev_text'    => '&larr;'
				,'next_text'    => '&rarr;'
				,'type'         => 'list'
				,'end_size'     => 3
				,'mid_size'     => 3
			) );
			?>
		</nav>
		<?php
	}
}

/*** Logo ***/
if( !function_exists('mymedi_theme_logo') ){
	function mymedi_theme_logo(){
		$theme_options = mymedi_get_theme_options();
		$logo_image = is_array($theme_options['ts_logo'])?$theme_options['ts_logo']['url']:$theme_options['ts_logo'];
		$logo_image_mobile = is_array($theme_options['ts_logo_mobile'])?$theme_options['ts_logo_mobile']['url']:$theme_options['ts_logo_mobile'];
		$logo_image_sticky = is_array($theme_options['ts_logo_sticky'])?$theme_options['ts_logo_sticky']['url']:$theme_options['ts_logo_sticky'];
		$logo_text = stripslashes($theme_options['ts_text_logo']);
		
		if( !$logo_image_mobile ){
			$logo_image_mobile = $logo_image;
		}
		if( !$logo_image_sticky ){
			$logo_image_sticky = $logo_image;
		}
		if( !$logo_text ){
			$logo_text = get_bloginfo('name');
		}
		?>
		<div class="logo">
			<a href="<?php echo esc_url( home_url('/') ); ?>">
			<!-- Main logo -->
			<?php if( $logo_image ): ?>
				<img src="<?php echo esc_url($logo_image); ?>" alt="<?php echo esc_attr($logo_text); ?>" title="<?php echo esc_attr($logo_text); ?>" class="normal-logo" />
			<?php endif; ?>
			
			<!-- Mobile logo -->
			<?php if( $logo_image_mobile ): ?>
				<img src="<?php echo esc_url($logo_image_mobile); ?>" alt="<?php echo esc_attr($logo_text); ?>" title="<?php echo esc_attr($logo_text); ?>" class="mobile-logo" />
			<?php endif; ?>
			
			<!-- Sticky logo -->
			<?php if( $logo_image_sticky ): ?>
				<img src="<?php echo esc_url($logo_image_sticky); ?>" alt="<?php echo esc_attr($logo_text); ?>" title="<?php echo esc_attr($logo_text); ?>" class="sticky-logo" />
			<?php endif; ?>
			
			<?php 
			if( !$logo_image ){
				echo esc_html($logo_text); 
			}
			?>
			</a>
		</div>
		<?php
	}
}

/*** Pingback URL ***/
add_action('wp_head', 'mymedi_pingback_header');
if( !function_exists('mymedi_pingback_header') ){
	function mymedi_pingback_header(){
		if( is_singular() && pings_open() ){
		?>
		<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
		<?php
		}
	}
}

/*** Favicon ***/
if( !function_exists('mymedi_theme_favicon') ){
	function mymedi_theme_favicon(){
		if( function_exists('wp_site_icon') && function_exists('has_site_icon') && has_site_icon() ){
			return;
		}
		$favicon_option = mymedi_get_theme_options('ts_favicon');
		$favicon = is_array($favicon_option)?$favicon_option['url']:$favicon_option;
		if( $favicon ):
		?>
			<link rel="shortcut icon" href="<?php echo esc_url($favicon); ?>" />
		<?php
		endif;
	}
}

/*** Header Template ***/
if( !function_exists('mymedi_get_header_template') ){
	function mymedi_get_header_template(){
		get_template_part('templates/headers/header', mymedi_get_theme_options('ts_header_layout'));
	}
}

/*** Top Header Menu ***/
if( !function_exists('mymedi_top_header_menu') ){
	function mymedi_top_header_menu(){
		if( has_nav_menu( 'top_header' ) ){
			do_action('mymedi_before_top_header_menu');
			wp_nav_menu( array( 'container' => 'nav', 'container_class' => 'top-header-menu', 'theme_location' => 'top_header' ) );
			do_action('mymedi_after_top_header_menu');
		}
	}
}

/*** Header Store Notice ***/
if( !function_exists('mymedi_header_store_notice') ){
	function mymedi_header_store_notice(){
		if( !isset($_COOKIE['ts_store_notice']) && $store_notice = mymedi_get_theme_options('ts_header_store_notice') ){
			echo '<div class="ts-store-notice"><div class="container">';
			echo do_shortcode( stripslashes( $store_notice ) );
			echo '<span class="close"></span>';
			echo '</div></div>';
		}
	}
}

/*** Header Contact Information ***/
if( !function_exists('mymedi_header_contact_information') ){
	function mymedi_header_contact_information(){
		if( $contact_info = mymedi_get_theme_options('ts_header_contact_information') ){
			echo do_shortcode( stripslashes( $contact_info ) );
		}
	}
}

/*** Header Delivery Message ***/
if( !function_exists('mymedi_header_delivery_message') ){
	function mymedi_header_delivery_message(){
		if( $delivery_message = mymedi_get_theme_options('ts_header_delivery_message') ){
			echo do_shortcode( stripslashes( $delivery_message ) );
		}
	}
}

if( !function_exists('mymedi_get_footer_content') ){
	function mymedi_get_footer_content( $footer_block_id = 0 ){
		global $post;
		$args = array(
			'post_type' 		=> 'ts_footer_block'
			,'posts_per_page' 	=> 1
			,'p' 				=> $footer_block_id
		);
		$posts = get_posts($args);
		if( is_array($posts) ){
			add_filter( 'the_content', 'do_shortcode', 11 ); /* Some plugins remove do_shortcode from the_content */
			foreach( $posts as $post ){
				setup_postdata($post);
				the_content();
			}
		}
		wp_reset_postdata();
	}
}

/* Ajax search */
add_action( 'wp_ajax_mymedi_ajax_search', 'mymedi_ajax_search' );
add_action( 'wp_ajax_nopriv_mymedi_ajax_search', 'mymedi_ajax_search' );
if( !function_exists('mymedi_ajax_search') ){
	function mymedi_ajax_search(){
		global $wpdb, $post;
		
		$search_for_product = class_exists('WooCommerce');
		if( $search_for_product ){
			$taxonomy = 'product_cat';
			$post_type = 'product';
		}
		else{
			$taxonomy = 'category';
			$post_type = 'post';
		}
		
		$num_result = (int)mymedi_get_theme_options('ts_ajax_search_number_result');
		$desc_limit_words = (int)mymedi_get_theme_options('ts_prod_cat_desc_words');
		
		$search_string = $_POST['search_string'];
		$category = isset($_POST['category'])? $_POST['category']: '';
		
		$args = array(
			'post_type'			=> $post_type
			,'post_status'		=> 'publish'
			,'s'				=> $search_string
			,'posts_per_page'	=> $num_result
			,'tax_query'		=> array()
		);
		
		if( $search_for_product ){
			$args['meta_query'] = WC()->query->get_meta_query();
			$args['tax_query'] = WC()->query->get_tax_query();
		}
		
		if( $category != '' ){
			$args['tax_query'][] = array(
					'taxonomy'  => $taxonomy
					,'terms'	=> $category
					,'field'	=> 'slug'
				);
		}
		
		$results = new WP_Query($args);
		
		if( $results->have_posts() ){
			$extra_class = '';
			if( isset($results->post_count, $results->found_posts) && $results->found_posts > $results->post_count ){
				$extra_class = 'has-view-all';
			}
			
			$html = '<ul class="product_list_widget '.$extra_class.'">';
			while( $results->have_posts() ){
				$results->the_post();
				$link = get_permalink($post->ID);
				
				$image = '';
				if( $post_type == 'product' ){
					$product = wc_get_product($post->ID);
					$image = $product->get_image();
				}
				else if( has_post_thumbnail($post->ID) ){
					$image = get_the_post_thumbnail($post->ID, 'thumbnail');
				}
				
				$html .= '<li>';
					$html .= '<div class="ts-wg-thumbnail">';
						$html .= '<a href="'.esc_url($link).'">'. $image .'</a>';
					$html .= '</div>';
					$html .= '<div class="ts-wg-meta">';
						$html .= '<a href="'.esc_url($link).'" class="title">'. mymedi_search_highlight_string($post->post_title, $search_string) .'</a>';
						$html .= '<div class="description">'. mymedi_the_excerpt_max_words($desc_limit_words, '', true, ' ...', false) .'</div>';
						if( $post_type == 'product' ){
							if( $price_html = $product->get_price_html() ){
								$html .= '<span class="price">'. $price_html .'</span>';
							}
						}
					$html .= '</div>';
				$html .= '</li>';
			}
			$html .= '</ul>';
			
			if( isset($results->post_count, $results->found_posts) && $results->found_posts > $results->post_count ){
				$view_all_text = sprintf( esc_html__('View all %d results', 'mymedi'), $results->found_posts );
				
				$html .= '<div class="view-all-wrapper">';
					$html .= '<a href="#">'. $view_all_text .'</a>';
				$html .= '</div>';
			}
			
			wp_reset_postdata();
			
			$return = array();
			$html = '<div class="search-content">'.$html.'</div>';
			$return['html'] = $html;
			$return['search_string'] = $search_string;
			die( json_encode($return) );
		}
		
		$return = array();
		$return['html'] = '<p>'.esc_html__('No products were found', 'mymedi').'</p>';
		$return['search_string'] = $search_string;
		die( json_encode($return) );
	}
}

if( !function_exists('mymedi_search_highlight_string') ){
	function mymedi_search_highlight_string($string, $search_string){
		$new_string = '';
		$pos_left = stripos($string, $search_string);
		if( $pos_left !== false ){
			$pos_right = $pos_left + strlen($search_string);
			$new_string_right = substr($string, $pos_right);
			$search_string_insensitive = substr($string, $pos_left, strlen($search_string));
			$new_string_left = stristr($string, $search_string, true);
			$new_string = $new_string_left . '<span class="hightlight">' . $search_string_insensitive . '</span>' . $new_string_right;
		}
		else{
			$new_string = $string;
		}
		return $new_string;
	}
}

/* Get post comment count */
if( !function_exists('mymedi_get_post_comment_count') ){
	function mymedi_get_post_comment_count( $post_id = 0 ){
		global $post;
		if( !$post_id ){
			$post_id = $post->ID;
		}
		
		$comments_count = wp_count_comments($post_id); 
		return $comments_count->approved;
	}
}

/* Match with ajax search results */
add_filter('woocommerce_get_catalog_ordering_args', 'mymedi_woocommerce_get_catalog_ordering_args_filter');
if( !function_exists('mymedi_woocommerce_get_catalog_ordering_args_filter') ){
	function mymedi_woocommerce_get_catalog_ordering_args_filter( $args ){
		if( is_search() && !isset($_GET['orderby']) && get_option( 'woocommerce_default_catalog_orderby' ) == 'menu_order' 
			&& mymedi_get_theme_options('ts_ajax_search') ){
			$args['orderby'] = '';
			$args['order'] = '';
		}
		return $args;
	}
}

if( !function_exists('mymedi_get_mailchimp_forms') ){
	function mymedi_get_mailchimp_forms(){
		$args = array(
			'post_type'			=> 'mc4wp-form'
			,'post_status'		=> 'publish'
			,'posts_per_page'	=> -1
		);
		$results = array();
		$forms = new WP_Query( $args );
		if( !empty( $forms->posts ) && is_array( $forms->posts ) ){
			foreach( $forms->posts as $p ){
				$results[] = array(
					'id'		=> $p->ID
					,'title'	=> $p->post_title
				);
			}
		}
		
		return $results;
	}
}

/* Add to cart popup */
add_action('wp_footer', 'mymedi_add_to_cart_popup_modal');
function mymedi_add_to_cart_popup_modal(){
	if( mymedi_get_theme_options('ts_add_to_cart_effect') == 'show_popup' ){
	?>
	<div id="ts-add-to-cart-popup-modal" class="ts-popup-modal">
		<div class="overlay"></div>
		<div class="add-to-cart-popup-container popup-container">
			<span class="close"></span>
			<div class="add-to-cart-popup-content"></div>
		</div>
	</div>
	<?php
	}
}

add_action('wp_ajax_mymedi_load_product_added_to_cart', 'mymedi_load_product_added_to_cart' );
add_action('wp_ajax_nopriv_mymedi_load_product_added_to_cart', 'mymedi_load_product_added_to_cart' );
function mymedi_load_product_added_to_cart(){
	if( isset($_POST['product_id']) ){
		$product_id = absint($_POST['product_id']);
		$product = wc_get_product( $product_id );
		if( !is_object($product) ){
			die( esc_html__('Invalid Product', 'mymedi') );
		}
		ob_start();
		?>
		<div class="heading">
			<h6><?php esc_html_e('Product is added to cart', 'mymedi'); ?></h6>
		</div>
		<div class="item">
			<div class="product-image">
				<?php echo wp_kses($product->get_image(), 'mymedi_product_image'); ?>
			</div>
			<div class="product-meta">
				<h3 class="heading-title product-name"><a href="<?php echo esc_url( $product->get_permalink() ); ?>" class="product-name">
					<?php echo esc_html( $product->get_title() ); ?>
				</a></h3>
				<span class="price"><?php echo wp_kses($product->get_price_html(), 'mymedi_product_price'); ?></span>
			</div>
		</div>
		<div class="action">
			<a href="<?php echo wc_get_cart_url(); ?>" class="button view-cart"><?php esc_html_e('View Cart', 'mymedi'); ?></a>
			<a href="<?php echo wc_get_checkout_url(); ?>" class="button checkout"><?php esc_html_e('Checkout', 'mymedi'); ?></a>
		</div>
		<?php
		die( ob_get_clean() );
	}
}

/* Single product - Ajax add to cart message */
add_action('wp_footer', 'mymedi_ajax_add_to_cart_message');
function mymedi_ajax_add_to_cart_message(){
	if( mymedi_get_theme_options('ts_prod_ajax_add_to_cart') ){
	?>
		<div id="ts-ajax-add-to-cart-message">
			<span><?php esc_html_e('Product has been added to your cart', 'mymedi'); ?></span>
			<span class="error-message"></span>
		</div>
	<?php
	}
}

/* Support Dokan */
function mymedi_load_dokan_style(){
	if( !class_exists('WeDevs_Dokan') ){
		return false;
	}
	if( ( function_exists('dokan_is_store_page') && dokan_is_store_page() ) 
		|| ( function_exists('dokan_is_product_edit_page') && dokan_is_product_edit_page() )
		|| ( function_exists('dokan_is_seller_dashboard') && dokan_is_seller_dashboard() )
		|| ( function_exists('dokan_is_store_review_page') && dokan_is_store_review_page() )
		|| ( function_exists('dokan_is_store_listing') && dokan_is_store_listing() )
		|| apply_filters( 'mymedi_forced_load_dokan_style', false ) ){
		return true;	
	}
	return false;
}

add_action('dokan_dashboard_wrap_before', 'mymedi_dokan_dashboard_wrap_before', 10, 2);
add_action('dokan_edit_product_wrap_before', 'mymedi_dokan_dashboard_wrap_before', 10, 2);
function mymedi_dokan_dashboard_wrap_before( $post, $post_id ){
	if( isset( $_GET['product_id'] ) ){
		return;
	}
	mymedi_breadcrumbs_title(true, true, get_the_title());
	?>
	<div class="page-container show_breadcrumb_<?php echo mymedi_get_theme_options('ts_breadcrumb_layout') ?>">
		<div id="main-content" class="ts-col-24">
	<?php
}

add_action('dokan_dashboard_wrap_after', 'mymedi_dokan_dashboard_wrap_after', 10, 2);
add_action('dokan_edit_product_wrap_after', 'mymedi_dokan_dashboard_wrap_after', 10, 2);
function mymedi_dokan_dashboard_wrap_after( $post, $post_id ){
	if( isset( $_GET['product_id'] ) ){
		return;
	}
	?>
		</div>
	</div>
	<?php
}

/* Install Required Plugins */
add_action( 'tgmpa_register', 'mymedi_register_required_plugins' );
function mymedi_register_required_plugins(){
	$plugin_dir_path = get_template_directory() . '/framework/plugins/';
    $plugins = array(

        array(
            'name'                => 'ThemeSky'
            ,'slug'               => 'themesky'
            ,'source'             => $plugin_dir_path . 'themesky.zip'
            ,'required'           => true
            ,'version'            => '1.1.0'
            ,'external_url'       => ''
        )
		,array(
            'name'                => 'One Click Demo Import'
            ,'slug'               => 'one-click-demo-import'
			,'source'             => 'https://downloads.wordpress.org/plugin/one-click-demo-import.2.6.1.zip'
            ,'required'           => false
			,'version'            => '2.6.1'
            ,'external_url'       => ''
        )
		,array(
            'name'                => 'Redux Framework'
            ,'slug'               => 'redux-framework'
			,'source'             => 'https://downloads.wordpress.org/plugin/redux-framework.4.1.24.zip'
            ,'required'           => true
			,'version'            => '4.1.24'
            ,'external_url'       => ''
        )
		,array(
            'name'                => 'WooCommerce'
            ,'slug'               => 'woocommerce'
			,'source'             => 'https://downloads.wordpress.org/plugin/woocommerce.4.8.0.zip'
            ,'required'           => true
			,'version'            => '4.8.0'
            ,'external_url'       => ''
        )
		,array(
            'name'                => 'WPBakery Page Builder'
            ,'slug'               => 'js_composer'
            ,'source'             => $plugin_dir_path . 'js_composer.zip'
            ,'required'           => true
            ,'version'            => '6.5.0'
            ,'external_url'       => ''
        )
		,array(
            'name'                => 'Slider Revolution'
            ,'slug'               => 'revslider'
            ,'source'             => $plugin_dir_path . 'revslider.zip'
            ,'required'           => false
            ,'version'            => '6.3.3'
            ,'external_url'       => ''
        )
		,array(
            'name'                => 'Contact Form 7'
            ,'slug'               => 'contact-form-7'
			,'source'             => 'https://downloads.wordpress.org/plugin/contact-form-7.5.3.1.zip'
            ,'required'           => false
			,'version'            => '5.3.1'
            ,'external_url'       => ''
        )
		,array(
            'name'                => 'MailChimp for WordPress'
            ,'slug'               => 'mailchimp-for-wp'
			,'source'             => 'https://downloads.wordpress.org/plugin/mailchimp-for-wp.4.8.1.zip'
            ,'required'           => false
			,'version'            => '4.8.1'
            ,'external_url'       => ''
        )
		,array(
            'name'                => 'YITH WooCommerce Wishlist'
            ,'slug'               => 'yith-woocommerce-wishlist'
			,'source'             => 'https://downloads.wordpress.org/plugin/yith-woocommerce-wishlist.3.0.17.zip'
            ,'required'           => false
			,'version'            => '3.0.17'
            ,'external_url'       => ''
        )
		,array(
            'name'                => 'YITH WooCommerce Compare'
            ,'slug'               => 'yith-woocommerce-compare'
			,'source'             => 'https://downloads.wordpress.org/plugin/yith-woocommerce-compare.2.4.3.zip'
            ,'required'           => false
			,'version'            => '2.4.3'
            ,'external_url'       => ''
        )
    );

    $config = array(
		'id'           	=> 'tgmpa'
		,'default_path' => ''
		,'menu'         => 'tgmpa-install-plugins'
		,'parent_slug'  => 'themes.php'
		,'capability'   => 'edit_theme_options'
		,'has_notices'  => true
		,'dismissable'  => true
		,'dismiss_msg'  => ''
		,'is_automatic' => false
		,'message'      => ''
	);

    tgmpa( $plugins, $config );
}
?>