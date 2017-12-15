<?php
class Form
{
	private $id;
	private $field;
	private $value;
	private $the_field;

	function __construct( $id, $field, $value=null )
	{
		$this->set_id( $id );
		$this->set_fields_supports( $field );
		$this->set_value( $value );

		switch ( $this->field->type ) {
			case 'text':
				$this->set_text();
				break;
			case 'number':
				$this->set_number();
				break;
			case 'textarea':
				$this->set_textarea();
				break;
			case 'select':
				$this->set_select();
				break;
			case 'checkbox':
				$this->set_checkbox();
				break;
			case 'radio':
				$this->set_radio();
				break;
			case 'date':
				$this->set_date();
				break;
			case 'hidden':
				$this->set_hidden();
				break;
			case 'button':
				$this->set_button();
				break;
		}
	}

	// Text
	private function set_text()
	{
		$html .= '<input name="' . $this->id() . '" id="' . $this->field->id . '" value="' .  $this->get_value() . '" class="large-text ' . $this->field->class . '" type="text" ' . $this->field->required . ' ' . $this->field->disabled . '>';

		$this->set_field( $html );
	}

	// Number
	private function set_number()
	{
		$html .= '
			<input name="' . $this->id() . '" id="' . $this->field->id . '" value="' . $this->get_value() . '" class="regular-text ' . $this->field->class . '" type="number" ' . $this->field->required . ' ' . $this->field->min . ' ' . $this->field->max . ' ' . $this->field->disabled . '>';

		$this->set_field( $html );
	}

	// Textarea
	private function set_textarea()
	{
		$html .= '
			<textarea name="' . $this->id() . '" id="' . $this->fields->id . '" class="large-text ' . $this->field->class . '" rows="3" ' . $this->fields->required . ' ' . $this->field->disabled . '>' . $this->get_value() . '</textarea>
		';

		$this->set_field( $html );
	}

	// Select
	private function set_select()
	{
		$html .= '
			<select name="' . $this->id() .'" id="' . $this->field->id . '" class="regular-text ' . $this->field->class . '" ' . $this->field->required . ' ' . $this->field->disabled . '>
				<option value=""></option>
		';
		if ( $this->field->opt ) {
			foreach ( $this->field->opt as $val => $label ) {
				$selected = ( $val == $this->get_value() ) ? 'selected' : '';
				$html .= '<option value="' . $val . '" ' . $selected . '>' . $label . '</option>';
			}
		}
		$html .= '
			</select>
		';

		$this->set_field( $html );
	}

	// Checkbox
	private function set_checkbox()
	{
		if ( $this->field->opt ) {
			foreach ( $this->field->opt as $val => $label ) {
				if ( $this->get_value() ) {
					$checked = ( in_array( $val, $this->get_value() ) ) ? 'checked="checked"' : '';
				}

				$html .= '
					<p>
						<label><input type="checkbox" name="' . $this->id() . '[]" id="' . $val . '" value="' . $val . '" class="tog ' . $this->field->class . '" ' . $checked . ' ' . $this->field->disabled . '>' . $label . '</label>
					</p>
				';
			}

			$this->set_field( $html );
		}
	}

	// Radio
	private function set_radio()
	{
		if ( $this->field->opt ) {
			foreach ( $this->field->opt as $val => $label ) {
				$checked 	= ( $val == $this->get_value() ) ? 'checked="checked"' : '';
				$html 		.= '
					<p>
						<label><input type="radio" name="' . $this->id() . '" id="' . $val . '" value="' . $val . '" class="tog ' . $this->field->class . '" ' . $checked . ' ' . $this->field->disabled . '>' . $label . '</label>
					</p>
				';
			}

			$this->set_field( $html );
		}
	}

	// Date
	private function set_date()
	{
		$val = $this->get_value() ? date( 'd/m/Y', $this->get_value() ) : '';
		
		$html .= '<input name="' . $this->id() . '" id="' . $this->id() . '" value="' . $val . '" class="regular-text date-field ' . $this->field->class . '" type="text" ' . $this->field->required . ' ' . $this->field->disabled . '>';

		$this->set_field( $html );
	}

	// Hidden Field
	private function set_hidden()
	{
		$html .= '<input name="' . $this->id() . '" id="' . $this->id() . '" value="' . $this->get_value() . '" class="regular-text' . $this->field->class . '" type="hidden" ' . $this->field->required . '>';

		$this->set_field( $html );
	}

	// Button
	private function set_button()
	{
		$html .= '<input name="' . $this->id() . '" id="' . $this->id() . '" class=" ' . $this->field->class . '" value="' . $this->field->value . '" type="button">';

		$this->set_field( $html );
	}

	// ID
	private function set_id( $id )
	{
		$this->id = $id;
	}
	public function id()
	{
		return $this->id;
	}

	// Value
	private function set_value( $value )
	{
		if ( $this->field->type == 'checkbox' ) {
			$this->value = $value;
		} else {
			@$this->value = $value ? esc_attr( stripslashes( $value ) ) : $this->field->value;
		}
	}
	public function get_value()
	{
		return $this->value;
	}

	// Return Field
	private function set_field( $field )
	{
		$this->the_field = $field;
	}
	public function field()
	{
		return $this->the_field;
	}
	public function label()
	{
		return $this->field->label;
	}
	public function desc()
	{
		return $this->field->desc;
	}


	// Fields Supports
	/*
	$field = array(
		type		=> text, number, textarea, select, checkbox, radio, date, file, hidden, button
		required 	=> true, // Default false
		id 			=> String,
		opt 		=> array( value => Label ),
		min 		=> Int,
		max 		=> Int,
		class 		=> String,
		label 		=> String,
		desc 		=> String,
		disabled 	=> true, // Default false
		value 		=> String
	);
	*/
	private function set_fields_supports( $field )
	{
		$this->field = new stdClass();
		foreach ( $field as $key => $val ) {
			switch ( $key ) {
				case 'type':
					$this->field->type = $val;
					break;
				case 'required':
					$this->field->required = ( $val == true ) ? 'required' : '';
					break;
				case 'id':
					$this->field->id = ( $val != $this->id ) ? 'id="' . $val . '"' : 'id="' . $this->id . '"';
					break;
				case 'opt':
					if ( $val ) {
						$this->field->opt = new stdClass();
						foreach ( $val as $id => $label ) {
							if ( $id ) {
								$this->field->opt->$id = $label;
							}
						}
					}
					break;
				case 'min':
					$this->field->min = $val ? 'min="' . $val . '"' : '';
					break;
				case 'max':
					$this->field->max = $val ? 'max="' . $val . '"' : '';
					break;
				case 'class':
					$this->field->class = $val ? $val : '';
					break;
				case 'label':
					$this->field->label = $val;
					break;
				case 'desc':
					$this->field->desc = $val;
					break;
				case 'disabled':
					$this->field->disabled = ( $val == true ) ? 'disabled' : '';
					break;
				case 'value':
					$this->field->value = $val;
					break;
			}
		}
	}
}
