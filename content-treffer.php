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

<article id="post-<?php the_ID(); ?>" <?php post_class("article-treffer"); ?>>
<!--
	<header class="entry-header">
    </header>
    -->
	<?php
//			if ( is_single() ) :
//				the_title( '<h1 class="entry-title">', '</h1>' );
//			else :
//				the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' );
//			endif;
//
//            $myvals = get_post_meta(get_the_ID());
//            foreach ($myvals as $key=>$val) {
//                if ($key == "newsml_meta_subtitle"){
//                    echo "<h2>" . $val[0] . "</h2>";
//                }
//            }
		?>

<!--
	<footer class="entry-footer">
	</footer><!-- .entry-footer -->

	<?php apa_entry_meta(); ?>

</article><!-- #post-## -->
