<?php
/*
Template Name: FormBuilder
*/
get_header(); ?>

<?php get_template_part( 'template-parts/featured-image' ); ?>

<div class="main-wrap" role="main">

<?php do_action( 'foundationpress_before_content' ); ?>
<?php while ( have_posts() ) : the_post(); ?>
	<article <?php post_class('main-content') ?> id="post-<?php the_ID(); ?>">
		<header>
			<h1 class="entry-title"><?php the_title(); ?></h1>
		</header>
		<?php do_action( 'foundationpress_page_before_entry_content' ); ?>
		<div class="entry-content">
			<?php the_content(); ?>
		</div>

		<?php if ( have_rows('sections') ) : ?>
		
			<?php $sections = array(); ?>

			<?php while( have_rows('sections') ) : the_row(); ?>
		
				<?php $section = array(); ?>
				<h4>1 <?php the_sub_field('section_header'); ?></h4>
				<?php $section["section_header"] = the_sub_field('section_header'); ?>
				<?php if ( have_rows('form_inputs') ) : ?>
				
					<?php while( have_rows('form_inputs') ) : the_row();

						$name = '';
						$label = '';
						$type = 'text';
						$placeholder = '';

						if( get_sub_field('id') ) {
							$id = get_sub_field('id');
							if ($id["name"]) {
								$name = $id["name"];
								if ($id["label"]) {
									$label = $id["label"];
								}
								else {
									$label = $id["name"];
								}
							}
						}

						if( get_sub_field('type') ) {
							$type = get_sub_field('type');
						}
						if( get_sub_field('placeholder') ) {
							$placeholder = get_sub_field('placeholder');
						}

						?>
					<?php endwhile; ?>
				
				<?php endif; ?>
			<?php endwhile; ?>
		
		<?php endif; ?>
		<div><?php 



		 ?></div>
		<footer>
			<?php
				wp_link_pages(
					array(
						'before' => '<nav id="page-nav"><p>' . __( 'Pages:', 'foundationpress' ),
						'after'  => '</p></nav>',
					)
				);
			?>
			<p><?php the_tags(); ?></p>
		</footer>
		<?php do_action( 'foundationpress_page_before_comments' ); ?>
		<?php comments_template(); ?>
		<?php do_action( 'foundationpress_page_after_comments' ); ?>
	</article>
<?php endwhile;?>

<?php do_action( 'foundationpress_after_content' ); ?>
<?php get_sidebar(); ?>
</div>

<?php get_footer();
