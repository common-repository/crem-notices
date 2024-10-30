<?php
/*-----------------------------------------------------------------------------------*/
/*	Création des metabox pour le notice
/*-----------------------------------------------------------------------------------*/

add_filter( 'crno_cmb_meta_boxes', 'crno_metabox_notice' );
/**
 * Define the metabox and field configurations.
 *
 * @param  array $meta_boxes
 * @return array
 */
function crno_metabox_notice( array $meta_boxes ) {

	// Start with an underscore to hide fields from custom fields list
	$prefix = 'notice_';

	$query = new WP_Query( array( 'post_type' => 'auteur', 'pagination' => false, 'posts_per_page'=>'-1', 'orderby' => 'title', 'order'   => 'ASC',) );
	$auteurs = $query->get_posts();
	
	// Initate an empty array
	$auteur_options = array('0'=>'Choisissez un auteur');
	foreach($auteurs as $auteur) {
		$auteur_options[] = array(
			'name' => $auteur->post_title,
			'value' => $auteur->ID,
		);
	}

	$meta_boxes['notice'] = array(
		'id'         => 'notice_meta',
		'title'      => __( 'Contenu Notice', 'cmb' ),
		'pages'      => array( 'notice' ),
		'context'    => 'normal',
		'priority'   => 'high',
		'show_names' => true,
		'fields'     => array(
			array(
				'name' => 'Description et contenu',
				 'type' => 'wysiwyg',
				'id' => $prefix . 'description'
			),
			array(
				'name' => 'Auteur',
				'type' => 'select',
				'id' => $prefix . 'auteur',
				'options' => $auteur_options
			),
			array(
				'name' => 'Bibliographie',
				'type' => 'wysiwyg',
				'id' => $prefix . 'bibliographie'
			),
			array(
				'name' => 'Code zotero de la notice',
				'type' => 'text',
				'id' => $prefix . 'code_zotero'
			),
			array(
				'name'    => __( 'Liens notices', 'cmb' ),
				'id'      => $prefix . 'liens',
				'type'    => 'chosen', 	 // Creates an optimized version of any available
									   	 // select fields with chosen.js. http://harvesthq.github.io/chosen/
				'select'    => 'post_multiselect',// the 'select' argument will take any of the select field 'type's.
										 			  // including: 'select', 'multiselect', 'taxonomy_select',
										 			  // 'taxonomy_multiselect', 'post_select', and 'post_multiselect'.
				//'taxonomy'    => 'category', // If 'taxonomy_select' or 'taxonomy_multiselect' are used you need to define
											 // which taxonomy to use.
				'post_type'    => 'notice',  // If 'post_select' or 'post_multiselect' are used you need to define
											 // which post type to use.
			),
			array(
				'name' => 'Version pdf de la notice',
				'desc' => 'Ajouter un fichier pdf.',
				'id' => $prefix . 'pdf',
				'type' => 'file',
				'allow' => array( 'attachment' ) // limit to just attachments with array( 'attachment' )
			),
			array(
				'name' => 'Image',
				'desc' => 'Ajouter une image.',
				'id' => $prefix . 'image',
				'type' => 'file',
				'allow' => array( 'attachment' ) // limit to just attachments with array( 'attachment' )
			),
			array(
				'name' => 'Vidéo',
				'desc' => 'Url de la vidéo, youtube, dailymotion...',
				'id' => $prefix . 'video',
				'type' => 'oembed',
			),
		),
	);

	return $meta_boxes;
}

add_action( 'init', 'crno_init_notice_metabox', 9999 );
/**
 * Initialize the metabox class.
 */
function crno_init_notice_metabox() {

	if ( ! class_exists( 'crno_cmb_Meta_Box' ) )
		require_once 'meta-box/init.php';

}
