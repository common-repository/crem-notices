<?php
/*-----------------------------------------------------------------------------------*/
/*	Création des metabox pour le auteur
/*-----------------------------------------------------------------------------------*/

add_filter( 'crno_cmb_meta_boxes', 'crno_metabox_auteur' );
/**
 * Define the metabox and field configurations.
 *
 * @param  array $meta_boxes
 * @return array
 */
function crno_metabox_auteur( array $meta_boxes ) {

	// Start with an underscore to hide fields from custom fields list
	$prefix = 'auteur_';

	$meta_boxes['auteur'] = array(
		'id'         => 'auteur_meta',
		'title'      => __( 'Contenu Auteur', 'cmb' ),
		'pages'      => array( 'auteur' ),
		'context'    => 'normal',
		'priority'   => 'high',
		'show_names' => true,
		'fields'     => array(
			array(
				'name' => 'Nom',
				'type' => 'text_small',
				'id' => $prefix . 'nom'
			),
			array(
				'name' => 'Prénom',
				'type' => 'text_small',
				'id' => $prefix . 'prenom'
			),
			array(
				'name' => 'Biographie',
				'type' => 'wysiwyg',
				'id' => $prefix . 'bio'
			),
			array(
				'name' => 'Adresse e-mail',
				'type' => 'text_email',
				'id' => $prefix . 'email'
			),
			array(
				'name' => 'Page personnelle (site)',
				'type' => 'text_url',
				'id' => $prefix . 'page'
			),
		),
	);

	return $meta_boxes;
}

add_action( 'init', 'crno_init_auteur_metabox', 9999 );
/**
 * Initialize the metabox class.
 */
function crno_init_auteur_metabox() {

	if ( ! class_exists( 'crno_cmb_Meta_Box' ) )
		require_once 'meta-box/init.php';

}
