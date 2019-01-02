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

function apa_article_header() {

	echo '<span class="report-meta-info" style="font-weight:normal;">';
	echo '<span class="source">' . apa_get_article_number(get_the_ID()) . "</span>";
	$prio = apa_get_urgency(get_the_ID());
	echo ' ' . $prio;
	if (apa_get_desks(get_the_ID()) != ""){
		echo ' ' . apa_get_desks(get_the_ID());
	}
	echo ' ' . get_the_modified_date() . ' ' . get_the_modified_time();
	echo '<br/>';



	if (apa_get_slugline((get_the_ID()))!="" ){
		echo apa_get_slugline((get_the_ID()));
	}

	echo '</span>';

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
			$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s <br/>%5$s</time>';

			if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
				$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s <br/>%5$s</time>';
			}

			$time_string = sprintf( $time_string,
				esc_attr( get_the_date( 'c' ) ),
				get_the_date(),
				esc_attr( get_the_modified_date( 'c' ) ),
				get_the_modified_date(),
			    get_the_modified_time()
			);

			//printf( '<span class="posted-on"><span class="screen-reader-text">%1$s </span><a href="%2$s" rel="bookmark">%3$s</a></span>',
			//	_x( 'Posted on', 'Used before publish date.', 'twentyfifteen' ),
			//	esc_url( get_permalink() ),
			//	$time_string
			//);

			echo '<div id="article-datetime" style="text-align:right; float:left;padding: 18px 5px 16px; font-weight:bold; vertical-align: top">' . get_the_modified_date() . '<br/>' . get_the_modified_time() . '</div>';

			$prio = apa_get_urgency(get_the_ID());
			$priostring="";
			$priospan="";
			if ($prio == "3"){
				$priostring="Eilt, ";
				$priospan='<span class="prio-3">EILT</span>';
			}
			if ($prio == "2"){
				$priostring="Vorrang, ";
				$priospan='<span class="prio-2">VORRANG</span>';
			}
			if ($prio == "1"){
				$priostring="Blitz, ";
				$priospan='<span class="prio-1">BLITZ</span>';
			}
			echo '<div style="float:left;padding: 18px 5px 16px; font-weight:bold; vertical-align: top;width:80%;">';
			if (has_post_thumbnail()){
				echo '<div style="float:right;max-width:200px;height:100%;">';
				the_post_thumbnail('thumbnail');
				echo '</div>';
			}
			echo $priospan;
			echo '<a href="'.esc_url( get_permalink() ).'">';
			echo the_title( '', '' ) . '</a><br/>';
			echo '<span class="report-meta-info" style="font-weight:normal;">';
			echo '<span class="source">' . apa_get_article_number(get_the_ID()) . "</span>";
            if ($priostring!=""){
            	echo ', ' . $priostring;
            }
            if (apa_get_desks(get_the_ID()) != ""){
            	echo ', ' . apa_get_desks(get_the_ID());
            }
            if (apa_get_slugline((get_the_ID()))!="" ){
            	echo ', '.apa_get_slugline((get_the_ID()));
            }
            echo '</span>';

            echo '</div>';


            echo '<div style="clear:both" />';

			//echo "<span> Prioritaet: " . apa_get_urgency(get_the_ID()) . "</span>";
			//echo "<span> Source: ".apa_get_source(get_the_ID())."</span>";
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

	function apa_get_slugline($postId) {
		$myvals = get_post_meta($postId);
		$source="";
		foreach ($myvals as $key=>$val) {
			if ($key === "newsml_meta_slugline"){
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


add_action( 'add_meta_boxes', 'apa_meta_init' );
function apa_meta_init() {
	add_meta_box(
		'newsmlpost_source',
		__('Source','newsml-import'),
		'newsmlpost_source_box_callback',
		'newsml_post'
	);

	add_meta_box(
		'newsmlpost_urgency',
		__('Urgency','newsml-import'),
		'newsmlpost_urgency_box_callback',
		'newsml_post'
	);

	add_meta_box(
		'newsmlpost_desks',
		__('Desks','newsml-import'),
		'newsmlpost_desks_box_callback',
		'newsml_post'
	);

	add_meta_box(
		'newsmlpost_slugline',
		__('Slugline','newsml-import'),
		'newsmlpost_slugline_box_callback',
		'newsml_post'
	);
}

/**
 * Renders the slugline box for the newsml_post to the add/edit page.
 *
 * @author Reinhard Stockinger
 *
 * @param mixed $post The post whose metadata is to load.
 */
function newsmlpost_slugline_box_callback( $post ) {
	wp_nonce_field('newsmlpost_meta_box', 'newsmlpost_meta_box_nonce');

	$value = get_post_meta($post->ID, 'newsml_meta_slugline', true);

	echo '<label for="newsmlpost_slugline">';
	_e('Slugline', 'newsml-import');
	echo '</label>';
	echo '<input type="text" id="newsmlpost_slugline" name="newsmlpost_slugline" value="' . esc_attr( $value) . '" size="25" />';
}

/**
 * Renders the desk box for the newsml_post to the add/edit page.
 *
 * @author Reinhard Stockinger
 *
 * @param mixed $post The post whose metadata is to load.
 */
function newsmlpost_desks_box_callback( $post ) {
	wp_nonce_field('newsmlpost_meta_box', 'newsmlpost_meta_box_nonce');

	$value = get_post_meta($post->ID, 'newsml_meta_desks', true);

	echo '<label for="newsmlpost_desks">';
	_e('Desks', 'newsml-import');
	echo '</label>';
	echo '<input type="text" id="newsmlpost_desks" name="newsmlpost_desks" value="' . esc_attr( $value) . '" size="25" />';
}

/**
 * Renders the urgency box for the newsml_post to the add/edit page.
 *
 * @author Reinhard Stockinger
 *
 * @param mixed $post The post whose metadata is to load.
 */
function newsmlpost_urgency_box_callback( $post ) {
	wp_nonce_field('newsmlpost_meta_box', 'newsmlpost_meta_box_nonce');

	$value = get_post_meta($post->ID, 'newsml_meta_urgency', true);

	echo '<label for="newsmlpost_urgency">';
	_e('Urgency', 'newsml-import');
	echo '</label>';
	if ($post->post_status !== 'publish') {
		//Default to 5!
		echo '<input type="text" id="newsmlpost_urgency" name="newsmlpost_urgency" value="5" size="5" />';
	}
	else {
		echo '<input type="text" id="newsmlpost_urgency" name="newsmlpost_urgency" value="' . esc_attr( $value) . '" size="5" />';
	}
}

/**
 * Renders the source box for the newsml_post to the add/edit page.
 *
 * @author Reinhard Stockinger
 *
 * @param mixed $post The post whose metadata is to load.
 */
function newsmlpost_source_box_callback( $post ) {
	wp_nonce_field('newsmlpost_meta_box', 'newsmlpost_meta_box_nonce');

	$value = get_post_meta($post->ID, 'newsml_meta_source', true);

	echo '<label for="newsmlpost_source">';
	_e('Source', 'newsml-import');
	echo '</label>';

	if ($post->post_status !== 'publish'){
		//OTS is default source in a new article
		echo '<input type="text" id="newsmlpost_source" name="newsmlpost_source" value="APA-OTS Originaltext-Service" size="25" readonly/>';

	}
	else {
		echo '<input type="text" id="newsmlpost_source" name="newsmlpost_source" value="' . esc_attr( $value) . '" size="25" readonly/>';

		//echo '<select id="newsmlpost_source" name="newsmlpost_source">';
		//echo '<option value="Basisdienst" '.selected($value,"Basisdienst").'>APA Basisdienst</option>';
		//echo '<option value="APA Bilderdienst" '.selected($value,"APA Bilderdienst").'>APA Bilderdienst</option>';
		//echo '<option value="APA Grafikdienst" '.selected($value,"APA Grafikdienst").'>APA Grafikdienst</option>';
		//echo '<option value="APA-OTS Originaltext-Service" '.selected($value,"APA-OTS Originaltext-Service").'>APA-OTS Originaltext-Service</option>';
		//echo '</select>';
	}
}

add_action( 'save_post', 'apa_save_newsmlpost_meta' );
/**
 * Saves the changes of metaboxes to the database (add/edit).
 *
 * @author Reinhard Stockinger
 *
 * @param int $post_id The ID of the post whose metadata is to be saved.
 * @return mixed Returns the $post_id if not successful.
 */
function apa_save_newsmlpost_meta( $post_id ) {

	if ( ! isset( $_POST['newsmlpost_meta_box_nonce'] ) ) {
		return $post_id;
	}

	$nonce = sanitize_text_field( $_POST['newsmlpost_meta_box_nonce'] );

	if ( ! wp_verify_nonce( $nonce, 'newsmlpost_meta_box' ) ) {
		return $post_id;
	}


	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
		return $post_id;

	// Check the user's permissions.
	if ( 'newsml_post' == sanitize_text_field( $_POST['post_type'] ) ) {

		if ( ! current_user_can( 'edit_page', $post_id ) )
			return $post_id;

	} else {

		if ( ! current_user_can( 'edit_post', $post_id ) )
			return $post_id;
	}

	// Sanitize the user input.
	$sanitized_source = sanitize_text_field( $_POST['newsmlpost_source'] );
	$sanitized_urgency = sanitize_text_field( $_POST['newsmlpost_urgency'] );
	$sanitized_desks = sanitize_text_field( $_POST['newsmlpost_desks'] );
	$sanitized_slugline = sanitize_text_field( $_POST['newsmlpost_slugline'] );

	// Update the meta field.
	update_post_meta( $post_id, 'newsml_meta_source', $sanitized_source );
	update_post_meta( $post_id, 'newsml_meta_urgency', $sanitized_urgency );
	update_post_meta( $post_id, 'newsml_meta_desks', $sanitized_desks );
	update_post_meta( $post_id, 'newsml_meta_slugline', $sanitized_slugline );
}


// Add Column to the admin View!
add_filter('manage_newsml_post_posts_columns', 'set_custom_edit_columns');
function set_custom_edit_columns($columns) {
	$columns['source'] = __('Source','newsml-import');
	$columns['mldnr'] = __('Articlenr','newsml-import');
	return $columns;
}

add_action('manage_newsml_post_posts_custom_column', 'custom_apa_column', 10,2);
function custom_apa_column($column, $post_id) {
	switch ( $column) {
		case 'source' :
			echo get_post_meta($post_id, 'newsml_meta_source', true);
			break;
		case 'mldnr' :
			echo apa_get_article_number($post_id);
			break;
	}
}

// Sortierung in der newsml-post admin list!
add_filter('manage_edit-newsml_post_sortable_columns', 'apa_newsml_sortable_columns');
function apa_newsml_sortable_columns($columns){
	$columns['source'] = 'apa_source';
	$columns['mldnr'] = 'apa_mldnr';
	return $columns;
}

add_action('pre_get_posts','apa_newsml_posts_orderby');
function apa_newsml_posts_orderby($query) {
	if( ! is_admin() || ! $query->is_main_query() ) {
		return;
	}

	if ( 'apa_source' === $query->get( 'orderby') ) {
		$query->set( 'orderby', 'meta_value' );
		$query->set( 'meta_key', 'newsml_meta_source' );
		$query->set( 'meta_type', 'char' );
	}
	if ( 'apa_mldnr' === $query->get( 'orderby') ) {
		$query->set( 'orderby', 'meta_value' );
		$query->set( 'meta_key', 'newsml_meta_guid' );
		$query->set( 'meta_type', 'char' );
	}
}

add_action( 'admin_head', 'hide_mediatopic_box'  );
function hide_mediatopic_box() {
	global $post;
	global $pagenow;
	if (is_admin() && $pagenow=='post-new.php') {
		//remove mediatopic box on insert
		remove_meta_box('newsml_mediatopicdiv','newsml_post','side');
	}
}