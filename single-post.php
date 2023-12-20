<?php
/**
 * The template for displaying all single instructivo.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Astra
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

get_header(); ?>

<?php if ( astra_page_layout() == 'left-sidebar' ) : ?>

	<?php get_sidebar(); ?>

<?php endif ?>

<div class="back">
    <a href="/blog" class="back" style="background:none">
    <svg xmlns="http://www.w3.org/2000/svg" width="39" height="28" viewBox="0 0 39 28" fill="none">
  <path d="M1 14.8429L14.8429 1" stroke="#171717" stroke-width="2"/>
  <path d="M2.45715 14.1143L38.8857 14.1143" stroke="#171717" stroke-width="2"/>
  <path d="M1.00004 12.6572L14.8429 26.5001" stroke="#171717" stroke-width="2"/>
</svg>
</a>
</div>
<div id="primary" <?php astra_primary_class(); ?>>
<div class="instructivo-header">
        <h2>BLOG POST</h2>
        <h1><?php the_title(); ?></h1>
        <div class="post-thumbnail">
            <?php the_post_thumbnail(); ?>
        </div>
    </div>
    <div class="entry-content">
        <?php $post_date = get_the_date(); ?>
        <div class="entry-date">
        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="2" viewBox="0 0 25 2" fill="none">
<path d="M0 1L25 1" stroke="#819D44" stroke-width="2"/>
</svg> <?php echo $post_date; ?>
        </div>
    
        <?php the_content(); ?>
    </div>
</div><!-- #primary -->

<?php if ( astra_page_layout() == 'right-sidebar' ) : ?>

	<?php get_sidebar(); ?>

<?php endif ?>

<?php get_footer(); ?>
