<?php

// exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit;


// check if class already exists
if( !class_exists('acf_field_postrating') ) :


class acf_field_postrating extends acf_field {


	/*
	*  __construct
	*
	*  This function will setup the field type data
	*
	*  @type	function
	*  @date	5/03/2014
	*  @since	5.0.0
	*
	*  @param	n/a
	*  @return	n/a
	*/

	function __construct( $settings ) {

		/*
		*  name (string) Single word, no spaces. Underscores allowed
		*/

		$this->name = 'postrating';


		/*
		*  label (string) Multiple words, can include spaces, visible when selecting a field type
		*/

		$this->label = __('Post Ratings', 'acf-postrating');


		/*
		*  category (string) basic | content | choice | relational | jquery | layout | CUSTOM GROUP NAME
		*/

		$this->category = 'choice';


		/*
		*  defaults (array) Array of default settings which are merged into the field object. These are used later in settings
		*/

		$this->defaults = array(
			'layout'			=> 'vertical',
			'choices'			=> array(),
			'default_value'		=> '',
			'other_choice'		=> 0,
			'save_other_choice'	=> 0,
			'allow_null' 		=> 0,
			'return_format'		=> 'value'
		);


		/*
		*  l10n (array) Array of strings that are used in JavaScript. This allows JS strings to be translated in PHP and loaded via:
		*  var message = acf._e('postrating', 'error');
		*/

		$this->l10n = array(
			'error'	=> __('Error! Please enter a higher value', 'acf-postrating'),
		);


		/*
		*  settings (array) Store plugin settings (url, path, version) as a reference for later use with assets
		*/

		$this->settings = $settings;


		// do not delete!
    	parent::__construct();

	}



	/*
	*  render_field_settings()
	*
	*  Create extra options for your field. This is rendered when editing a field.
	*  The value of $field['name'] can be used (like bellow) to save extra data to the $field
	*
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$field	- an array holding all the field's data
	*/

	function render_field_settings( $field ) {

		// encode choices (convert from array)
		$field['choices'] = acf_encode_choices($field['choices']);


		// choices
		acf_render_field_setting( $field, array(
			'label'			=> __('Choices','acf'),
			'instructions'	=> __('Enter each choice on a new line.','acf') . '<br /><br />' . __('For more control, you may specify both a value and label like this:','acf'). '<br /><br />' . __('red : Red','acf'),
			'type'			=> 'textarea',
			'name'			=> 'choices',
		));


		// allow_null
		acf_render_field_setting( $field, array(
			'label'			=> __('Allow Null?','acf'),
			'instructions'	=> '',
			'type'			=> 'radio',
			'name'			=> 'allow_null',
			'choices'		=> array(
				1				=> __("Yes",'acf'),
				0				=> __("No",'acf'),
			),
			// 'layout'	=>	'horizontal',
		));


		// other_choice
		acf_render_field_setting( $field, array(
			'label'			=> __('Other','acf'),
			'instructions'	=> '',
			'type'			=> 'true_false',
			'name'			=> 'other_choice',
			'message'		=> __("Add 'other' choice to allow for custom values", 'acf')
		));


		// save_other_choice
		acf_render_field_setting( $field, array(
			'label'			=> __('Save Other','acf'),
			'instructions'	=> '',
			'type'			=> 'true_false',
			'name'			=> 'save_other_choice',
			'message'		=> __("Save 'other' values to the field's choices", 'acf')
		));


		// default_value
		acf_render_field_setting( $field, array(
			'label'			=> __('Default Value','acf'),
			'instructions'	=> __('Appears when creating a new post','acf'),
			'type'			=> 'text',
			'name'			=> 'default_value',
		));


		// layout
		acf_render_field_setting( $field, array(
			'label'			=> __('Layout','acf'),
			'instructions'	=> '',
			'type'			=> 'radio',
			'name'			=> 'layout',
			'layout'		=> 'horizontal',
			'choices'		=> array(
				'vertical'		=> __("Vertical",'acf'),
				'horizontal'	=> __("Horizontal",'acf')
			)
		));


		// return_format
		acf_render_field_setting( $field, array(
			'label'			=> __('Return Value','acf'),
			'instructions'	=> __('Specify the returned value on front end','acf'),
			'type'			=> 'radio',
			'name'			=> 'return_format',
			'layout'		=> 'horizontal',
			'choices'		=> array(
				'value'			=> __('Value','acf'),
				'label'			=> __('Label','acf'),
				'array'			=> __('Both (Array)','acf')
			)
		));

	}

	/*
	*  render_field()
	*
	*  Create the HTML interface for your field
	*
	*  @param	$field (array) the $field being rendered
	*
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$field (array) the $field being edited
	*  @return	n/a
	*/


	function render_field( $field ) {

		// vars
		$i = 0;
		$e = '';
		$ul = array(
			'class'				=> 'acf-postrating-list',
			'data-allow_null'	=> $field['allow_null'],
			'data-other_choice'	=> $field['other_choice']
		);


		// append to class
		$ul['class'] .= ' ' . ($field['layout'] == 'horizontal' ? 'acf-hl' : 'acf-bl');
		$ul['class'] .= ' ' . $field['class'];


		// select value
		$checked = '';
		$value = strval($field['value']);


		// selected choice
		if( isset($field['choices'][ $value ]) ) {

			$checked = $value;

		// custom choice
		} elseif( $field['other_choice'] && $value !== '' ) {

			$checked = 'other';

		// allow null
		} elseif( $field['allow_null'] ) {

			// do nothing

		// select first input by default
		} else {

			$checked = key($field['choices']);

		}


		// ensure $checked is a string (could be an int)
		$checked = strval($checked);


		// other choice
		if( $field['other_choice'] ) {

			// vars
			$input = array(
				'type'		=> 'text',
				'name'		=> $field['name'],
				'value'		=> '',
				'disabled'	=> 'disabled'
			);


			// select other choice if value is not a valid choice
			if( $checked === 'other' ) {

				unset($input['disabled']);
				$input['value'] = $field['value'];

			}


			// append other choice
			$field['choices']['other'] = '</label><input type="text" ' . acf_esc_attr($input) . ' /><label>';

		}


		// bail early if no choices
		if( empty($field['choices']) ) return;


		// hiden input
		$e .= acf_get_hidden_input( array('name' => $field['name']) );


		// open
		$e .= '<div ' . acf_esc_attr($ul) . '>';


		// foreach choices
		foreach( $field['choices'] as $value => $label ) {

			// ensure value is a string
			$value = strval($value);
			$class = '';


			// increase counter
			$i++;


			// vars
			$atts = array(
				'type'	=> 'radio',
				'id'	=> $field['id'],
				'name'	=> $field['name'],
				'value'	=> $value
			);


			// checked
			if( $value === $checked ) {

				$atts['checked'] = 'checked';
				$class = ' class="selected"';

			}


			// deisabled
			if( isset($field['disabled']) && acf_in_array($value, $field['disabled']) ) {

				$atts['disabled'] = 'disabled';

			}


			// id (use crounter for each input)
			if( $i > 1 ) {

				$atts['id'] .= '-' . $value;

			}


			// append
			$e .= '<input ' . acf_esc_attr( $atts ) . '/><label' . $class . '></label>';

		}


		// close
		 $e .= '</div>';


		// return
		echo $e;

	}


	/*
	*  input_admin_enqueue_scripts()
	*
	*  This action is called in the admin_enqueue_scripts action on the edit screen where your field is created.
	*  Use this action to add CSS + JavaScript to assist your render_field() action.
	*
	*  @type	action (admin_enqueue_scripts)
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	n/a
	*  @return	n/a
	*/

	/*

	function input_admin_enqueue_scripts() {

		// vars
		$url = $this->settings['url'];
		$version = $this->settings['version'];


		// register & include JS
		wp_register_script( 'acf-input-postrating', "{$url}assets/js/input.js", array('acf-input'), $version );
		wp_enqueue_script('acf-input-postrating');


		// register & include CSS
		wp_register_style( 'acf-input-postrating', "{$url}assets/css/input.css", array('acf-input'), $version );
		wp_enqueue_style('acf-input-postrating');

	}

	*/


	/*
	*  input_admin_head()
	*
	*  This action is called in the admin_head action on the edit screen where your field is created.
	*  Use this action to add CSS and JavaScript to assist your render_field() action.
	*
	*  @type	action (admin_head)
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	n/a
	*  @return	n/a
	*/

	/*

	function input_admin_head() {



	}

	*/


	/*
   	*  input_form_data()
   	*
   	*  This function is called once on the 'input' page between the head and footer
   	*  There are 2 situations where ACF did not load during the 'acf/input_admin_enqueue_scripts' and
   	*  'acf/input_admin_head' actions because ACF did not know it was going to be used. These situations are
   	*  seen on comments / user edit forms on the front end. This function will always be called, and includes
   	*  $args that related to the current screen such as $args['post_id']
   	*
   	*  @type	function
   	*  @date	6/03/2014
   	*  @since	5.0.0
   	*
   	*  @param	$args (array)
   	*  @return	n/a
   	*/

   	/*

   	function input_form_data( $args ) {



   	}

   	*/


	/*
	*  input_admin_footer()
	*
	*  This action is called in the admin_footer action on the edit screen where your field is created.
	*  Use this action to add CSS and JavaScript to assist your render_field() action.
	*
	*  @type	action (admin_footer)
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	n/a
	*  @return	n/a
	*/

	/*

	function input_admin_footer() {



	}

	*/


	/*
	*  field_group_admin_enqueue_scripts()
	*
	*  This action is called in the admin_enqueue_scripts action on the edit screen where your field is edited.
	*  Use this action to add CSS + JavaScript to assist your render_field_options() action.
	*
	*  @type	action (admin_enqueue_scripts)
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	n/a
	*  @return	n/a
	*/

	/*

	function field_group_admin_enqueue_scripts() {

	}

	*/


	/*
	*  field_group_admin_head()
	*
	*  This action is called in the admin_head action on the edit screen where your field is edited.
	*  Use this action to add CSS and JavaScript to assist your render_field_options() action.
	*
	*  @type	action (admin_head)
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	n/a
	*  @return	n/a
	*/

	/*

	function field_group_admin_head() {

	}

	*/


	/*
	*  load_value()
	*
	*  This filter is applied to the $value after it is loaded from the db
	*
	*  @type	filter
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$value (mixed) the value found in the database
	*  @param	$post_id (mixed) the $post_id from which the value was loaded
	*  @param	$field (array) the field array holding all the field options
	*  @return	$value
	*/

	/*

	function load_value( $value, $post_id, $field ) {

		return $value;

	}

	*/


	/*
	*  update_value()
	*
	*  This filter is applied to the $value before it is saved in the db
	*
	*  @type	filter
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$value (mixed) the value found in the database
	*  @param	$post_id (mixed) the $post_id from which the value was loaded
	*  @param	$field (array) the field array holding all the field options
	*  @return	$value
	*/

	/*

	function update_value( $value, $post_id, $field ) {

		return $value;

	}

	*/


	/*
	*  format_value()
	*
	*  This filter is appied to the $value after it is loaded from the db and before it is returned to the template
	*
	*  @type	filter
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$value (mixed) the value which was loaded from the database
	*  @param	$post_id (mixed) the $post_id from which the value was loaded
	*  @param	$field (array) the field array holding all the field options
	*
	*  @return	$value (mixed) the modified value
	*/

	/*

	function format_value( $value, $post_id, $field ) {

		// bail early if no value
		if( empty($value) ) {

			return $value;

		}


		// apply setting
		if( $field['font_size'] > 12 ) {

			// format the value
			// $value = 'something';

		}


		// return
		return $value;
	}

	*/


	/*
	*  validate_value()
	*
	*  This filter is used to perform validation on the value prior to saving.
	*  All values are validated regardless of the field's required setting. This allows you to validate and return
	*  messages to the user if the value is not correct
	*
	*  @type	filter
	*  @date	11/02/2014
	*  @since	5.0.0
	*
	*  @param	$valid (boolean) validation status based on the value and the field's required setting
	*  @param	$value (mixed) the $_POST value
	*  @param	$field (array) the field array holding all the field options
	*  @param	$input (string) the corresponding input name for $_POST value
	*  @return	$valid
	*/

	/*

	function validate_value( $valid, $value, $field, $input ){

		// Basic usage
		if( $value < $field['custom_minimum_setting'] )
		{
			$valid = false;
		}


		// Advanced usage
		if( $value < $field['custom_minimum_setting'] )
		{
			$valid = __('The value is too little!','acf-postrating'),
		}


		// return
		return $valid;

	}

	*/


	/*
	*  delete_value()
	*
	*  This action is fired after a value has been deleted from the db.
	*  Please note that saving a blank value is treated as an update, not a delete
	*
	*  @type	action
	*  @date	6/03/2014
	*  @since	5.0.0
	*
	*  @param	$post_id (mixed) the $post_id from which the value was deleted
	*  @param	$key (string) the $meta_key which the value was deleted
	*  @return	n/a
	*/

	/*

	function delete_value( $post_id, $key ) {



	}

	*/


	/*
	*  load_field()
	*
	*  This filter is applied to the $field after it is loaded from the database
	*
	*  @type	filter
	*  @date	23/01/2013
	*  @since	3.6.0
	*
	*  @param	$field (array) the field array holding all the field options
	*  @return	$field
	*/

	/*

	function load_field( $field ) {

		return $field;

	}

	*/


	/*
	*  update_field()
	*
	*  This filter is appied to the $field before it is saved to the database
	*
	*  @type	filter
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$field - the field array holding all the field options
	*  @param	$post_id - the field group ID (post_type = acf)
	*
	*  @return	$field - the modified field
	*/

	function update_field( $field ) {

		// decode choices (convert to array)
		$field['choices'] = acf_decode_choices($field['choices']);


		// return
		return $field;
	}



	/*
	*  update_value()
	*
	*  This filter is appied to the $value before it is updated in the db
	*
	*  @type	filter
	*  @since	3.6
	*  @date	23/01/13
	*  @todo	Fix bug where $field was found via json and has no ID
	*
	*  @param	$value - the value which will be saved in the database
	*  @param	$post_id - the $post_id of which the value will be saved
	*  @param	$field - the field array holding all the field options
	*
	*  @return	$value - the modified value
	*/

	function update_value( $value, $post_id, $field ) {

		// bail early if no value (allow 0 to be saved)
		if( !$value && !is_numeric($value) ) return $value;


		// save_other_choice
		if( $field['save_other_choice'] ) {

			// value isn't in choices yet
			if( !isset($field['choices'][ $value ]) ) {

				// get raw $field (may have been changed via repeater field)
				// if field is local, it won't have an ID
				$selector = $field['ID'] ? $field['ID'] : $field['key'];
				$field = acf_get_field( $selector, true );


				// bail early if no ID (JSON only)
				if( !$field['ID'] ) return $value;


				// update $field
				$field['choices'][ $value ] = $value;


				// save
				acf_update_field( $field );

			}

		}


		// return
		return $value;
	}


	/*
	*  load_value()
	*
	*  This filter is appied to the $value after it is loaded from the db
	*
	*  @type	filter
	*  @since	5.2.9
	*  @date	23/01/13
	*
	*  @param	$value - the value found in the database
	*  @param	$post_id - the $post_id from which the value was loaded from
	*  @param	$field - the field array holding all the field options
	*
	*  @return	$value - the value to be saved in te database
	*/

	function load_value( $value, $post_id, $field ) {

		// must be single value
		if( is_array($value) ) {

			$value = array_pop($value);

		}


		// return
		return $value;

	}


	/*
	*  translate_field
	*
	*  This function will translate field settings
	*
	*  @type	function
	*  @date	8/03/2016
	*  @since	5.3.2
	*
	*  @param	$field (array)
	*  @return	$field
	*/

	function translate_field( $field ) {

		return acf_get_field_type('select')->translate_field( $field );

	}


	/*
	*  format_value()
	*
	*  This filter is appied to the $value after it is loaded from the db and before it is returned to the template
	*
	*  @type	filter
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$value (mixed) the value which was loaded from the database
	*  @param	$post_id (mixed) the $post_id from which the value was loaded
	*  @param	$field (array) the field array holding all the field options
	*
	*  @return	$value (mixed) the modified value
	*/

	function format_value( $value, $post_id, $field ) {

		return acf_get_field_type('select')->format_value( $value, $post_id, $field );

	}


}


// initialize
new acf_field_postrating( $this->settings );


// class_exists check
endif;

?>
