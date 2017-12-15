<?php
class CPTTest extends CustomPost
{
    function __construct()
    {
        $this->set_cpt_name( MI_CPT_TEST );
		
        $this->create_cpt( $this->cpt_name(), array(
		    'label'         => 'Teste',
		    'label_plural'  => 'Testes',
		    'public'        => true,
		    'show_ui'       => true,
		    'has_archive'   => true,
		    'supports'      => array( 'title', 'thumbnail', 'excerpt', 'editor', 'revisions' )
		));

		if ( is_admin() ) {
			add_action( 'add_meta_boxes', 	array( $this, 'metabox_init' ) );
			add_action( 'save_post', 		array( $this, 'metabox_save' ) );
        }
    }

    public function metabox_init()
    {
        add_meta_box( $this->cpt_name() . '_info', 'Informações Adicionais', array( $this, 'meta_info' ), $this->cpt_name(), 'normal', 'high' );
        add_meta_box( $this->cpt_name() . '_info2', 'Informações Adicionais 2', array( $this, 'meta_info2' ), $this->cpt_name(), 'normal', 'high' );
    }

    // Metaboxes Info
    public function meta_info( $post )
	{
		wp_nonce_field( MI_PREFIX . 'meta_box', MI_PREFIX . 'meta_box_nonce' );

		$fields = array(
			'text'	=> array(
				'type'	=> 'text',
				'label'	=> 'Text Field'
			)
		);

		$html .= '
			<table class="form-table">
				<tbody>
		';
		$all_fields = '';
		foreach ( $fields as $name => $args ) {
			$form 	= new Form( $name, $args, get_post_meta( $post->ID, MI_PREFIX . $name, true ) );
			$html 	.= '
					<tr>
						<th scope="row"><label for="' . $form->id() . '">' . $form->label() . '</label></th>
						<td>
							' . $form->field() . '
							<p class="description">' . $form->desc() . '</p>
						</td>
					</tr>
			';

			$all_fields .= $all_fields ? ',' . $name : $name;
		}
		$html .= '
				</tbody>
			</table>
		';

		echo $html;
    }
    
    // Metaboxes Info 2
    public function meta_info2( $post )
	{
		wp_nonce_field( MI_PREFIX . 'meta_box', MI_PREFIX . 'meta_box_nonce' );

		$fields = array(
			'text1'	=> array(
				'type'	=> 'text',
				'label'	=> 'Text Field 2'
			)
		);

		$html .= '
			<table class="form-table">
				<tbody>
		';

        foreach ( $fields as $name => $args ) {
			$form 	= new Form( $name, $args, get_post_meta( $post->ID, MI_PREFIX . $name, true ) );
			$html 	.= '
					<tr>
						<th scope="row"><label for="' . $form->id() . '">' . $form->label() . '</label></th>
						<td>
							' . $form->field() . '
							<p class="description">' . $form->desc() . '</p>
						</td>
					</tr>
			';

			$all_fields .= $all_fields ? ',' . $name : $name;
		}

		$form = new Form( 'all_fields', array( 'type' => 'hidden' ), $all_fields );
		$html .= $form->field();
		$html .= '
				</tbody>
			</table>
		';

		echo $html;
	}
}
