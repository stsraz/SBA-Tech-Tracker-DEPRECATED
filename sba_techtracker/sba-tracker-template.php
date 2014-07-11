<?php

/*
* Template Name: SBA Tech Tracker Template
*/

//Add my scripts to Wordpress
function sba_tracker_scripts() {
	//JQuery Timer Scripts
	wp_register_style( 'jquery_timer', get_bloginfo('stylesheet_directory') . '/sba_techtracker/JQuery_Timer/jquery.countdown.css' );
	wp_enqueue_style( 'jquery_timer' );
	wp_enqueue_script( 'jquery.plugin.min.js', get_bloginfo('stylesheet_directory') . '/sba_techtracker/JQuery_Timer/jquery.plugin.min.js', array( 'jquery' ), true);
	wp_enqueue_script( 'jquery.countdown.min.js', get_bloginfo('stylesheet_directory') . '/sba_techtracker/JQuery_Timer/jquery.countdown.min.js', array( 'jquery' ), true);
	
	//My styles
	wp_register_style( 'sba_style', get_bloginfo('stylesheet_directory') . '/sba_techtracker/CSS/sba-style.css' );
	wp_enqueue_style( 'sba_style' );
	
	//My scripts
	wp_enqueue_script( 'sba-tracker-scripts.js', get_bloginfo('stylesheet_directory') . '/sba_techtracker/JS/sba-tracker-scripts.js', array( 'jquery' ), true);
	wp_localize_script( 'sba-tracker-scripts.js', 'MyAjax', array( 
	'ajaxurl' => admin_url( 'admin-ajax.php' ),
	'security' => wp_create_nonce( 'sba-security-string' )
	));
}
add_action( 'wp_enqueue_scripts', 'sba_tracker_scripts' );

check_logged_in();
set_time_zone();

//FOR DEVELOPMENT
session_start();
$_SESSION['step'] = 0;

get_header();

?>

<section class = 'content'>

	<?php get_template_part('inc/page-title'); ?>
	
	<!-- FOR DEVELOPMENT -->
	<div id = 'test_div'></div>
	
	<div id = 'tracker_content'></div>
	
<!-- /.content -->
</section>

<?php 

get_sidebar();
get_footer();

?>