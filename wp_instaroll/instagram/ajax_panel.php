<?php

// AJAX handler for Instagram user stream
function instagram_photosbyusertable_ayax()
{
	$app_id = get_option(WP_ROLL_INSTAGRAM_PLUGIN_PREFIX.'_instagram_app_id');
	$app_secret = get_option(WP_ROLL_INSTAGRAM_PLUGIN_PREFIX.'_instagram_app_secret');			
	$user_access_token = get_option(WP_ROLL_INSTAGRAM_PLUGIN_PREFIX.'_instagram_user_accesstoken');
	$search_tag = get_option(WP_ROLL_INSTAGRAM_PLUGIN_PREFIX.'_instagram_search_tag');
	$insta_post_title = get_option(WP_ROLL_INSTAGRAM_PLUGIN_PREFIX.'_instagram_post_title_placeholder');
	$instagram_username = get_option(WP_ROLL_INSTAGRAM_PLUGIN_PREFIX.'_instagram_user_username');
	
	if (empty($app_id) || empty($app_secret) || empty($user_access_token) || empty($search_tag))
	{					
		$instagram_settings_page = get_bloginfo('wpurl').'/wp-admin/options-general.php?page='.WP_ROLL_INSTAGRAM_PLUGIN_PREFIX.'_menu';
		
		print('<p><strong>You need to  configure Instagram access from the <a href="'.$instagram_settings_page.'">Instagram Settings</a> panel inside the Settings menu.</strong></p>');
	}
	else {

		print('<h3>Instagram stream for user: '.$instagram_username.'</h3>');
		
		print('<p><a class="button-primary" href="'.getInstagramGeneratedDraftPosts().'">Go to Instagram draft posts</a>&nbsp;&nbsp;&nbsp;<a class="button-primary" id="Instagram_userphotosupdate" href="#">Update view</a></p>');

		$user_feed = getInstagramUserStream();
		
		if ($user_feed)
		{
			?>
			<div id="InstagramPhotosTable">
				
				<table class="wp-list-table widefat fixed posts">
					<thead>
						<tr>
							<th style="width: 350px;">Picture</th>
							<th style="width: 140px;">ID</th>
							<th style="width: 210px;">Tags</th>
							<th style="width: 80px;">Likes</th>
							<th style="width: 90px;">Comments</th>
							<th>Caption</th>
							<th style="width: 130px;">Author username</th>
							<th style="width: 80px;">Author ID</th>
							<th style="width: 100px;">Action</th>
						</tr>
					</thead>
					<tbody>
				<?php
								
					$data = $user_feed->data;
							
					foreach ($data as $element)
					{
						print('<tr class="alternate author-self status-publish format-default iedit">');
							print('<td class="insta_image"><a href="'.$element->link.'" target="_blank"><img src="'.$element->images->low_resolution->url.'" alt="" data-fullimageurl="'.$element->images->standard_resolution->url.'" /></a></td>');
							print('<td class="insta_id">'.$element->id.'<br />&nbsp;</td>');
							
							$tags_string = '';
							$tags = $element->tags;
							$tags_counter = 0;
							foreach ($tags as $tag)
							{
								if ($tags_counter++ > 0)
									$tags_string .= ', ';
									
								$tags_string .= $tag;
							}
								
							print('<td class="insta_tags">'.$tags_string.'<br />&nbsp;</td>');
							
							print('<td class="insta_likes_count">'.$element->likes->count.'<br />&nbsp;</td>');
							print('<td class="insta_comments_count">'.$element->comments->count.'<br />&nbsp;</td>');
							print('<td class="insta_description">'.$element->caption->text.'<br />&nbsp;</td>');
							print('<td class="insta_username">'.$element->user->username.'<br />&nbsp;</td>');
							print('<td class="insta_userid">'.$element->user->id.'<br />&nbsp;</td>');
							print('<td class="insta_createpost"><a href="#" id="create_wp_post_'.$element->id.'" class="button-secondary '.WP_ROLL_INSTAGRAM_PLUGIN_PREFIX.'_createpost_action">Create post</a></td>');
						print('</tr>');
					}
				?>
					</tbody>
				</table>
			</div>
			
			<?php
		}
	}

	exit;
	
	// accessible with URL:
	// http://[HOST]/wp-admin/admin-ajax.php?action=wpinstaroll_photosbytagtable
}
add_action('wp_ajax_wpinstaroll_photosbyusertable', 'instagram_photosbyusertable_ayax');


// AJAX handler for Instagram tag stream
function instagram_photosbytagtable_ayax()
{
	$app_id = get_option(WP_ROLL_INSTAGRAM_PLUGIN_PREFIX.'_instagram_app_id');
	$app_secret = get_option(WP_ROLL_INSTAGRAM_PLUGIN_PREFIX.'_instagram_app_secret');			
	$user_access_token = get_option(WP_ROLL_INSTAGRAM_PLUGIN_PREFIX.'_instagram_user_accesstoken');
	$search_tag = get_option(WP_ROLL_INSTAGRAM_PLUGIN_PREFIX.'_instagram_search_tag');
	$insta_post_title = get_option(WP_ROLL_INSTAGRAM_PLUGIN_PREFIX.'_instagram_post_title_placeholder');
	
	if (empty($app_id) || empty($app_secret) || empty($user_access_token) || empty($search_tag))
	{					
		$instagram_settings_page = get_bloginfo('wpurl').'/wp-admin/options-general.php?page='.WP_ROLL_INSTAGRAM_PLUGIN_PREFIX.'_menu';
		
		print('<p><strong>You need to  configure Instagram access from the <a href="'.$instagram_settings_page.'">Instagram Settings</a> panel inside the Settings menu.</strong></p>');
	}
	else {
		
		print('<h3>Instagram tag: '.$search_tag.'</h3>');
		
		print('<p><a class="button-primary" href="'.getInstagramGeneratedDraftPosts().'">Go to Instagram draft posts</a>&nbsp;&nbsp;&nbsp;<a class="button-primary" id="Instagram_tagphotosupdate" href="#">Update view</a></p>');

		$tag_feed = getInstagramPhotosWithTag($search_tag);
		
		if ($tag_feed)
		{
			?>
			<div id="InstagramPhotosTable">
				
				<table class="wp-list-table widefat fixed posts">
					<thead>
						<tr>
							<th style="width: 350px;">Picture</th>
							<th style="width: 140px;">ID</th>
							<th style="width: 210px;">Tags</th>
							<th style="width: 80px;">Likes</th>
							<th style="width: 90px;">Comments</th>
							<th>Caption</th>
							<th style="width: 130px;">Author username</th>
							<th style="width: 80px;">Author ID</th>
							<th style="width: 100px;">Action</th>
						</tr>
					</thead>
					<tbody>
				<?php
								
					$data = $tag_feed->data;
							
					foreach ($data as $element)
					{
						print('<tr class="alternate author-self status-publish format-default iedit">');
							print('<td class="insta_image"><a href="'.$element->link.'" target="_blank"><img src="'.$element->images->low_resolution->url.'" alt="" data-fullimageurl="'.$element->images->standard_resolution->url.'" /></a></td>');
							print('<td class="insta_id">'.$element->id.'<br />&nbsp;</td>');
							
							$tags_string = '';
							$tags = $element->tags;
							$tags_counter = 0;
							foreach ($tags as $tag)
							{
								if ($tags_counter++ > 0)
									$tags_string .= ', ';
									
								$tags_string .= $tag;
							}
								
							print('<td class="insta_tags">'.$tags_string.'<br />&nbsp;</td>');
							
							print('<td class="insta_likes_count">'.$element->likes->count.'<br />&nbsp;</td>');
							print('<td class="insta_comments_count">'.$element->comments->count.'<br />&nbsp;</td>');
							print('<td class="insta_description">'.$element->caption->text.'<br />&nbsp;</td>');
							print('<td class="insta_username">'.$element->user->username.'<br />&nbsp;</td>');
							print('<td class="insta_userid">'.$element->user->id.'<br />&nbsp;</td>');
							print('<td class="insta_createpost"><a href="#" id="create_wp_post_'.$element->id.'" class="button-secondary '.WP_ROLL_INSTAGRAM_PLUGIN_PREFIX.'_createpost_action">Create post</a></td>');
						print('</tr>');
					}
				?>
					</tbody>
				</table>
			</div>
			
			<?php
		}
	}

	exit;
	
	// accessible with URL:
	// http://[HOST]/wp-admin/admin-ajax.php?action=wpinstaroll_photosbytagtable
}
add_action('wp_ajax_wpinstaroll_photosbytagtable', 'instagram_photosbytagtable_ayax');


// handler for creating a post from Instagram pic
function instagram_createpostfromphoto_ayax()
{
	if (!isset($_POST['url']) || !isset($_POST['id']) || !isset($_POST['link']))
	{
		$response = array(
			'error' => true,
			'error_description' => 'required parameters missing'
		);
		print(json_encode($response));
		
		exit;
	}
	
	
	$search_tag = get_option(WP_ROLL_INSTAGRAM_PLUGIN_PREFIX.'_instagram_search_tag');
	if (empty($search_tag))
	{
		$response = array(
			'error' => true,
			'error_description' => 'Instagram access not properly configured'
		);
		print(json_encode($response));
		
		exit;
	}

	$title_placeholder = get_option(WP_ROLL_INSTAGRAM_PLUGIN_PREFIX.'_instagram_post_title_placeholder');

	// a. if the category corresponding to the Instagram search tags
	// doesn't exist, we create it
	$category_name = '#'.$search_tag;
	$cat_id = category_exists($category_name);
	if (!$cat_id)
		$cat_id = wp_create_category($category_name);	
	
	
	// b. post creation
	$created_post_status = get_option(WP_ROLL_INSTAGRAM_PLUGIN_PREFIX.'_instagram_created_post_status');
	if ($created_post_status != 'publish')
		$created_post_status = 'draft';

	$insert_photo_mode = get_option(WP_ROLL_INSTAGRAM_PLUGIN_PREFIX.'_instagram_insert_photo_mode');
	if ($insert_photo_mode != 'post_content')
		$insert_photo_mode = 'featured';


	$post_args = array(
		'post_author' 	=> 0,
		'post_category'	=> array($cat_id),
		'post_content' 	=> $_POST['caption'],
		'post_status'	=> $created_post_status, 
		'post_title'	=> $title_placeholder,
		'post_type'		=> 'post' 
	);
	$created_post_ID = wp_insert_post($post_args);
	
	if (!$created_post_ID)
	{
		$response = array(
			'error' => true,
			'error_description' => 'problem creating the post'
		);
		print(json_encode($response));
		
		exit;
	}


	// c. add Instagram pic metadata to the just created post
	update_post_meta($created_post_ID, '_'.WP_ROLL_INSTAGRAM_PLUGIN_METADATA_PREFIX.'_insta_id', $_POST['id']);
	update_post_meta($created_post_ID, '_'.WP_ROLL_INSTAGRAM_PLUGIN_METADATA_PREFIX.'_insta_link', $_POST['link']);
	update_post_meta($created_post_ID, '_'.WP_ROLL_INSTAGRAM_PLUGIN_METADATA_PREFIX.'_insta_authorusername', $_POST['author_username']);
	update_post_meta($created_post_ID, '_'.WP_ROLL_INSTAGRAM_PLUGIN_METADATA_PREFIX.'_insta_authorid', $_POST['author_id']);	
	
	
	// d. download image from Instagram and associate to post
	$tmp = download_url($_POST['url']);
    $file_array = array(
        'name' => basename($_POST['url']),
        'tmp_name' => $tmp
    );

    if (is_wp_error($tmp))
	{
		@unlink($file_array['tmp_name']);
		
		$response = array(
			'error' => true,
			'error_description' => 'problem downloading the image from Instagram'
		);
		print(json_encode($response));
		
		exit;
    }

    $attach_id = media_handle_sideload($file_array, $created_post_ID);
    if (is_wp_error($attach_id))
	{
        @unlink($file_array['tmp_name']);
        
		$response = array(
			'error' => true,
			'error_description' => 'problem adding the image to the post'
		);
		print(json_encode($response));
    }
	
	@unlink($file_array['tmp_name']);
	
	if ($insert_photo_mode === 'featured')
	{
		// attach to image as featured image
		add_post_meta($created_post_ID, '_thumbnail_id', $attach_id, true);
	}
	else {

		$image_info = wp_get_attachment_image_src($attach_id, 'full');

		// insert the image inside the post, followed by post caption
		$update_post_data = array();
  		$update_post_data['ID'] = $created_post_ID;
  		$update_post_data['post_content'] = '<img src="'.$image_info[0].'" alt="'.strip_tags($_POST['caption']).'" width="'.$image_info[1].'" height="'.$image_info[2].'"/><br/>'.
  											$_POST['caption'];

  		wp_update_post($update_post_data);
	}
	
	$response = array(
		'error' => false,
		'post_id' => $created_post_ID
	);
	print(json_encode($response));

	exit;
	
	// accessible with URL:
	// http://[HOST]/wp-admin/admin-ajax.php?action=create_post_from_instagram_pic
}
add_action('wp_ajax_create_post_from_instagram_pic', 'instagram_createpostfromphoto_ayax');


?>