<?php
/**
 * Class Actions
 *
 * @package   Ensemble\Components\Classes\Admin
 * @copyright Copyright (c) 2018, Drew Jaynes
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.0.0
 */
namespace Ensemble\Components\Classes\Admin;

use Ensemble\Components\People\Directors;
use Ensemble\Core\Interfaces\Loader;
use function Ensemble\html;

/**
 * Sets up logic for performing actions for classes.
 *
 * @since 1.0.0
 *
 * @see Loader
 */
class Actions implements Loader {

	/**
	 * Registers hook callbacks for classes actions.
	 *
	 * @since 1.0.0
	 */
	public function load() {
		// Classes > Add and > Edit fields.
		add_action( 'ensemble_class_add_form_fields', array( $this, 'add_class_fields'  ) );
		add_action( 'ensemble_class_edit_form',       array( $this, 'edit_class_fields' ) );

		// Classes > Add List table columns.
		add_filter( 'manage_edit-ensemble_class_columns',  array( $this, 'filter_class_table_columns' ),   100 );
		add_filter( 'manage_ensemble_class_custom_column', array( $this, 'column_city'               ), 11, 3 );
		add_filter( 'manage_ensemble_class_custom_column', array( $this, 'column_directors'          ), 12, 3 );

		// Save custom meta on add and edit.
		add_action( 'create_ensemble_class', array( $this, 'add_class_save_meta' ) );
		add_action( 'edit_ensemble_class',   array( $this, 'edit_class_save_meta' ) );
	}

	/**
	 * Inserts custom fields markup into the Add Unit form.
	 *
	 * @since 1.0.0
	 *
	 * @param string $taxonomy Taxonomy.
	 */
	public function add_class_fields() {
		?>
		<div class="form-field bootstrap-iso w-95 fs-13">
			<?php
			$this->output_city_field();
			$this->output_directors_field();
			?>
		</div>
		<?php
	}

	/**
	 * Inserts custom fields into the Edit Unit form.
	 *
	 * @since 1.0.0
	 *
	 * @param \WP_Term $term Unit term object.
	 */
	public function edit_class_fields( $term ) {
		?>
		<table class="form-table bootstrap-iso">
			<tbody>
			<tr class="form-field">
				<th scope="row">
					<label for="ensemble-city"><?php esc_html_e( 'Home City', 'ensemble' ); ?></label>
				</th>
				<td>
					<?php $this->output_city_field( $term ); ?>
				</td>
			</tr>
			<tr class="form-field">
				<th scope="row">
					<label for="ensemble-directors"><?php esc_html_e( 'Director(s)', 'ensemble' ); ?></label>
				</th>
				<td>
					<?php $this->output_directors_field( $term ); ?>
				</td>
			</tr>
			</tbody>
		</table>
		<?php
	}

	/**
	 * Private helper to output the markup for the Home City field.
	 *
	 * @since 1.0.0
	 *
	 * @param null|\WP_Term $term Optional. Term object. Default null (ignored).
	 */
	private function output_city_field( $term = null ) {
		$args = array(
			'id'    => 'class-city',
			'label' => __( 'Home City', 'ensemble' ),
		);

		if ( null !== $term ) {
			$args['value'] = get_term_meta( $term->term_id, 'ensemble_city', true );

			// If $term is available this is for the Classes > Edit form where the label is output separately.
			unset( $args['label'] );
		}

		// Output the element.
		html()->text( $args );
	}

	/**
	 * Private helper to output the markup for the 'Director(s)' field.
	 *
	 * @since 1.0.0
	 *
	 * @param null|\WP_Term $term Optional. Term object. Default null (ignored).
	 */
	private function output_directors_field( $term = null ) {
		$args = array(
			'id'               => 'class-directors',
			'name'             => 'class-directors[]',
			'label'            => __( 'Director(s)', 'ensemble' ),
			'class'            => array( 'form-control' ),
			'multiple'         => true,
			'options'          => $this->get_directors_as_options(),
			'show_option_all'  => false,
			'show_option_none' => false,
		);

		if ( null !== $term ) {
			$selected = get_term_meta( $term->term_id, 'ensemble-directors', true );
			$args['selected'] = array_map( 'absint', explode( ',', $selected ) );

			// If $term is available this is for the Classes > Edit form where the label is output separately.
			unset( $args['label'] );
		}

		// Output the element.
		html()->select( $args );
	}

	/**
	 * Retrieves a list of director ID\name pairs for use as an options array.
	 *
	 * @since 1.0.0
	 *
	 * @return array Director ID\name pairs if any are found, otherwise an empty array.
	 */
	private function get_directors_as_options() {
		$directors_results = ( new Directors\Database )->query( array(
			'fields' => array( 'ID', 'display_name' ),
			'number' => 500,
		) );

		if ( ! empty( $directors_results ) ) {
			foreach ( $directors_results as $director ) {
				$directors[ $director->ID ] = $director->display_name;
			}
		} else {
			$directors = array();
		}

		return $directors;
	}

	/**
	 * Filters the columns in the Competing Classes list table.
	 *
	 * @since 1.0.0
	 *
	 * @param array $columns List table columns.
	 * @return array Modified columns array.
	 */
	public function filter_class_table_columns( $columns ) {
		global $hook_suffix;

		// For some reason core is evaluating this filter on term.php. Nip that in the bud.
		if ( 'term.php' === $hook_suffix ) {
			return $columns;
		}

		$new_columns = array(
			'cb'        => $columns['cb'],
			'name'      => $columns['name'],
			'city'      => _x( 'City', 'competing class', 'ensemble' ),
			'directors' => _x( 'Director(s)', 'competing class', 'ensemble' ),
		);

		return $new_columns;
	}

	/**
	 * Renders the contents of a single 'City' column row in the Classes list table.
	 *
	 * @since 1.0.0
	 *
	 * @param string $string      Blank string.
	 * @param string $column_name Name of the column.
	 * @param int    $term_id     Term ID.
	 * @return string Markup for the column row.
	 */
	public function column_city( $value, $column_name, $class_id ) {
		if ( 'city' !== $column_name ) {
			return $value;
		}

		$value = 'City';
		return $value;
	}

	/**
	 * Renders the contents of a single 'Director(s)' column row in the Classes list table.
	 *
	 * @since 1.0.0
	 *
	 * @param string $string      Blank string.
	 * @param string $column_name Name of the column.
	 * @param int    $term_id     Term ID.
	 * @return string Markup for the column row.
	 */
	public function column_directors( $value, $column_name, $class_id ) {
		if ( 'directors' !== $column_name ) {
			return $value;
		}

		$value = 'Director1, Director2';

		return $value;
	}

	/**
	 * Handles saving custom term meta fields when a new class is added.
	 *
	 * @since 1.0.0
	 *
	 * @param int $class_id Unit term ID.
	 */
	public function add_class_save_meta( $class_id ) {
		$city      = $_REQUEST['class-city'] ?? '';
		$directors = $_REQUEST['class-directors'] ?? array();

		log_it( $city );
		log_it( $directors );
	}

	/**
	 * Handles saving custom term meta fields when a class is updated.
	 *
	 * @since 1.0.0
	 *
	 * @param int $class_id Unit term ID.
	 */
	public function edit_class_save_meta( $class_id ) {
		$city      = $_REQUEST['class-city'] ?? '';
		$directors = $_REQUEST['class-directors'] ?? array();

		log_it( $city );
		log_it( $directors );
	}
}
