<?php
/**
 * The Template for displaying all courses (archive).
 *
 * @author 		codeBOX
 * @package 	lifterLMS/Templates
 *
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

get_header(); 
if( get_post_meta(get_the_ID(), 'header', true) != 'no') echo avia_title();
?>

<div class='container_wrap container_wrap_first main_color <?php avia_layout_class( 'main' ); ?>'>

	<div class='container'>

		<main class='template-page content  <?php avia_layout_class( 'content' ); ?> units' <?php avia_markup_helper(array('context' => 'content','post_type'=>'page'));?>>

			<?php if ( apply_filters( 'lifterlms_show_page_title', true ) ) : ?>

				<h1 class="page-title"><?php lifterlms_page_title(); ?></h1>
				
			<?php endif; ?>


			<?php do_action( 'lifterlms_archive_description' ); ?>

			<?php if ( have_posts() ) : ?>

				<?php do_action( 'lifterlms_before_shop_loop' ); ?>

				<?php lifterlms_course_loop_start(); ?>

					<?php while ( have_posts() ) : the_post(); ?>

						<?php llms_get_template_part( 'content', 'course' ); ?>

					<?php endwhile; // end of the loop. ?>

				<?php lifterlms_course_loop_end(); ?>

				<?php do_action( 'lifterlms_after_shop_loop' ); ?>

				<?php llms_get_template_part( 'course', 'pagination' ); ?>

			<?php else : ?>

				<?php llms_get_template( 'loop/no-courses-found.php' ); ?>

			<?php endif; ?>

		</main>
	
		<?php get_sidebar(); ?>
	
	</div>

</div>

<?php 
do_action( 'lifterlms_sidebar' ); ?>

<?php get_footer(); ?>
