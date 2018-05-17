<?php
/**
 * Unit Actions
 *
 * @package   Ensemble\Components\Units\Admin
 * @copyright Copyright (c) 2018, Drew Jaynes
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.0.0
 */
namespace Ensemble\Components\Units\Admin;

use Ensemble\Components\People\Directors;
use Ensemble\Core\Interfaces\Loader;
use function Ensemble\html;

/**
 * Sets up logic for performing actions for units.
 *
 * @since 1.0.0
 *
 * @see Loader
 */
class Actions implements Loader {

	/**
	 * Registers hook callbacks for unit actions.
	 *
	 * @since 1.0.0
	 */
	public function load() {
		// Units > Add and > Edit fields.
		add_action( 'ensemble_unit_add_form_fields', array( $this, 'add_unit_fields'  ) );
		add_action( 'ensemble_unit_edit_form',       array( $this, 'edit_unit_fields' ) );

		// Units > Add List table columns.
		add_filter( 'manage_edit-ensemble_unit_columns',  array( $this, 'filter_unit_table_columns' ),   100 );
		add_filter( 'manage_ensemble_unit_custom_column', array( $this, 'column_city'               ), 11, 3 );
		add_filter( 'manage_ensemble_unit_custom_column', array( $this, 'column_directors'          ), 12, 3 );

		// Save custom meta on add and edit.
		add_action( 'create_ensemble_unit', array( $this, 'add_unit_save_meta' ) );
		add_action( 'edit_ensemble_unit',   array( $this, 'edit_unit_save_meta' ) );
	}

	/**
	 * Inserts custom fields markup into the Add Unit form.
	 *
	 * @since 1.0.0
	 *
	 * @param string $taxonomy Taxonomy.
	 */
	public function add_unit_fields() {
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
	public function edit_unit_fields( $term ) {
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
			'id'    => 'unit-city',
			'label' => __( 'Home City', 'ensemble' ),
		);

		if ( null !== $term ) {
			$args['value'] = get_term_meta( $term->term_id, 'ensemble_city', true );

			// If $term is available this is for the Units > Edit form where the label is output separately.
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
			'id'               => 'unit-directors',
			'name'             => 'unit-directors[]',
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

			// If $term is available this is for the Units > Edit form where the label is output separately.
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
	 * Filters the columns in the Competing Units list table.
	 *
	 * @since 1.0.0
	 *
	 * @param array $columns List table columns.
	 * @return array Modified columns array.
	 */
	public function filter_unit_table_columns( $columns ) {
		global $hook_suffix;

		// For some reason core is evaluating this filter on term.php. Nip that in the bud.
		if ( 'term.php' === $hook_suffix ) {
			return $columns;
		}

		$new_columns = array(
			'cb'        => $columns['cb'],
			'name'      => $columns['name'],
			'city'      => _x( 'City', 'competing unit', 'ensemble' ),
			'directors' => _x( 'Director(s)', 'competing unit', 'ensemble' ),
		);

		return $new_columns;
	}

	/**
	 * Renders the contents of a single 'City' column row in the Units list table.
	 *
	 * @since 1.0.0
	 *
	 * @param string $string      Blank string.
	 * @param string $column_name Name of the column.
	 * @param int    $term_id     Term ID.
	 * @return string Markup for the column row.
	 */
	public function column_city( $value, $column_name, $unit_id ) {
		if ( 'city' !== $column_name ) {
			return $value;
		}

		$value = 'City';
		return $value;
	}

	/**
	 * Renders the contents of a single 'Director(s)' column row in the Units list table.
	 *
	 * @since 1.0.0
	 *
	 * @param string $string      Blank string.
	 * @param string $column_name Name of the column.
	 * @param int    $term_id     Term ID.
	 * @return string Markup for the column row.
	 */
	public function column_directors( $value, $column_name, $unit_id ) {
		if ( 'directors' !== $column_name ) {
			return $value;
		}

		$value = 'Director1, Director2';

		return $value;
	}

	/**
	 * Handles saving custom term meta fields when a new unit is added.
	 *
	 * @since 1.0.0
	 *
	 * @param int $unit_id Unit term ID.
	 */
	public function add_unit_save_meta( $unit_id ) {

	}

	/**
	 * Handles saving custom term meta fields when a unit is updated.
	 *
	 * @since 1.0.0
	 *
	 * @param int $unit_id Unit term ID.
	 */
	public function edit_unit_save_meta( $unit_id ) {

	}
}
