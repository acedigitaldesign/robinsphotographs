<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<?php $featured_image = new HeroImage ?>
<div class="hero" 
		 role="img" 
     aria-label="<?php echo $featured_image->alt; ?>"
     style="background-image: url('<?php echo $featured_image->src ?>'); background-position-Y: <?php echo $featured_image->heroOffset; ?>%;">
	<div class="hero__content container">
		<h1 class="hero__title">
			<?php the_title(); ?>
		</h1>
	</div>
</div>