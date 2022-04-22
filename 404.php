<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package Robins Photographs
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

get_header(); 

  get_template_part('template-parts/site-header' ); 
	// get_template_part('template-parts/breadcrumbs-bar' ); ?>
  
  <main>
    <div class="container">

		<section class="error-404 not-found text-align-s-center">
		<header class="page-header">
			<h1 class="page-title mb-4"><?php esc_html_e( 'Oops! That page can&rsquo;t be found.', 'robinsphotographs' ); ?></h1>
		</header><!-- .page-header -->

		<div class="page-content mb-8">
			<p><?php esc_html_e( 'Looks like something went wrong... Try going back to the Home page to find what you were looking for.', 'robinsphotographs' ); ?></p>
			<p><a href="<?= get_home_url() ?>" class="link-primary | icon-arrow-long-right icon-before icon-rotate-180">Back to Home</a></p>

			<?php // get_search_form(); ?>

    </div>
		</section>
		</div>
  </main>

<?php get_footer();