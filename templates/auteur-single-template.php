<?php get_header(); ?>
<?php if (have_posts()) : ?>
	<?php while (have_posts()) : the_post(); ?>
	<div class="container" id="contenu">
		<div class="row">
			<h1><?php the_title(); ?></h1>
			<hr class="pop"/>
			<?php if(get_post_meta($post->ID, 'auteur_email', true) != NULL || get_post_meta($post->ID, 'auteur_page', true) != NULL ) { ?>
			<div class="row panel-pop">
				<?php if(get_post_meta($post->ID, 'auteur_email', true) != NULL) { ?>
				<div class="columns six phone-three">
					<a href="mailto:<?php echo get_post_meta($post->ID, 'auteur_email', true); ?>" title="E-mail <?php echo get_post_meta($post->ID, 'auteur_nom', true).' '.get_post_meta($post->ID, 'auteur_prenom', true); ?>"><i class="fa fa-envelope-o"></i> <?php echo get_post_meta($post->ID, 'auteur_email', true); ?></a>
				</div>
				<?php }
				if(get_post_meta($post->ID, 'auteur_page', true) != NULL) { ?>
				<div class="columns six phone-three">
					<a href="<?php echo get_post_meta($post->ID, 'auteur_page', true); ?>" title="Page personnelle <?php echo get_post_meta($post->ID, 'auteur_nom', true).' '.get_post_meta($post->ID, 'auteur_prenom', true); ?>"><i class="fa fa-globe"></i> <?php echo get_post_meta($post->ID, 'auteur_page', true); ?></a>
				</div>
				<?php } ?>
			</div>
			<?php } ?>
			<div class="auteur__bio"><?php echo apply_filters('the_content', get_post_meta($post->ID, 'auteur_bio', true)); ?></div>
			<?php
			$args_notice = array(
				'post_type' => 'notice',
				'posts_per_page' => '-1',
				'pagination' => false,
				'meta_key' => 'notice_auteur',
				'meta_value' => $post->ID,
				'orderby' => 'title',
				'order' => 'ASC'
			);
			$notices_q = new WP_Query($args_notice);
			$notices = $notices_q->get_posts();
			if($notices!= NULL) { ?>
				<hr class="pop"/>
				<h3>Notices de <?php the_title(); ?></h3>
				<div class="cols"> 
				<?php foreach( $notices as $notice ) {
					$title=$notice->post_title;
					$initial=strtoupper(substr($title,0,1));
					if($initial!=$letter)
					{
						if($initial!='A') { echo '</ul></div>'; }
						echo '<div><h3 id="'.$initial.'">'.$initial.'</h3><ul class="no-bullet">';
						$letter=$initial;
					}
					echo '<li><a href="'.get_the_permalink($notice->ID).'" title="'.$title.'">'.$title.'</a></li>';
				}
			} ?>
		</div>
	</div>
	<?php endwhile; ?>
<?php endif; ?>
<?php get_footer(); ?>