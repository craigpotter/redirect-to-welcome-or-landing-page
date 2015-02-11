<?php
/**
 * Plugin Name: Redirect wordpress to welcome or landing page.
 * Plugin URI: http://eslam.me
 * Description: Easy simple to the point plug-in allow you to set page so users get redirected to it if they landed on your home page or any page or post
 * Version: 1.0
 * Author: Eslam Mahmoud
 * Author URI: http://eslam.me
 * License: GPL2
 */

//Blocking direct access to the plugin
defined('ABSPATH') or die("No script kiddies please!");

//check if the function was not already defined
if( !function_exists("eslam_me_wordpress_redirect_to_landing_page")){
	function eslam_me_wordpress_redirect_to_landing_page(){
		if(isset($_COOKIE['eslam_me_wordpress_redirect_to_landing_page_url_visited']) && $_COOKIE['eslam_me_wordpress_redirect_to_landing_page_url_visited']){
			//Do nothing
		}
		else{
			$eslam_me_wordpress_redirect_to_landing_page_url = get_option('eslam_me_wordpress_redirect_to_landing_page_url', false);
			$eslam_me_wordpress_redirect_to_landing_page_for_all_pages = get_option('eslam_me_wordpress_redirect_to_landing_page_for_all_pages', false);
			if($eslam_me_wordpress_redirect_to_landing_page_url && $eslam_me_wordpress_redirect_to_landing_page_for_all_pages){
				if( $eslam_me_wordpress_redirect_to_landing_page_for_all_pages == 'all' || ($eslam_me_wordpress_redirect_to_landing_page_for_all_pages == 'home' && is_front_page()) ) {
					setcookie('eslam_me_wordpress_redirect_to_landing_page_url_visited', true);
					header("Location: ". $eslam_me_wordpress_redirect_to_landing_page_url);
					die();
				}
			}
		}
	}

	//add our function to the hook
	add_action('wp', 'eslam_me_wordpress_redirect_to_landing_page');
}

/** Step 2 (from text above). */
add_action( 'admin_menu', 'eslam_me_wordpress_redirect_to_landing_page_menu' );

/** Step 1. */
function eslam_me_wordpress_redirect_to_landing_page_menu() {
	add_options_page( 'Redirect to landing page plug-in', 'Redirect to landing page', 'manage_options', 'eslam_me_wordpress_redirect_to_landing_page', 'eslam_me_wordpress_redirect_to_landing_page_options' );
}

/** Step 3. */
function eslam_me_wordpress_redirect_to_landing_page_options() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
echo '<h1>Redirect wordpress to welcome/landing page.</h1>
<p>Easy simple to the point plug-in allow you to set page so users get redirected to it if they landed on your home page or any page or post</p>';

	if(isset($_POST['landing_page_url']) && $_POST['landing_page_url']){
		if(get_option('eslam_me_wordpress_redirect_to_landing_page_url', false)) {
			update_option( 'eslam_me_wordpress_redirect_to_landing_page_url', $_POST['landing_page_url']);
		}else{
			add_option('eslam_me_wordpress_redirect_to_landing_page_url', $_POST['landing_page_url'], '', 'yes' );
		}

		//TODO: Save the current time stamp and then check on it with cookie creation date
		echo '<p>URL Saved</p>';
	}

	if(isset($_POST['all_pages'])){
		if(get_option( 'eslam_me_wordpress_redirect_to_landing_page_for_all_pages', false)){
			update_option( 'eslam_me_wordpress_redirect_to_landing_page_for_all_pages', $_POST['all_pages']);
		}else{
			add_option( 'eslam_me_wordpress_redirect_to_landing_page_for_all_pages', $_POST['all_pages'], '', 'yes' );
		}
		echo '<p>Option Saved</p>';
	}
	echo '<div class="wrap">';
	echo '<b>Add the welcome/landing page URL:</b>';
	echo '
		<form action="" method="post">
		<input type="text" name="landing_page_url" value="'.get_option('eslam_me_wordpress_redirect_to_landing_page_url', '').'"/>
		<br><br>
		<b>Allow redirection on all pages?</b>
		<br>
		<div style="margin-left:10px">
			<input type="radio" name="all_pages" value="home" ' . (get_option( 'eslam_me_wordpress_redirect_to_landing_page_for_all_pages', "home")=="home"?'checked':'') . '>Home page only
			<br>
			<input type="radio" name="all_pages" value="all" ' . (get_option( 'eslam_me_wordpress_redirect_to_landing_page_for_all_pages', "home")=="all"?'checked':'') . '>All pages
		</div>
		<br>
		<button type="submit">Save</button>
		</form>
		<br><br>
		<b>Developed by: </b><a target="_blank" href="http://eslam.me">Eslam Mahmoud</a>
	';
	echo '</div>';
}
?>