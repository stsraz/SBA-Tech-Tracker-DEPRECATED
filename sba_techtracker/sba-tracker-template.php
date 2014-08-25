<?php

/*
* Template Name: SBA Tech Tracker Template
*/

// Check if user is logged in, if not redirect to the Wordpress login page
if (!is_user_logged_in()) {
	auth_redirect();
}

// Add my scripts to Wordpress
function sba_precheck_scripts() {
	// jQuery and jQuery UI
	wp_register_style( 'jq-ui-css', get_bloginfo('stylesheet_directory') . '/sba_techtracker/CSS/jquery-ui.css' );
	wp_enqueue_style( 'jq-ui-css' );
	
	wp_enqueue_script("jquery");
	wp_enqueue_script("jquery-form");
	wp_enqueue_script("jquery-ui-core");
	wp_enqueue_script("jquery-ui-widget");
	wp_enqueue_script("jquery-ui-tabs");
	wp_enqueue_script("jquery-ui-button");
	wp_enqueue_script("jquery-ui-progressbar");
	wp_enqueue_script("jquery-ui-datepicker");
	wp_enqueue_script("jquery-ui-dialog");
	wp_enqueue_script("jquery-effects-core");
	wp_enqueue_script("jquery-effects-explode");
	
	// Store.js
	wp_register_script('store-js', get_bloginfo('stylesheet_directory') . '/sba_techtracker/JS/store.min.js', true);
	
	// My XML
	wp_register_script('my-xml', get_bloginfo('stylesheet_directory') . '/sba_techtracker/PHP/sba_data.xml', true);
	
	// My styles
	wp_register_style( 'sba-style', get_bloginfo('stylesheet_directory') . '/sba_techtracker/CSS/sba-style.css' );
	wp_enqueue_style( 'sba-style' );
	
	// My scripts
	wp_enqueue_script( 'sba-scripts.js', get_bloginfo('stylesheet_directory') . '/sba_techtracker/JS/sba-scripts.js', array( 'jquery' ), true);
	wp_localize_script( 'sba-scripts.js', 'MyAjax', array( 
	'ajaxurl' => admin_url( 'admin-ajax.php' ),
	'security' => wp_create_nonce( 'sba-security-string' )
	));
}
add_action( 'wp_enqueue_scripts', 'sba_precheck_scripts' );

get_header();

?>

<section class = 'content'>
	<div id="test"></div>
	<div id="my_content"></div>
</section>

<?php 
get_sidebar();
get_footer();
?>