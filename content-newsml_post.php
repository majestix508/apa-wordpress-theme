<?php
/**
 * The default template for displaying content
 *
 * Used for both single and index/archive/search.
 *
 * @package WordPress
 * @subpackage Twenty_Fifteen
 * @since Twenty Fifteen 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php
		// Post thumbnail.
		twentyfifteen_post_thumbnail();
	?>

	<header class="entry-header">
		<?php
			if ( is_single() ) {
				apa_article_header();
				echo "<br/><br/>";
                the_title( '<h1 class="entry-title">', '</h1>' );
				$myvals = get_post_meta($post->ID);
				$subtitle="";
				foreach ($myvals as $key=>$val) {
					if ($key === "newsml_meta_subtitle"){
						$subtitle= $val[0];
						break;
					}
				}
                if ($subtitle != "") {
                    echo '<h2>Utl.: ' . $subtitle . '</h2><br/>';
                }
            }
			else {
				the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' );

			}
		?>
	</header><!-- .entry-header -->

	<div class="entry-content">
		<?php
			/* translators: %s: Name of current post */
			the_content( sprintf(
				__( 'Continue reading %s', 'twentyfifteen' ),
				the_title( '<span class="screen-reader-text">', '</span>', false )
			) );

			wp_link_pages( array(
				'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'twentyfifteen' ) . '</span>',
				'after'       => '</div>',
				'link_before' => '<span>',
				'link_after'  => '</span>',
				'pagelink'    => '<span class="screen-reader-text">' . __( 'Page', 'twentyfifteen' ) . ' </span>%',
				'separator'   => '<span class="screen-reader-text">, </span>',
			) );
		?>
	</div><!-- .entry-content -->


    <div>
	    <?php
        //if ( $post->post_type == 'newsmlg2_post' && $post->post_status == 'publish' ) {
            $attachments = get_posts( array(
			    'post_type' => 'attachment',
			    'posts_per_page' => -1,
			    'post_parent' => $post->ID,
			    //'exclude'     => get_post_thumbnail_id()
		    ) );

		    if ( $attachments ) {
			    echo "<span style='margin-left:15px;'>Anh&auml;nge</span>";
			    echo "<ul class=\"apa-attachment-ul\">";
		        foreach ( $attachments as $attachment ) {
		            //echo "<pre>" . var_dump($attachment) . "</pre>";
		            $path = $attachment->guid;
		            $ext = substr($path, strrpos($path,'.')+1);
		            $desc_text="Highres";
		            if ($ext == "ai"){
		                $desc_text = "Adobe Illustrator";
                    }
				    $class = "post-attachment mime-" . sanitize_title( $attachment->post_mime_type );
				    $thumbimg = wp_get_attachment_link( $attachment->ID, 'thumbnail', false, true );
				    $textlink = wp_get_attachment_link( $attachment->ID, [], false,false,$desc_text);
				    echo '<li class="apa-attachment-li ' . $class . ' data-design-thumbnail">' . $thumbimg . '<br>' . $textlink .'</li>';
			    }
			    echo "</ul>";

		    }
	    //}
	    ?>
    </div>
	<?php
		// Author bio.
		if ( is_single() && get_the_author_meta( 'description' ) ) :
			get_template_part( 'author-bio' );
		endif;
	?>

	<footer class="entry-footer">
		<?php edit_post_link( __( 'Edit', 'twentyfifteen' ), '<span class="edit-link">', '</span>' ); ?>
	</footer><!-- .entry-footer -->

</article><!-- #post-## -->
