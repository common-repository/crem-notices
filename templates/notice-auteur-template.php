<?php get_header(); ?>
<div class="container" id="contenu">
	<div class="row">
		<?php if (have_posts()) : ?>
			<?php while (have_posts()) : the_post(); ?>
				<h1><?php the_title(); ?></h1>
				<div class="rte">
					<?php the_content(); ?>
				</div>
				<hr class="pop"/>
			<?php endwhile; ?>
		<?php endif; ?>
		<div class="liste-char">
			<?php foreach (range('A','Z') as $letter) {
				$lettre = $letter;
			    echo '<a href="#'.$lettre.'"><strong>'.$lettre.'</strong></a> ';
			} ?>
		</div>
		<hr class="pop"/>
		<?php 
		$letter = '';
		$auteurs_q = new WP_Query(array('post_type' => 'auteur', 'posts_per_page' => '-1', 'pagination' => false, 'orderby' =>'title', 'order'=> 'ASC'));
		$auteurs = $auteurs_q->get_posts();?> 
		<div class="cols"> 
		<?php foreach( $auteurs as $auteur ) {
			$title=$auteur->post_title;
			$initial=strtoupper(substr($title,0,1));
			if ($auteur === reset($auteurs))
				$first_char = strtoupper(substr($title,0,1));

			if($initial!=$letter)
			{
				if($initial!=$first_char) { echo '</ul></div>'; }
				echo '<div><h3 id="'.$initial.'">'.$initial.'</h3><ul class="no-bullet">';
				$letter=$initial;
			}
			echo '<li><a href="'.get_the_permalink($auteur->ID).'" title="'.$title.'">'.$title.'</a></li>';
		} ?>
		</div>
	</div>
</div>
<?php get_footer(); ?>