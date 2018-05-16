<?php
/**
 * HTML elements class
 *
 * This class incorporates work originating from the EDD_HTML_Elements class, bundled
 * with the Easy Digital Downloads plugin, (c) 2015, Pippin Williamson.
 *
 * This class incorporates work originating from the SellBird\Util\HTML class, bundled
 * with the SellBird platform, (c) 2018, Sandhills Development, LLC
 *
 * @package   Ensemble\Util
 * @copyright Copyright (c) 2018, Drew Jaynes
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.0.0
 */
namespace Ensemble\Util;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Helper class for outputting common HTML elements, such as for forms.
 *
 * @since 1.0.0
 */
class HTML {

	/**
	 * Element type, e.g. radio, text, number, etc.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	private $type = '';

	/**
	 * Holds stringified attributes to be built into $output.
	 *
	 * @since 1.0.0
	 * @var   string[]
	 */
	private $atts = array();

	/**
	 * Renders an HTML select (drop-down).
	 *
	 * @since 1.0.0
	 *
	 * @param array $args {
	 *     Optional. Arguments for displaying the select element.
	 *
	 *     @type string       $id               ID attribute to use for the drop-down.
	 *     @type string       $name             Name attribute to use for the drop-down.
	 *     @type string|array $class            Class or array of classes to use for the drop-down.
	 *     @type string       $label            Label for the drop-down. Default empty.
	 *     @type string|int   $selected         Default value to output as "selected".
	 *     @type string       $multiple         Whether this should be considered a multi-select element.
	 *     @type array        $options          Options for the select in value/label pairs.
	 *     @type string       $placeholder      Placeholder attribute to use for the drop-down.
	 *     @type string       $show_option_all  Label to show for the 'All' option.
	 *     @type string       $show_option_none Label to show when there are no options.
	 *     @type array        $data             Array of name/value data attributes to add to the drop-down.
	 *     @type bool         $readonly         Whether the drop-down should be output as 'readonly'. Default false.
	 *     @type bool         $disabled         Whether the drop-down should be output as 'disabled'. Default false.
	 * }
	 * @param bool  $echo Optional. Whether to echo the output. Default true (echo).
	 * @return string|void HTML markup if `$echo` is false, otherwise void (echo).
	 */
	public function select( $args = array(), $echo = true ) {
		$this->type = 'select';

		$defaults = array(
			'id'               => '',
			'name'             => '',
			'class'            => '',
			'label'            => '',
			'selected'         => '',
			'multiple'         => '',
			'options'          => array(),
			'placeholder'      => '',
			'show_option_all'  => 'All',
			'show_option_none' => 'None',
			'data'             => array(),
			'readonly'         => false,
			'disabled'         => false,
		);

		$args = $this->parse_args( $args, $defaults );

		$options = $output = '';

		if ( ! empty( $args['options'] ) ) {
			if ( ! empty( $args['show_option_none'] ) ) {
				$selected = empty( $args['selected'] ) ? '' : selected( $args['selected'], -1, false );

				$options .= sprintf( '<option value="-1" %1$s>%2$s</option>',
					$selected,
					esc_html( $args['show_option_none'] )
				);
			}

			if ( ! empty( $args['show_option_all'] ) ) {
				$selected = empty( $args['selected'] ) ? '' : selected( $args['selected'], 'all', false );

				$options .= sprintf( '<option value="all" %1$s>%2$s</option>',
					$selected,
					esc_html( $args['show_option_all'] )
				);
			}

			foreach ( $args['options'] as $key => $option ) {
				$selected = empty( $args['selected'] ) ? '' : selected( $args['selected'], $key, false );

				$options .= sprintf( '<option value="%1$s" %2$s>%3$s</option>',
					esc_attr( $key ),
					$selected,
					esc_html( $option )
				);
			}
		}

		if ( ! empty( $args['label'] ) ) {
			$output .= sprintf( '<label for="%1$s">%2$s</label>',
				esc_attr( $args['id'] ),
				esc_html( $args['label'] )
			);
		}

		if ( ! empty( $this->atts ) ) {
			$atts = implode( ' ', $this->atts );

			$output .= sprintf( '<select %1$s>%2$s</select>', $atts, $options );
		} else {
			$output .= sprintf( '<select>%s</select>', $options );
		}

		if ( true === $echo ) {
			echo $output;
		} else {
			return $output;
		}
	}

	/**
	 * Renders an HTML checkbox.
	 *
	 * @since 1.0.0
	 *
	 * @param array $args {
	 *     Optional. Arguments for building the checkbox element.
	 *
	 *     @type string       $id       ID attribute for the checkbox. Default empty.
	 *     @type string       $name     Name attribute for the checkbox. Default empty.
	 *     @type string|array $class    Class attribute for the checkbox. Default empty.
	 *     @type string       $label    Label for the element. Default empty.
	 *     @type bool         $checked  Whether the checkbox element should be checked. Default false.
	 *     @type bool         $disabled Whether the checkbox should be disabled. Default false.
	 *     @type bool         $readonly Whether the checkbox should be set to readonly. Default false.
	 * }
	 * @param bool  $echo Optional. Whether to echo the output. Default false (return).
	 * @return string|void HTML markup if `$echo` is false, otherwise void (echo).
	 */
	public function checkbox( $args = array(), $echo = true ) {
		$this->type = 'checkbox';

		$output = $this->build_checkable( $args, false );

		if ( true === $echo ) {
			echo $output;
		} else {
			return $output;
		}
	}

	/**
	 * Renders an HTML radio button.
	 *
	 * @since 1.0.0
	 *
	 * @param array $args {
	 *     Optional. Arguments for building the radio button element
	 *
	 *     @type string       $id       ID attribute for the radio button. Default empty.
	 *     @type string       $name     Name attribute for the radio button. Default empty.
	 *     @type string|array $class    Class attribute for the radio button. Default empty.
	 *     @type string       $label    Label for the element. Default empty.
	 *     @type bool         $checked  Whether the radio button element should be checked. Default false.
	 *     @type bool         $disabled Whether the radio button should be disabled. Default false.
	 *     @type bool         $readonly Whether the radio button should be set to readonly. Default false.
	 * }
	 * @param bool  $echo Optional. Whether to echo the output. Default true (echo).
	 * @return string|void HTML markup if `$echo` is false, otherwise void (echo).
	 */
	public function radio( $args = array(), $echo = true ) {
		$this->type = 'radio';

		$output = $this->build_checkable( $args, false );

		if ( true === $echo ) {
			echo $output;
		} else {
			return $output;
		}
	}

	/**
	 * Renders a "checkable" HTML element, such as a checkbox or radio button.
	 *
	 * @since 1.0.0
	 *
	 * @param array  $args {
	 *     Optional. Arguments for building the element.
	 *
	 *     @type string       $id          ID attribute for the element. Default empty.
	 *     @type string       $name        Name attribute for the element. Default empty.
	 *     @type string|array $class       Class attribute for the element. Default empty.
	 *     @type string|array $label_class Class attribute for the element's label. Default empty.
	 *     @type string       $label       Label for the element. Default empty.
	 *     @type bool         $checked     Whether the element should be checked. Default false.
	 *     @type bool         $disabled    Whether the element should be set to disabled. Default false.
	 *     @type bool         $readonly    Whether the element should be set to readonly. Default false.
	 * }
	 * @param bool  $echo Optional. Whether to echo the output. Default true (echo).
	 * @return string|void HTML markup if `$echo` is false, otherwise void (echo).
	 */
	private function build_checkable( $args = array(), $echo = true ) {

		if ( ! in_array( $this->type, array( 'checkbox', 'radio' ), true ) ) {
			$this->type = 'checkbox';
		}

		$defaults = array(
			'id'          => '',
			'name'        => '',
			'class'       => '',
			'label'       => '',
			'label_class' => '',
			'checked'     => false,
			'disabled'    => false,
			'readonly'    => false,
		);

		$args = $this->parse_args( $args, $defaults );

		$output = '';

		if ( ! empty( $args['label'] ) ) {
			$label_class = isset( $args['label_class'] ) ? ' class="' . esc_attr( $args['label_class'] ) . '"' : '';

			$output .= sprintf( '<label%s for="%s">', $label_class, esc_attr( $args['id'] ) );
		}

		if ( ! empty( $this->atts ) ) {
			$atts = implode( ' ', $this->atts );

			$output .= sprintf( '<input type="%1$s" %2$s />', esc_attr( $this->type ), $atts );
		} else {
			$output .= sprintf( '<input type="%1$s" />', esc_attr( $this->type ) );
		}

		if ( ! empty( $args['label'] ) ) {
			$output .= sprintf( '%1$s</label>', esc_html( $args['label'] ) );
		}

		if ( true === $echo ) {
			echo $output;
		} else {
			return $output;
		}
	}

	/**
	 * Renders an HTML input.
	 *
	 * @since 1.0.0
	 *
	 * @param string $type Type of input to build. Accepts 'text', 'hidden', 'number', 'button',
	 *                     'file', 'email', 'date', or 'submit'. Default 'text'.
	 * @param array  $args {
	 *     Optional. Arguments for building the text input.
	 *
	 *     @type string       $id           ID attribute for the text input.
	 *     @type string       $name         Name attribute for the text input. Default empty.
	 *     @type string|array $class        Class attribute for the text input. Default empty.
	 *     @type string       $value        Value for the text input. Default empty.
	 *     @type string       $label        Label for the text input. Default empty.
	 *     @type string       $aria_label   Aria label for the input. Default is the value of `$label`.
	 *     @type string       $desc         Description for the text input. Default empty.
	 *     @type int          $min          Minimum value for a number input. Default empty.
	 *     @type int          $max          Maximum value for a number input. Default empty.
	 *     @type string       $step         Step by which number inputs can be incremented. Default '0.01'.
	 *     @type array        $data         Array of name/value data attributes to add to the text input.
	 *     @type string       $placeholder  Placeholder attribute to use for the text input.
	 *     @type string       $autocomplete Whether the input should allow autocomplete ('on') or not ('off').
	 *                                      Default 'on'.
	 *     @type bool         $disabled     Whether the text input should be disabled. Default false.
	 *     @type bool         $readonly     Whether the text input should be set to readonly. Default false.
	 * }
	 * @param bool  $echo Optional. Whether to echo the output. Default true (echo).
	 * @return string|void HTML markup if `$echo` is false, otherwise void (echo).
	 */
	public function input( $type, $args = array(), $echo = true ) {

		$types = array(
			'text', 'hidden', 'number', 'button', 'file',
			'email', 'date', 'submit'
		);

		if ( ! in_array( $type, $types, true ) ) {
			$this->type = 'text';
		} else {
			$this->type = $type;
		}

		$defaults = array(
			'id'           => '',
			'name'         => '',
			'class'        => '',
			'value'        => '',
			'label'        => '',
			'desc'         => '',
			'min'          => '',
			'max'          => '',
			'step'         => '',
			'data'         => array(),
			'placeholder'  => '',
			'autocomplete' => '',
			'disabled'     => false,
			'readonly'     => false,
		);

		$args = $this->parse_args( $args, $defaults );

		$output = '';

		if ( ! empty( $args['label'] ) ) {
			$output .= sprintf( '<label for="%1$s">%2$s</label>',
				esc_attr( $args['id'] ),
				esc_html( $args['label'] )
			);
		}

		if ( ! empty( $args['desc'] ) ) {
			$output .= sprintf( '<span class="description">%s</span>',
				esc_html( $args['desc'] )
			);
		}

		if ( ! empty( $this->atts ) ) {
			$atts = implode( ' ', $this->atts );

			$output .= sprintf( '<input type="%1$s" %2$s />', esc_attr( $this->type ), $atts );
		} else {
			$output .= sprintf( '<input type="%1$s" />', esc_attr( $this->type ) );
		}

		if ( true === $echo ) {
			echo $output;
		} else {
			return $output;
		}
	}

	/**
	 * Renders an HTML text field.
	 *
	 * @since 1.0.0
	 *
	 * @param array $args {
	 *     Optional. Arguments for building the text input.
	 *
	 *     @type string       $id           ID attribute for the text input.
	 *     @type string       $name         Name attribute for the text input. Default empty.
	 *     @type string|array $class        Class attribute for the text input. Default empty.
	 *     @type string       $value        Value for the text input. Default empty.
	 *     @type string       $label        Label for the text input. Default empty.
	 *     @type string       $desc         Description for the text input. Default empty.
	 *     @type array        $data         Array of name/value data attributes to add to the text input.
	 *     @type string       $placeholder  Placeholder attribute to use for the text input.
	 *     @type string       $autocomplete Whether the input should allow autocomplete ('on') or not ('off').
	 *                                      Default 'on'.
	 *     @type bool         $disabled     Whether the text input should be disabled. Default false.
	 *     @type bool         $readonly     Whether the text input should be set to readonly. Default false.
	 * }
	 * @param bool  $echo Optional. Whether to echo the output. Default true (echo).
	 * @return string|void HTML markup if `$echo` is false, otherwise void (echo).
	 */
	public function text( $args = array(), $echo = true ) {
		$output = $this->input( 'text', $args, false );

		if ( true === $echo ) {
			echo $output;
		} else {
			return $output;
		}
	}

	/**
	 * Renders an HTML hidden input.
	 *
	 * @since 1.0.0
	 *
	 * @param array  $args {
	 *     Optional. Arguments for building the hidden input.
	 *
	 *     @type string $id    ID attribute for the hidden input. Default empty.
	 *     @type string $name  Name attribute for the hidden input. Default empty.
	 *     @type mixed  $value Value for the hidden input. Default empty.
	 *     @type array  $data  Array of name/value data attributes to add to the input.
	 * }
	 * @param bool  $echo Optional. Whether to echo the output. Default true (echo).
	 * @return string|void HTML markup if `$echo` is false, otherwise void (echo).
	 */
	public function hidden( $args = array(), $echo = true ) {
		$output = $this->input( 'hidden', $args, false );

		if ( true === $echo ) {
			echo $output;
		} else {
			return $output;
		}
	}

	/**
	 * Renders an HTML number input.
	 *
	 * @since 1.0.0
	 *
	 * @param array  $args {
	 *     Optional. Arguments for building the number input.
	 *
	 *     @type string $id    ID attribute for the number input. Default empty.
	 *     @type string $name  Name attribute for the number input. Default empty.
	 *     @type mixed  $value Value for the number input. Default empty.
	 *     @type int    $min   Minimum value for the number input. Default empty.
	 *     @type int    $max   Maximum value for the number input. Default empty.
	 *     @type int    $step  Step by which numbers can be incremented. Default '0.01'.
	 *     @type array  $data  Array of name/value data attributes to add to the input.
	 * }
	 * @param bool  $echo Optional. Whether to echo the output. Default true (echo).
	 * @return string|void HTML markup if `$echo` is false, otherwise void (echo).
	 */
	public function number( $args = array(), $echo = true ) {
		$output = $this->input( 'number', $args, false );

		if ( true === $echo ) {
			echo $output;
		} else {
			return $output;
		}
	}

	/**
	 * Renders an HTML textarea element.
	 *
	 * @since 1.0.0
	 *
	 * @param array $args {
	 *     Optional. Arguments for building the textarea.
	 *
	 *     @type string       $id       ID attribute for the textarea. Default empty.
	 *     @type string       $name     Name attribute for the textarea. Default empty.
	 *     @type string|array $class    Class attribute for the textarea. Default empty.
	 *     @type string       $value    Value attribute for the textarea. Default empty.
	 *     @type string       $label    Label attribute for the textarea. Default empty.
	 *     @type string       $desc     Description for the textarea. Default empty.
	 *     @type bool         $disabled Whether the textarea should be disabled. Default false.
	 *     @type bool         $readonly Whether the textarea should be set to readonly. Default false.
	 * }
	 * @param bool  $echo Optional. Whether to echo the output. Default true (echo).
	 * @return string|void HTML markup if `$echo` is false, otherwise void (echo).
	 */
	public function textarea( $args = array(), $echo = true ) {
		$defaults = array(
			'id'       => '',
			'name'     => '',
			'class'    => '',
			'value'    => '',
			'label'    => '',
			'desc'     => '',
			'disabled' => false,
			'readonly' => false,
		);

		$args = $this->parse_args( $args, $defaults );

		$value = isset( $args['value'] ) ? $args['value'] : '';

		$output = '';

		if ( ! empty( $args['label'] ) ) {
			$output .= sprintf( '<label for="%1$s">%2$s</label>',
				esc_attr( $args['id'] ),
				esc_html( $args['label'] )
			);
		}

		if ( ! empty( $this->atts ) ) {
			$atts = implode( ' ', $this->atts );
			$output .= sprintf( '<textarea %1$s>%2$s</textarea>', $atts, $value );
		} else {
			$output .= sprintf( '<textarea>%1$s</textarea>', $value );
		}

		if ( ! empty( $args['desc'] ) ) {
			$output .= sprintf( '<span class="description">%s</span>',
				esc_html( $args['desc'] )
			);
		}

		if ( true === $echo ) {
			echo $output;
		} else {
			return $output;
		}
	}

	/**
	 * Renders a rich-text editor control.
	 *
	 * @since 1.0.0
	 *
	 * @param array $args
	 * @param bool  $echo
	 * @return string|void HTML markup for the wp_editor() instance if `$echo` is false, otherwise void (echo).
	 */
	public function editor( $args = array(), $echo = true ) {
		$current_site = get_current_blog_id();

		// Force the main site (otherwise editor script will be enqueued at the sub-site URL).
		if ( ! is_main_site() ) {
			restore_main_site();

			$switched = true;
		} else {
			$switched = false;
		}

		$defaults = array(
			'id'          => '',
			'label'       => '',
			'content'     => '',
			'context'     => 'edit',
			'site'        => $current_site,
			'editor_args' => array(
				'textarea_rows' => 10,
				'media_buttons' => false,
			),
		);

		$args = $this->parse_args( $args, $defaults );

		if ( 'add' === $args['context'] ) {
			// Show the teeny editor only when adding an object (simpler workflow).
			$args['editor_args']['teeny'] = true;
		}

		$output = '';

		if ( ! empty( $args['label'] ) ) {
			$output .= sprintf( '<label for="%1$s">%2$s</label>',
				esc_attr( $args['id'] ),
				esc_html( $args['label'] )
			);
		}

		$content = $args['content'];

		unset( $args['content'] );

		ob_start();

		wp_editor( $content, $args['id'], $args['editor_args'] );

		$output .= ob_get_clean();

		// Switch back to the original site if necessary.
		if ( true === $switched ) {
			switch_to_blog( $current_site );
		}

		if ( true === $echo ) {
			echo $output;
		} else {
			return $output;
		}
	}

	/**
	 * Renders an HTML button element.
	 *
	 * @since 1.0.0
	 *
	 * @param array $args {
	 *     Optional. Arguments for building the textarea.
	 *
	 *     @type string       $id       ID attribute for the button. Default empty.
	 *     @type string|array $class    Class attribute for the button. Default empty.
	 *     @type string       $value    Value attribute for the button. Default empty.
	 *     @type string       $url      URL to redirect the user to via onclick.
	 *     @type bool         $disabled Whether the button should be disabled. Default false.
	 * }
	 * @param bool  $echo Optional. Whether to echo the output. Default true (echo).
	 * @return string|void HTML markup if `$echo` is false, otherwise void (echo).
	 */
	public function button( $args = array(), $echo = true ) {
		$defaults = array(
			'id'       => '',
			'class'    => '',
			'value'    => '',
			'url'      => '',
			'disabled' => false,
		);

		// Retrieve value attributes and remove them from args parsing.
		if ( ! empty( $args['value_atts'] ) ) {
			$value_atts = $args['value_atts'];

			unset( $args['value_atts'] );

			if ( ! empty( $args['value'] ) ) {
				$value_atts['value'] = $args['value'];

				unset( $args['value'] );
			} else {
				$value_atts['value'] = '';
			}
		} else {
			$value_atts = array();
		}

		$args = $this->parse_args( $args, $defaults );

		if ( ! empty( $args['url'] ) ) {
			$this->atts[] = sprintf( 'onclick="window.location=\'%1$s\';"', esc_url( $args['url'] ) );

			unset( $args['url'] );
		}

		$value = $output = '';

		if ( ! empty( $args['value'] ) ) {
			$value = esc_html( $args['value'] );
		} else {
			// Value icon.
			if ( ! empty( $value_atts['icon'] ) ) {
				$value .= $value_atts['icon'];
			}

			// Before value.
			if ( ! empty( $value_atts['before'] ) ) {
				$value .= $value_atts['before'];
			}

			// The actual value.
			$value .= esc_html( $value_atts['value'] );

			// After value;
			if ( ! empty( $value_atts['after'] ) ) {
				$value .= $value_atts['after'];
			}
		}

		if ( ! empty( $this->atts ) ) {
			$atts = implode( ' ', $this->atts );

			$output .= sprintf( '<button type="button" %1$s>%2$s</button>', $atts, $value );
		} else {
			$output .= sprintf( '<button type="button">%1$s</button>', $value );
		}

		if ( true === $echo ) {
			echo $output;
		} else {
			return $output;
		}
	}

	/**
	 * Renders a 'Search' toolbar toggle button element.
	 *
	 * @since 1.0.0
	 *
	 * @see button()
	 *
	 * @param array $args Optional. Arguments for building the 'Search' toolbar toggle button.
	 * @param bool  $echo Optional. Whether to echo the output. Default true (echo).
	 * @return string|void HTML markup if `$echo` is false, otherwise void (echo).
	 */
	public function toolbar_toggle_button( $args = array(), $echo = true ) {
		$defaults = array(
			'role'       => 'button',
			'class'      => array( 'btn', 'btn-link', 'd-flex', 'p-0', 'toolbar-link' ),
			'href'       => '',
			'value'      => 'Search',
			'value_atts' => array(
				'icon'   => '',
				'before' => '<span>',
				'after'  => '</span>',
			),
			'data'       => array(
				'toggle' => 'collapse',
			),
			'aria'       => array(
				'expanded' => 'false',
				'controls' => '',
			),
		);

		$args = array_merge( $defaults, $args );

		if ( empty( $args['href'] ) || empty( $args['aria']['controls'] ) ) {
			return '';
		}

		if ( true === $echo ) {
			echo $this->button( $args );
		} else {
			return $this->button( $args, false );
		}
	}

	/**
	 * Helper for outputting a readonly attribute.
	 *
	 * @since 1.0.0
	 *
	 * @see __checked_selected_helper()
	 *
	 * @param mixed  $helper  One of the values to compare
	 * @param mixed  $current (true) The other value to compare if not just true
	 * @param bool   $echo    Whether to echo or just return the string
	 * @return string|void HTML attribute or empty string if `$echo` is false, otherwise void (echo).
	 */
	public function readonly( $helper, $current, $echo ) {
		if ( true === $echo ) {
			__checked_selected_helper( $helper, $current, $echo, 'readonly' );
		} else {
			return __checked_selected_helper( $helper, $current, $echo, 'readonly' );
		}
	}

	/**
	 * Parses arguments against defaults and sanitizes any attributes.
	 *
	 * @since 1.0.0
	 *
	 * @see wp_parse_args()
	 *
	 * @param string|array|object $args         User-defined arguments to merge with defaults.
	 * @param array               $defaults     Optional. Array that serves as the defaults. Default empty array.
	 * @param bool                $remove_empty Whether to remove empty arguments. Default true.
	 * @return array Sanitized user-defined arguments merged with defaults.
	 */
	private function parse_args( $args, $defaults = array(), $remove_empty = true ) {
		$args = wp_parse_args( $args, $defaults );
		$args = $this->process_args( $args );

		if ( true === $remove_empty ) {
			foreach ( $args as $key => $value ) {
				// Never remove vital attributes id, name, or options, regardless of emptiness.
				if ( in_array( $key, array( 'id', 'name', 'options' ), true ) ) {
					continue;
				}

				if ( empty( $value ) ) {
					unset( $args[ $key ] );
				}
			}
		}

		return $args;
	}

	/**
	 * Sanitizes a given group of attributes.
	 *
	 * @since 1.0.0
	 *
	 * @param array $args All supplied arguments.
	 * @return array Supplied arguments with sanitized attributes according to whitelists.
	 */
	private function process_args( $args ) {

		// Loop through attributes.
		foreach ( $args as $key => $value ) {

			if ( empty( $value ) && false !== $value ) {
				continue;
			}

			switch ( $key ) {

				case 'autocomplete':
					if ( ! in_array( $value, array( 'on', 'off' ), true ) ) {
						$value = 'on';
					}

					$value = $this->sanitize_key( $value );

					$this->attribute_to_string( $key, $value );
					break;

				case 'class':
				case 'label_class':
					if ( ! is_array( $value ) ) {
						$value = array( $value );
					}

					$value = array_map( 'sanitize_html_class', $value );
					$value = implode( ' ', $value );

					$this->attribute_to_string( $key, $value );
					break;

				case 'context':
					if ( ! in_array( $value, array( 'edit', 'add' ) ) ) {
						$value = 'edit';
					}
					break;

				case 'aria':
				case 'data':
					$this->build_hyphenated_atts( $key, $value );
					break;

				case 'options':
					foreach ( $value as $value_key => $sub_value ) {
						$value_key = $this->sanitize_key( $value_key );

						$value[ $value_key ] = $sub_value;
					}
					break;

				case 'show_option_all':
				case 'show_option_none':
					$value = sanitize_text_field( $value );
					break;

				case 'min':
				case 'max':
					$value = intval( $value );

					$this->attribute_to_string( $key, $value );
					break;

				case 'placeholder':
				case 'href':
					$value = sanitize_text_field( $value );

					$this->attribute_to_string( $key, $value );
					break;

				case 'url':
					// Deliberately skip adding URL to $atts (converted to onclick separately and unset).
					$value = sanitize_text_field( $value );

					break;

				// Attributes to leave alone.
				case 'editor_args':
				case 'label':
				case 'site':
					$value = $value;
					break;

				case 'value':
					if ( ! in_array( $this->type, array( 'textarea', 'button' ) ) ) {
						$this->attribute_to_string( $key, $value );
					}
					break;

				case 'disabled':
				case 'readonly':
					if ( false !== $value ) {
						$this->special_to_string( $key, $value );
					}
					break;

				default:
					$value = $this->sanitize_key( $value );

					$this->attribute_to_string( $key, $value );
					break;
			}

			$args[ $key ] = $value;
		}

		return $args;
	}

	/**
	 * Sanitizes a string key for use in an attribute.
	 *
	 * Keys are used as internal identifiers. Alphanumeric characters, dashes,
	 * underscores, stops, colons, square brackets, and slashes are allowed.
	 *
	 * @since 1.0.0
	 *
	 * @param string $key Key to sanitize.
	 * @return string Sanitized key.
	 */
	private function sanitize_key( $key ) {
		return preg_replace( '/[^][a-zA-Z0-9_\-\.\:\/]/', '', $key );
	}

	/**
	 * Converts a key and value pair into an HTML attribute string.
	 *
	 * @since 1.0.0
	 *
	 * @param string $key   Attribute key.
	 * @param mixed  $value Attribute value.
	 * @return string Attribute string.
	 */
	private function attribute_to_string( $key, $value ) {
		$this->atts[] = sprintf( '%1$s="%2$s"', $key, esc_attr( $value ) );
	}

	/**
	 * Converts a special attribute into a string based on the key.
	 *
	 * @since 1.0.0
	 *
	 * @param string $key Attribute key.
	 * @param mixed  $value
	 */
	private function special_to_string( $key, $value ) {
		switch( $key ) {

			case 'disabled':
				$this->atts[] = disabled( 1, $value, false );
				break;

			case 'readonly':
				$this->atts[] = $this->readonly( 1, $value, false );
				break;

			default: break;
		}
	}

	/**
	 * General helper to build hyphenated attribute groups.
	 *
	 * @since 1.0.0
	 *
	 * @param string $group Attribute group.
	 * @param array  $atts  Attribute key/value pairs.
	 */
	private function build_hyphenated_atts( $group, $atts ) {
		foreach ( $atts as $key => $value ) {
			$this->atts[] = sprintf( '%1$s-%2$s="%3$s"',
				$group,
				$this->sanitize_key( $key ),
				esc_attr( $value )
			);
		}
	}
}
