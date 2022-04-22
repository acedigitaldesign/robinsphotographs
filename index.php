<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package We Love Concept Art
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

 ?>

	<?php get_header();?>

		<?php while ( have_posts() ) : the_post(); ?>

		<main>
			<?php get_template_part('template-parts/hero'); ?>
			<div class="container pt-m-12 pb-m-20">
				<div class="<?php echo initialize_content_layout_styles(); ?>">
					<?php the_custom_content(false); ?>
				</div>
			</div>
		</main>

		<?php endwhile; // End of the loop. ?>


<?php get_footer(); ?>