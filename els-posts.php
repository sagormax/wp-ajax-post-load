<?php

/**
 * @package els-ajax-posts-result
 */
/*
Plugin Name: Ajax Post Results
Plugin URI: http://www.easyloopsoft.com/
Description: Sample get ajax data from post.
Version: 3.1.3
Author: EasyLoopSoft Team
Author URI: http://www.easyloopsoft.com/
License: GPLv2 or later
Text Domain: els
*/

/*
| Create Admin Menu
*/
function elsAdminPage()
{
	global $elsAdminSettings;
	$elsAdminSettings =	add_menu_page( __("els Ajax Page", "els"), __("Ajax Page", "els"), "manage_options", "els-ajax-page", "elsAjaxAction", "dashicons-screenoptions");
}
add_action('admin_menu', 'elsAdminPage');

/*
| Admin Menu View
*/
function elsAjaxAction()
{
	?>
	<h1>This is Ajax Post Results:</h1>
	<div class="warp">

		<form action="" id="ajaxGetData" method="POST">
			Show Post:<input type="text" id="showPost" name="showPost">
			<input type="submit" id="elsSubmit" class="button-primary" name="ajaxresults" value="Get Results">
			<span class="spinner loading"></span>
		</form>
		
		<!--Result form-->
		<form method="get" id="posts-filter">
			<p class="search-box">
				<label for="post-search-input" class="screen-reader-text">Search Pages:</label>
				<input type="search" value="" name="s" id="post-search-input">
				<input type="submit" value="Search Pages" class="button" id="search-submit">
			</p>

			<table class="wp-list-table widefat fixed striped pages">
				<thead>
				<tr>
					<td class="manage-column column-cb check-column" id="cb">
						<label for="cb-select-all-1" class="screen-reader-text">Select All</label>
						<input type="checkbox" id="cb-select-all-1">
					</td>
					<th class="" id="title" scope="col">
						<span>Title</span><span class="sorting-indicator"></span>
					</th>
					<th class="" id="title" scope="col">
						<span>Short Description</span><span class="sorting-indicator"></span>
					</th>
					<th class="" id="title" scope="col">
						<span>View</span><span class="sorting-indicator"></span>
					</th>
				</thead>

				<tbody id="the-list">
					<!--Render result-->					
				</tbody>

			</table>
		</form>
		<!--End-->

	</div>
	<?php
}

/*
| Admin script load
*/
function elsEnqueueScript($hook)
{
	global $elsAdminSettings;
	if( $elsAdminSettings != $hook )
		return;
	wp_enqueue_script('els-ajax-def', plugin_dir_url(__FILE__).'js/els-ajax-def.js', array('jquery'), '1.1', TRUE);
	wp_enqueue_style('ajax-post-result', plugin_dir_url(__FILE__).'css/ajax-post-result.css', '', '1.1');
	wp_localize_script('els-ajax-def', 'els_var',array(
		'els_ajax_nonce' => wp_create_nonce('els-ajax-result')
	));
}
add_action('admin_enqueue_scripts', 'elsEnqueueScript');


/*
| Data Process
*/
function elsCallPostData()
{	
	if( !isset($_POST['elsnonce']) || wp_verify_nonce('els-ajax-result') )
		die('Authentication Error!!!');

	$showPost = $_POST['info']['showPost'];
	$posts = get_posts(array(
		'post_type' => 'post',
		'posts_per_page' => $showPost,
	));
	//var_dump($posts);
	if( $posts ):
		foreach ($posts as $post) {
			echo '
				<tr class="iedit author-self level-0 post-2 type-page status-publish hentry" id="post-2">
					<th class="check-column" scope="row">
						<input type="checkbox" id="cb-select-all-1">
					</th>
					<th class="check-column" scope="row">
						<label for="cb-select-2" class="">'.get_the_title($post->ID).'</label>
					</th>
					<th class="check-column" scope="row">
						<label for="cb-select-2" class="">'.$post->post_content.'</label>
					</th>
					<th class="check-column" scope="row">
						<label for="cb-select-2" class=""><a target="_blank" href="'.$post->guid.'">View Post</a></label>
					</th>						
				</tr>
			';
		}
	else:
		echo __('<p>No Post Found...</p>', 'els');
	endif;

	die();
}
add_action('wp_ajax_els_show_post_results', 'elsCallPostData');
add_action('wp_ajax_nopriv_els_show_post_results', 'elsCallPostData');