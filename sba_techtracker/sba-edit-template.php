<?php

/*
* Template Name: SBA Tech Tracker Level 3 Edit Page Template
*/

//Add my scripts to Wordpress
function sba_edit_scripts() {
	//My styles
	wp_register_style( 'sba_style', get_bloginfo('stylesheet_directory') . '/sba_techtracker/CSS/sba-style.css' );
	wp_enqueue_style( 'sba_style' );
	
	//My scripts
	wp_enqueue_script( 'sba-edit-scripts.js', get_bloginfo('stylesheet_directory') . '/sba_techtracker/JS/sba-edit-scripts.js', array( 'jquery' ), true);
	wp_localize_script( 'sba-edit-scripts.js', 'MyAjax', array( 
	'ajaxurl' => admin_url( 'admin-ajax.php' ),
	'security' => wp_create_nonce( 'sba-security-string' )
	));
}
add_action( 'wp_enqueue_scripts', 'sba_edit_scripts' );

check_logged_in();
set_time_zone();

get_header();

?>

<section class = 'content'>
	<?php //get_template_part('inc/page-title'); ?>
	
	<!-- FOR DEVELOPMENT -->
	<div id = 'test_div'></div>
	
	<div id = 'edit_content'></div>
	
<!-- /.content -->
</section>

<?php 

get_sidebar();
get_footer();

?>