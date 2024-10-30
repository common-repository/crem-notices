<?php get_header(); ?>
<div class="container" id="contenu">
	<div class="row">
		<?php $term = $wp_query->queried_object; ?>
		<?php $image_src = s8_get_taxonomy_image_src($term,'thumbnail'); ?>
		<?php if($image_src['src']!= NULL) { ?><img src="<?php echo $image_src['src']; ?>" style="margin-right:20px;" class="left"/><?php } ?>
		<h1 <?php if($image_src['src']!= NULL) { ?> style="margin-top:60px;" <?php } ?>><?php echo $term->name; ?></h1>
		<?php if($term->description!=NULL) { ?>
			<p><?php echo $term->description; ?></p>
		<?php } ?>
		<hr class="pop"/>
		<div class="liste-char">
			<?php foreach (range('A','Z') as $letter) {
				$lettre = $letter;
			    echo '<a href="#'.$lettre.'"><strong>'.$lettre.'</strong></a> ';
			} ?>
		</div>
		<hr class="pop"/>
		<?php 
		$letter = '';
		$notices = new WP_Query(
			array(
				'post_type' => 'notice',
				'posts_per_page' => '-1',
				'pagination' => false,
				'orderby' =>'title',
				'order'=> 'ASC',
				'tax_query' => array(
					array (
						'taxonomy' => 'famille',
						'field' => 'term_id',
						'terms' => $term->term_id,
					)
				)
			)
		);
		$posts = $notices->get_posts();
		?>
		<div class="cols">
		<?php foreach( $posts as $post ) {
			$title=$post->post_title;
			$initial=strtoupper(substr($title,0,1));
			
			if ($post === reset($posts))
				$first_char = strtoupper(substr($title,0,1));

			if($initial!=$letter)
			{
				if($initial!=$first_char) { echo '</ul></div>'; }
				echo '<div><h3 id="'.$initial.'">'.$initial.'</h3><ul class="no-bullet">';
				$letter=$initial;
			}
			echo '<li><a href="'.get_the_permalink($post->ID).'" title="'.$title.'">'.$title.'</a></li>';
		} ?>
	</div>
</div>
</div>
<?php get_footer(); ?>