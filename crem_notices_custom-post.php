<?php
/*-----------------------------------------------------------------------------------*/
/*	Gestion des notices
/*	- Création du custom post
/*	- Création de la taxonomie
/*	- Ajout des informations dans le widget "D'un coup d'oeil de wordpress"
/*-----------------------------------------------------------------------------------*/

function crno_register_notice() {
	/**
	*	Création du custom post
	**/
	$args = array(  
		'label' => __('Notices'),  
		'singular_label' => __('Notices'),  
		'public' => true,  
		'show_ui' => true,  
		'has_archive' => true,  
		'capability_type' => 'post',  
		'hierarchical' => false,  
		'rewrite' => true,
		'menu_icon' => 'dashicons-book-alt',		
		'supports' => array('title','thumbnail') 
	);
	register_post_type('notice' , $args ); 


	/**
	*	Création de la taxonomie
	**/
	// Définition des variable pour le type de notices
	$tx_args = array(
		'name' => _x( 'Familles', 'taxonomy general name' ),
		'singular_name' => _x( 'Famille', 'taxonomy singular name' ),
		'search_items' =>  __( 'Rechercher une Famille' ),
		'all_items' => __( 'Toutes les Familles' ),
		'parent_item' => __( 'Famille parent' ),
		'parent_item_colon' => __( 'Famille parent:' ),
		'edit_item' => __( 'Modifier la Famille' ), 
		'update_item' => __( 'Mettre &agrave; jour la Famille' ),
		'add_new_item' => __( 'Ajouter une nouvelle Famille' ),
		'new_item_name' => __( 'Nouveau nom de Famille' ),
		'menu_name' => __( 'Familles' ),
	);

	// Enregristrement de la taxonomie "marque"
	register_taxonomy('famille',array('notice'), array(
		'hierarchical' => true,
		'labels' => $tx_args,
		'show_ui' => true,
		'show_admin_column' => true,
		'query_var' => true,
		'rewrite' => array( 'slug' => 'famille' ),
	));

	$key_args = array(
		'name' => _x( 'Mots cl&eacute;s', 'taxonomy general name' ),
		'singular_name' => _x( 'Mot cl&eacute;', 'taxonomy singular name' ),
		'search_items' =>  __( 'Rechercher un Mot cl&eacute;' ),
		'all_items' => __( 'Tous les Mots cl&eacute;s' ),
		'parent_item' => __( 'Mot cl&eacute; parent' ),
		'parent_item_colon' => __( 'Mot cl&eacute; parent:' ),
		'edit_item' => __( 'Modifier le Mot cl&eacute;' ), 
		'update_item' => __( 'Mettre &agrave; jour le Mot cl&eacute;' ),
		'add_new_item' => __( 'Ajouter un nouveau Mot cl&eacute;' ),
		'new_item_name' => __( 'Nouveau nom de Mot cl&eacute;' ),
		'menu_name' => __( 'Mots-cl&eacute;s' ),
	);

	// Enregristrement de la taxonomie "marque"
	register_taxonomy('mot-cle',array('notice'), array(
		'hierarchical' => false,
		'labels' => $key_args,
		'show_ui' => true,
		'show_admin_column' => true,
		'query_var' => false,
		'rewrite' => array( 'slug' => 'mot-cle' ),
	));

 
}
add_action('init', 'crno_register_notice');


function crno_register_auteur() {
	/**
	*	Création du custom post
	**/
	$args = array(  
		'label' => __('Auteurs'),  
		'singular_label' => __('Auteurs'),  
		'public' => true,  
		'show_ui' => true,  
		'has_archive' => true,  
		'capability_type' => 'post',  
		'hierarchical' => false,  
		'rewrite' => true,
		'menu_icon' => 'dashicons-groups',		
		'supports' => array('title') 
	);
	register_post_type('auteur' , $args ); 
}
add_action('init', 'crno_register_auteur');



/**
*	Ajout des informations dans le widget "D'un coup d'oeil de wordpress"
**/
add_action('dashboard_glance_items', 'crno_add_notice_counts');
function crno_add_notice_counts() {
	if (!post_type_exists('notice')) {
		return;
	}

	$num_posts = wp_count_posts( 'notice' );
	$num = number_format_i18n( $num_posts->publish );
	$text = _n( 'Notice', 'Notices', intval($num_posts->publish) );
	if ( current_user_can( 'edit_posts' ) ) {
		$num = "<a href='edit.php?post_type=notice'>$num";
		$text = "$text</a>";
	}
	echo '<li class="slide-count">';
	echo $num.' '.$text;
	echo '</li>';

	if ($num_posts->pending > 0) {
		$num = number_format_i18n( $num_posts->pending );
		$text = _n( 'Notice en attente', 'Notices en attente', intval($num_posts->pending) );
		if ( current_user_can( 'edit_posts' ) ) {
			$num = "<a href='edit.php?post_status=pending&post_type=notice'>$num</a>";
			$text = "<a href='edit.php?post_status=pending&post_type=notice'>$text</a>";
		}
		echo '<li class="slide-pending-count">';
		echo $num.' '.$text;
		echo '</li>';
	}
}