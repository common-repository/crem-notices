<?php  
/* 
Plugin Name: crem_notices
Plugin URI: 
Description: Gestion de notices
Author: Anaïs Idriss
Version: 2.0 
Author URI: publictionnaire
*/  

/*-----------------------------------------------------------------------------------*/
/*	Plugin Notices
/*	- Appel des fichier utiles
/*	- Définition des tailles d'images
/*	- Fonction lors de l'activation du plugin
/*	- Fonction lors de la désactivation du plugin
/*	- Utilisation du template notice.php pour la page notice
/*	- Fonction get_notice
/*-----------------------------------------------------------------------------------*/


/**
*	Appel des fichier utiles
**/
require_once('crem_notices_custom-post.php');
require_once('crem_notices_metabox.php');
require_once('crem_notices_auteur_metabox.php');



/**
*	Définition des tailles d'images
**/
if ( function_exists( 'add_image_size' ) ) {
	add_image_size( 'crem_notice', 480, 250, true );
}



/**
*	Obtenir l'id de la page depuis le slug
**/
function crno_id_par_slug_notice($page_slug) {
	$page = get_page_by_path($page_slug);
	if ($page) {
		return $page->ID;
	} else {
		return null;
	}
}



/**
*	Fonction get_notice
*	@nombre int - nombre d'élements à afficher
*	@type array(int) - Taxonomie des éléments à afficher
*	@size string - taille des vignettes
*	@order string - DESC|ASC
*	@orderby string - Critere d'ordre
*	@id string - ID du bloc	
**/
function crno_get_notice($nombre = '-1', $types=array(), $colonne = 'three-up', $size = 'notice', $order = 'ASC',  $orderby = 'title', $id='liste-notice' ){
	
	global $post;

	$crno_notice = '';
	
	// Création de la requete
	$args_notice['post_type'] = 'notice';
	$args_notice['order']     = $order;
	$args_notice['orderby']   = $orderby;
	$args_notice['showposts']   = $nombre;
	if ($types!=NULL) {
		foreach($types as $type) {
			$args_notice['tax_query'][] = array (
				'taxonomy' => 'famille',
				'field' => 'id',
				'terms' => $type
			);
		}
	}
	$crno_notices = new WP_Query($args_notice);

	//
	if ($crno_notices->have_posts()) :
		if ($colonne=='one-up') {
			$crno_notice.= '<div id="'.$id.'">';
		} else {
			$crno_notice.= '<ul class="block-grid '.$colonne.' mobile" id="'.$id.'">';
		}
		//Boucle des notices
		while ($crno_notices->have_posts()) : $crno_notices->the_post();
		$img_notice = get_the_post_thumbnail( $post->ID, $size );
			if ($colonne=='one-up') {
				$crno_notice.= '<div class="notice">';
			} else {
				$crno_notice.= '<li class="notice">';
			}
				$crno_notice .='<div class="image-bloc">
					<div class="pop">
						<h3><a href="'.get_the_permalink().'" title="'.get_the_title().'">'.get_the_title().'</a></h3>
						<hr class="light"/> 
						<div class="post-info">
						</div>
					</div>
					'.$img_notice.'
				</div>';
			if ($colonne=='one-up') {
				$crno_notice.= '</div>';
			} else {
				$crno_notice.= '</li>';
			}
		endwhile;
		if ($colonne=='one-up') {
			$crno_notice.= '</div>';
		} else {
			$crno_notice.= '</ul>';
		}
	endif;
	wp_reset_query(); wp_reset_postdata();

	return $crno_notice;
}



/**
*	Fonction lors de l'activation du plugin
*	Création de la page notice
**/
if (!function_exists('crno_notice_active')) {
	function crno_notice_active(){
		$my_post = array(
			'post_title' => 'Notices',
			'post_name' => 'notices',
			'post_content' => '',
			'post_status' => 'publish',
			'post_author' => 1,
			'post_type' => 'page'
		);
			 
		// Ajout de la page dans l'admin
		wp_insert_post( $my_post );
	}
	register_activation_hook( __FILE__, 'crno_notice_active');
}



/**
*	Fonction lors de la desactivation du plugin
*	Suppression de la page notice
**/
if (!function_exists('crno_notice_desactive')) {
	function crno_notice_desactive(){
		/* Supprime la page */
		wp_delete_post( crno_id_par_slug_notice('notices'), true );

		/* Supprime les notices */
		$crno_notices = new WP_Query('post_type=notice');
		while ($crno_notices->have_posts()) : $crno_notices->the_post();
			wp_delete_post( get_the_ID(), true);
		endwhile;

		/* Supprime les types */
		$taxonomy = 'type';
		$terms = get_terms($taxonomy);
		$count = count($terms);
		if ( $count > 0 ){
			foreach ( $terms as $term ) {
				wp_delete_term( $term->term_id, $taxonomy );
			}
		}
	}
	register_deactivation_hook( __FILE__, 'crno_notice_desactive');
}



/**
*	Utilisation du template notice-template.php pour la page notice
**/
add_filter( 'template_include', 'crno_notice_page_template', 99 );
function crno_notice_page_template( $template ) {

	if ( is_page( 'notices' )  ) {
		$new_template = dirname( __FILE__ ) . '/templates/notice-template.php';
		if ( '' != $new_template ) {
			return $new_template ;
		}
	}

	return $template;
}



/**
*	Utilisation du template notice-template.php pour la page notice
**/
add_filter( 'template_include', 'crno_notice_auteur_page_template', 99 );
function crno_notice_auteur_page_template( $template ) {

	if ( is_page( 'auteurs' )  ) {
		$new_template = dirname( __FILE__ ) . '/templates/notice-auteur-template.php';
		if ( '' != $new_template ) {
			return $new_template ;
		}
	}

	return $template;
}



/**
*	Utilisation du template notice-template.php pour la page notice Alpha
**/
add_filter( 'template_include', 'crno_notice_alpha_page_template', 99 );
function crno_notice_alpha_page_template( $template ) {

	if ( is_page( 'ordre-alphabetique' )  ) {
		$new_template = dirname( __FILE__ ) . '/templates/notice-alpha-template.php';
		if ( '' != $new_template ) {
			return $new_template ;
		}
	}

	return $template;
}



/**
*	Utilisation du template notice-single-template.php pour la page d'un notice
**/
add_filter( 'single_template', 'crno_notice_single_template' );
function crno_notice_single_template($single_template) {
	global $post;

	if ($post->post_type == 'notice') {
		$single_template = dirname( __FILE__ ) . '/templates/notice-single-template.php';
	}
	return $single_template;
}



/**
*	Utilisation du template notice-single-template.php pour la page d'un notice
**/
add_filter( 'single_template', 'crno_auteur_single_template' );
function crno_auteur_single_template($single_template) {
	global $post;

	if ($post->post_type == 'auteur') {
		$single_template = dirname( __FILE__ ) . '/templates/auteur-single-template.php';
	}
	return $single_template;
}



/**
*	Utilisation du template notice-archive-template.php pour la page d'archive des notice
**/
add_filter( 'archive_template', 'crno_notice_archive_template' ) ;
function crno_notice_archive_template( $archive_template ) {
	wp_reset_query(); 
	global $post;
	if ( is_tax ( 'famille' ) ) {
		$archive_template = dirname( __FILE__ ) . '/templates/notice-archive-template.php';
	}
	return $archive_template;
}
