<?php get_header(); ?>
<?php if (have_posts()) : ?>
	<?php while (have_posts()) : the_post(); ?>
	<div class="container" id="contenu">
		<div class="row">
			<div class="columns eight">
				<h1><?php the_title(); ?></h1>
				<hr class="pop"/>
				<?php 
				//DESCRIPTION ___
				if(get_post_meta($post->ID, 'notice_description', true) != NULL) { ?>
					<div class="row rte">
						<?php echo apply_filters( 'the_content', get_post_meta($post->ID, 'notice_description', true)); ?>
					</div>
				<?php } ?>
				<?php 
				//BIBLIOGRAPHIE ___
				if(get_post_meta($post->ID, 'notice_bibliographie', true) != NULL) { ?>
					<hr class="pop"/>
					<h2>Bibliographie</h2>
					<div class="row rte">
						<?php echo apply_filters( 'the_content', get_post_meta($post->ID, 'notice_bibliographie', true)); ?>
					</div>
				<?php } ?>
			</div>
			<div class="columns four notice-sidebar">
				<?php
				//FAMILLE 
				$familles = get_the_terms( $post->ID, 'famille' );					
				if ( $familles && ! is_wp_error( $familles ) ) { ?>
					<div class="side__block famille">
						<h2>Famille</h2>
						<ul class="block-grid two-up mobile">
							<?php foreach ( $familles as $famille ) { ?>
								<li>
									<?php $image_src = s8_get_taxonomy_image_src($famille,'thumbnail'); ?>
									<?php if($image_src['src']!= NULL) { ?><a href="<?php echo get_term_link($famille->term_id,'famille'); ?>" title="<?php echo $famille->name; ?>"><img src="<?php echo $image_src['src']; ?>"/></a><?php } ?>
									<strong><a href="<?php echo  get_term_link($famille->term_id,'famille'); ?>" title="<?php echo $famille->name; ?>"><?php echo $famille->name; ?></a></strong>
								</li>
							<?php } ?>
						</ul>
					</div>
				<?php } ?>

				<?php
				// AUTEUR ___
				if(get_post_meta($post->ID, 'notice_auteur', true) != '0') { ?>
					<div class="side__block auteur">
						<h2>Auteur</h2>
						<?php
							$auteur = get_post(get_post_meta($post->ID, 'notice_auteur', true));
						?>
						<p><a href="<?php echo get_permalink($auteur->ID); ?>" title="<?php echo get_post_meta($auteur->ID, 'auteur_nom', true).' '.get_post_meta($auteur->ID, 'auteur_prenom', true); ?>"><?php echo get_post_meta($auteur->ID, 'auteur_nom', true).' '.get_post_meta($auteur->ID, 'auteur_prenom', true); ?></a></p>
						<?php if(get_post_meta($auteur->ID, 'auteur_email', true) != NULL
						OR get_post_meta($auteur->ID, 'auteur_page', true) != NULL
						OR get_post_meta($auteur->ID, 'auteur_bio', true) != NULL) { ?>
						<div class="panel-pop auteur">
							<h3 class="auteur__nom"><?php echo get_post_meta($auteur->ID, 'auteur_nom', true).' '.get_post_meta($auteur->ID, 'auteur_prenom', true); ?></h3>
							<p class="auteur__bio"><?php echo apply_filters('the_content', get_post_meta($auteur->ID, 'auteur_bio', true)); ?></p>
							<p>
								<a href="mailto:<?php echo get_post_meta($auteur->ID, 'auteur_email', true); ?>" title="E-mail <?php echo get_post_meta($auteur->ID, 'auteur_nom', true).' '.get_post_meta($auteur->ID, 'auteur_prenom', true); ?>"><?php echo get_post_meta($auteur->ID, 'auteur_email', true); ?></a>
							</p>
							<p>
								<a href="<?php echo get_post_meta($auteur->ID, 'auteur_page', true); ?>" title="Page personnelle <?php echo get_post_meta($auteur->ID, 'auteur_nom', true).' '.get_post_meta($auteur->ID, 'auteur_prenom', true); ?>"><?php echo get_post_meta($auteur->ID, 'auteur_page', true); ?></a>
							</p>
						</div>
						<?php } ?>
					</div>
				<?php } ?>

				<?php 
				//MOTS CLES ___
				if(has_term( '','mot-cle', $post->ID )) { ?>
					<div class="side__block">
						<h2>Mots-clés</h2>
						<?php the_terms( $post->ID, 'mot-cle', '', ' / ' ); ?>
					</div>
				<?php } ?>

				<?php
				// CODE ZOTERO
				if(get_post_meta($post->ID, 'notice_code_zotero', true) != NULL) { ?>
					<div class="side__block">
						<h2>Citer la notice</h2>
						<?php echo get_post_meta($post->ID, 'notice_code_zotero', true); ?>
					</div>
				<?php } ?>

				<?php
				//VOIR AUSSI
				if(get_post_meta($post->ID, 'notice_liens', true) != NULL) { ?>
					<div class="side__block">
						<h2>Voir aussi</h2>
						<?php $liens_q = new WP_Query(array('post_type' => 'notice', 'post__in' => get_post_meta($post->ID, 'notice_liens', true)));
						$liens = $liens_q->get_posts();

						foreach($liens as $lien) { ?>
							<a href="<?php echo get_permalink($lien->ID); ?>"><?php echo $lien->post_title; ?></a> 
						<?php } ?>
					</div>
				<?php } ?>

				<?php
				//TELECHARGER LE PDF
				if(get_post_meta($post->ID, 'notice_pdf', true) != NULL) { ?>
					<div class="side__block">
						<a href="<?php echo get_post_meta($post->ID, 'notice_pdf', true); ?>" class="button pop radius full-width" title="Télécharger la notice en PDF"><i class="fa fa-file-pdf-o"></i> Télécharger en pdf</a>
					</div>
				<?php } ?>
			</div>
		</div>
	</div>
	<?php endwhile; ?>
<?php endif; ?>
<?php get_footer(); ?>