<?php

add_theme_support( 'post-thumbnails', array( 'post', 'newsml_post' ) );

add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );
function theme_enqueue_styles() {
	wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
	wp_enqueue_style( 'child-style',
		get_stylesheet_directory_uri() . '/style.css',
		array('parent-style')
	);
}

if ( ! function_exists( 'apa_entry_meta' ) ) :
	/**
	 * Prints HTML with meta information for the categories, tags.
	 *
	 * @since Twenty Fifteen 1.0
	 */
	function apa_entry_meta() {
		if ( is_sticky() && is_home() && ! is_paged() ) {
			printf( '<span class="sticky-post">%s</span>', __( 'Featured', 'twentyfifteen' ) );
		}

		$format = get_post_format();
		if ( current_theme_supports( 'post-formats', $format ) ) {
			printf( '<span class="entry-format">%1$s<a href="%2$s">%3$s</a></span>',
				sprintf( '<span class="screen-reader-text">%s </span>', _x( 'Format', 'Used before post format.', 'twentyfifteen' ) ),
				esc_url( get_post_format_link( $format ) ),
				get_post_format_string( $format )
			);
		}

		if ( in_array( get_post_type(), array( 'newsml_post', 'post', 'attachment' ) ) ) {
			$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s %5$s</time>';

			if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
				$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s %5$s</time>';
			}

			$time_string = sprintf( $time_string,
				esc_attr( get_the_date( 'c' ) ),
				get_the_date(),
				esc_attr( get_the_modified_date( 'c' ) ),
				get_the_modified_date(),
			    get_the_modified_time()
			);

			printf( '<span class="posted-on"><span class="screen-reader-text">%1$s </span><a href="%2$s" rel="bookmark">%3$s</a></span>',
				_x( 'Posted on', 'Used before publish date.', 'twentyfifteen' ),
				esc_url( get_permalink() ),
				$time_string
			);

			echo "<span>" . apa_get_article_number(get_the_ID()) . "</span>";

			echo "<span> Prioritaet: " . apa_get_urgency(get_the_ID()) . "</span>";
			echo "<span> Ressorts: " . apa_get_desks(get_the_ID()) . "</span>";
			//echo "<span> Timezone:" . date_default_timezone_get() . "</span>";
			echo "<span> Source: ".apa_get_source(get_the_ID())."</span>";
		}

		if ( 'post' == get_post_type() ) {
			if ( is_singular() || is_multi_author() ) {
				printf( '<span class="byline"><span class="author vcard"><span class="screen-reader-text">%1$s </span><a class="url fn n" href="%2$s">%3$s</a></span></span>',
					_x( 'Author', 'Used before post author name.', 'twentyfifteen' ),
					esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
					get_the_author()
				);
			}

			$categories_list = get_the_category_list( _x( ', ', 'Used between list items, there is a space after the comma.', 'twentyfifteen' ) );
			if ( $categories_list && twentyfifteen_categorized_blog() ) {
				printf( '<span class="cat-links"><span class="screen-reader-text">%1$s </span>%2$s</span>',
					_x( 'Categories', 'Used before category names.', 'twentyfifteen' ),
					$categories_list
				);
			}

			$tags_list = get_the_tag_list( '', _x( ', ', 'Used between list items, there is a space after the comma.', 'twentyfifteen' ) );
			if ( $tags_list && ! is_wp_error( $tags_list ) ) {
				printf( '<span class="tags-links"><span class="screen-reader-text">%1$s </span>%2$s</span>',
					_x( 'Tags', 'Used before tag names.', 'twentyfifteen' ),
					$tags_list
				);
			}
		}

		if ( is_attachment() && wp_attachment_is_image() ) {
			// Retrieve attachment metadata.
			$metadata = wp_get_attachment_metadata();

			printf( '<span class="full-size-link"><span class="screen-reader-text">%1$s </span><a href="%2$s">%3$s &times; %4$s</a></span>',
				_x( 'Full size', 'Used before full size attachment link.', 'twentyfifteen' ),
				esc_url( wp_get_attachment_url() ),
				$metadata['width'],
				$metadata['height']
			);
		}

		if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
			echo '<span class="comments-link">';
			/* translators: %s: post title */
			comments_popup_link( sprintf( __( 'Leave a comment<span class="screen-reader-text"> on %s</span>', 'twentyfifteen' ), get_the_title() ) );
			echo '</span>';
		}
	}
endif;

	function apa_get_article_number($postId) {
		$myvals = get_post_meta($postId);
		$article_number="";
		foreach ($myvals as $key=>$val) {
			if ($key === "newsml_meta_guid"){
				$valarr = explode(":",$val[0]);
				$article_number= $valarr[4];
				break;
			}
		}
		return $article_number;
	}

	function apa_get_urgency($postId) {
		$myvals = get_post_meta($postId);
		$urgency="";
		foreach ($myvals as $key=>$val) {
			if ($key === "newsml_meta_urgency"){
				$urgency= $val[0];
				break;
			}
		}
		return $urgency;
	}

	function apa_get_source($postId) {
		$myvals = get_post_meta($postId);
		$source="";
		foreach ($myvals as $key=>$val) {
			if ($key === "newsml_meta_source"){
				$source= $val[0];
				break;
			}
		}
		return $source;
	}

function apa_get_desks($postId) {
		$myvals = get_post_meta($postId);
		$desks="";
		foreach ($myvals as $key=>$val) {
			if ($key === "newsml_meta_desks"){
				$desks= $val[0];
				break;
			}
		}
		return $desks;
	}


add_action( 'pre_get_posts' , 'my_pre_get_posts' );

function my_pre_get_posts( $query ) {

	$src="";
	if (isset($_GET['src'])) {
		$src = $_GET['src'];
	}

	// Check this is main query and other conditionals as needed
	if( $query->is_main_query() && $src === "APA") {
		$query->set(
			'meta_query',
			array(
				array(
					'key' => 'newsml_meta_source',
					'value' => 'Basisdienst'
				)
			)
		);
	}

	if( $query->is_main_query() && $src === "OTS") {
		$query->set(
			'meta_query',
			array(
				array(
					'key' => 'newsml_meta_source',
					'value' => 'APA-OTS Originaltext-Service'
				)
			)
		);
	}

	if( $query->is_main_query() && $src === "ABD") {
		$query->set(
			'meta_query',
			array(
				array(
					'key' => 'newsml_meta_source',
					'value' => 'APA Bilderdienst'
				)
			)
		);
	}

	if( $query->is_main_query() && $src === "AGD") {
		$query->set(
			'meta_query',
			array(
				array(
					'key' => 'newsml_meta_source',
					'value' => 'APA Grafikdienst'
				)
			)
		);
	}

}

// Added to extend allowed files types in Media upload
add_filter('upload_mimes', 'custom_upload_mimes');
function custom_upload_mimes ( $existing_mimes=array() ) {

	// Add *.EPS files to Media upload
	$existing_mimes['eps'] = 'application/postscript';

	// Add *.AI files to Media upload
	$existing_mimes['ai'] = 'application/postscript';

	return $existing_mimes;
}

///**
// * Download an image from the specified URL and attach it to a post.
// * Modified version of core function media_sideload_image() in /wp-admin/includes/media.php (which returns an html img tag instead of attachment ID)
// * Additional functionality: ability override actual filename, and to pass $post_data to override values in wp_insert_attachment (original only allowed $desc)
// *
// * @since 1.4 Somatic Framework
// *
// * @param string $url (required) The URL of the image to download
// * @param int $post_id (required) The post ID the media is to be associated with
// * @param bool $thumb (optional) Whether to make this attachment the Featured Image for the post (post_thumbnail)
// * @param string $filename (optional) Replacement filename for the URL filename (do not include extension)
// * @param array $post_data (optional) Array of key => values for wp_posts table (ex: 'post_title' => 'foobar', 'post_status' => 'draft')
// * @return int|object The ID of the attachment or a WP_Error on failure *
//*/
// function somatic_attach_external_image( $url = null, $post_id = null, $thumb = null, $filename = null, $post_data = array() ) {
// 	if ( !$url || !$post_id ) return new WP_Error('missing', "Need a valid URL and post ID...");
// }
//
// require_once( ABSPATH . 'wp-admin/includes/file.php' );
// // Download file to temp location, returns full server path to temp file, ex; /home/user/public_html/mysite/wp-content/26192277_640.tmp
//$tmp = download_url( $url );
//// If error storing temporarily,unlink
// if ( is_wp_error( $tmp ) ) {
//	@unlink($file_array['tmp_name']);
//// clean up
//$file_array['tmp_name'] = '';
//return $tmp;
//// output wp_error }
//preg_match('/[^\?]+\.(jpg|JPG|jpe|JPE|jpeg|JPEG|gif|GIF|png|PNG)/', $url, $matches);
//// fix file filename for query strings
//$url_filename = basename($matches[0]);
//// extract filename from url for title
//$url_type = wp_check_filetype($url_filename);
//// determine file type (ext and mime/type)
//// override filename if given, reconstruct server path
//if ( !empty( $filename ) ) { $filename = sanitize_file_name($filename); $tmppath = pathinfo( $tmp );
//// extract path parts
//$new = $tmppath['dirname'] . "/". $filename . "." . $tmppath['extension'];
//// build new path
//rename($tmp, $new);
//// renames temp file on server
//$tmp = $new;
//// push new filename (in path) to be used in file array later
//}
//// assemble file data (should be built like $_FILES since wp_handle_sideload() will be using)
//$file_array['tmp_name'] = $tmp;
//// full server path to temp file
//if ( !empty( $filename ) ) { $file_array['name'] = $filename . "." . $url_type['ext'];
//// user given filename for title, add original URL extension
//} else {
//	$file_array['name'] = $url_filename;
//}
//// just use original URL filename
//}
//// set additional wp_posts columns
//if ( empty( $post_data['post_title'] ) ) { $post_data['post_title'] = basename($url_filename, "." . $url_type['ext']);
//// just use the original filename (no extension)
//}
//// make sure gets tied to parent
//if ( empty( $post_data['post_parent'] ) ) { $post_data['post_parent'] = $post_id; }
//// required libraries for media_handle_sideload
//require_once(ABSPATH . 'wp-admin/includes/file.php');
// require_once(ABSPATH . 'wp-admin/includes/media.php');
// require_once(ABSPATH . 'wp-admin/includes/image.php');
// // do the validation and storage stuff
//$att_id = media_handle_sideload( $file_array, $post_id, null, $post_data );
//// $post_data can override the items saved to wp_posts table, like post_mime_type, guid, post_parent, post_title, post_content, post_status
//// If error storing permanently, unlink
//if ( is_wp_error($att_id) ) { @unlink($file_array['tmp_name']);
//// clean up
//return $att_id;
//// output wp_error
//} // set as post thumbnail if desired
//if ($thumb) { set_post_thumbnail($post_id, $att_id); } return $att_id; }