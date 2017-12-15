<?php
class CustomPost
{
	private $cpt_name;
	private $tax_name;

	public function create_cpt( $slug, $args )
	{
		$cpts[$slug] = $args;

	    foreach ( $cpts as $cpt => $attr ) {
	        $label 			= ( isset( $attr['label'] ) ) ? $attr['label'] : ucfirst( $cpt );
	        $label_plural 	= ( isset( $attr['label_plural'] ) ) ? $attr['label_plural'] :  $label . 's';

	        $attr['labels'] = array(
	            'name'                  => $label_plural,
	            'add_new'               => 'Adicionar',
	            'add_new_item'          => 'Adicionar ' . $label,
	            'edit_item'             => 'Editar ' . $label,
	            'new_item'              => 'Adicionar ' . $label,
	            'view_item'             => 'Visualizar ' . $label,
	            'search_items'          => 'Pesquisar ' . $label_plural,
	            'not_found'             => 'Nenhum conteúdo foi encontrado',
	            'not_found_in_trash'    => 'Nenhum conteúdo foi encontrado na lixeira',
	            'all_items'             => 'Tudo'
	        );
	        register_post_type( $cpt, $attr );
	    }
	}

	public function create_tax( $slug, $post_type, $args )
	{
	    $taxs[$slug] = $args;

	    $attr_default = array(
	        'show_in_nav_menus'     => true,
	        'show_ui'               => true,
	        'show_tagcloud'         => false,
	        'hierarchical'          => true
	    );

	    foreach ( $taxs as $tax => $attr ) {
	        extract( $attr );
	        $attr['labels'] = array(
	            'name'              => $label_plural,
	            'singular_name'     => $label,
	            'search_items'      => 'Pesquisar ' . $label_plural,
	            'all_items'         => 'Tudo ' . $label_plural,
	            'parent_item'       => $label . ' acima',
	            'parent_item_colon' => $label . ' acima:',
	            'edit_item'         => 'Editar ' . $label_plural,
	            'update_item'       => 'Atualizar ' . $label,
	            'add_new_item'      => 'Adicionar ' . $label,
	            'new_item_name'     => 'Adicionar ' . $label,
	            'menu_name'         => $label_plural
	        );
	        $attr = array_merge( $attr_default, $attr );
	        register_taxonomy( $tax, $post_type, $attr );
	    }
	}

	// Custom Post Name
	public function set_cpt_name( $cpt_name )
	{
		$this->cpt_name = $cpt_name;
	}
	public function cpt_name()
	{
		return $this->cpt_name;
	}

	// Taxonomy Name
	public function set_tax_name( $tax_name )
	{
		$this->tax_name = $tax_name;
	}
	public function tax_name()
	{
		return $this->tax_name;
	}

	// Save Post Metabox
	public function metabox_save( $post_id )
	{
		if ( !isset( $_POST[MI_PREFIX . 'meta_box_nonce'] ) ) { return; }
		if ( !wp_verify_nonce( $_POST[MI_PREFIX . 'meta_box_nonce'], MI_PREFIX . 'meta_box' ) ) { return; }
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) { return; }
		if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {
			if ( !current_user_can( 'edit_page', $post_id ) ) { return; }
		} else {
			if ( !current_user_can( 'edit_post', $post_id ) ) { return; }
		}

		$fields = explode( ',', $_POST['all_fields'] );

		foreach ( $fields as $id => $field ) {
			// Salva data no formato timestamp
			if ( preg_match( '/^\d{1,2}\/\d{1,2}\/\d{4}$/', $_POST[$field] ) ) {
				$_POST[$field] = strtotime( str_replace( '/', '-', $_POST[$field] ) );
			} else {
				$_POST[$field] = $_POST[$field];
			}

			add_post_meta( $post_id, MI_PREFIX . $field, $_POST[$field], true ) or update_post_meta( $post_id, MI_PREFIX . $field, $_POST[$field] );
		}
	}
}
