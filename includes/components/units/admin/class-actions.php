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

use Ensemble\Components\People\Directors\Database;
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
	 * Registers hook callbacks for contest actions.
	 *
	 * @since 1.0.0
	 */
	public function load() {
		// Units > Add fields.
		add_action( 'ensemble_unit_add_form_fields', array( $this, 'add_unit_city_field'      ), 11 );
		add_action( 'ensemble_unit_add_form_fields', array( $this, 'add_unit_directors_field' ), 12 );

		// Units > Edit fields.
		add_action( 'ensemble_unit_edit_form', array( $this, 'edit_unit_city_field'      ), 11 );
		add_action( 'ensemble_unit_edit_form', array( $this, 'edit_unit_directors_field' ), 12 );

		// Units > Add List table columns.
		add_filter( 'manage_edit-ensemble_unit_columns',  array( $this, 'filter_unit_table_columns' ),   100 );
		add_filter( 'manage_ensemble_unit_custom_column', array( $this, 'column_city'               ), 11, 3 );
		add_filter( 'manage_ensemble_unit_custom_column', array( $this, 'column_directors'          ), 12, 3 );

		// Save custom meta on add and edit.
		add_action( 'create_ensemble_unit', array( $this, 'add_unit_save_meta' ) );
		add_action( 'edit_ensemble_unit',   array( $this, 'edit_unit_save_meta' ) );
	}

	/**
	 * Inserts a 'Home City' field into the New Unit form.
	 *
	 * @since 1.0.0
	 *
	 * @param string $taxonomy Taxonomy.
	 */
	public function add_unit_city_field() {
		$this->output_city_field();
	}

	/**
	 * Inserts a 'Home City' field in to the Edit Unit form.
	 *
	 * @since 1.0.0
	 *
	 * @param \WP_Term $term Unit term object.
	 */
	public function edit_unit_city_field( $term ) {
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

			// Output the element without the wrapper.
			html()->text( $args );
		} else {
			?>
			<div class="form-field bootstrap-iso w-95 fs-13">
				<?php html()->text( $args ); ?>
			</div>
			<?php
		}
	}

	/**
	 * Inserts a 'Director(s)' multi-select field into the New Unit form.
	 *
	 * @since 1.0.0
	 */
	public function add_unit_directors_field() {
		$this->output_directors_field();
	}

	/**
	 * Inserts a 'Director(s)' field in to the Edit Unit form.
	 *
	 * @since 1.0.0
	 *
	 * @param \WP_Term $term Unit term object.
	 */
	public function edit_unit_directors_field( $term ) {
		$this->output_directors_field( $term );
	}

	/**
	 * Private helper to output the markup for the 'Director(s)' field.
	 *
	 * @since 1.0.0
	 *
	 * @param null|\WP_Term $term Optional. Term object. Default null (ignored).
	 */
	private function output_directors_field( $term = null ) {
		$directors_results = ( new Database )->query( array(
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

		$args = array(
			'id'               => 'unit-directors',
			'name'             => 'unit-directors[]',
			'label'            => __( 'Director(s)', 'ensemble' ),
			'class'            => array( 'form-control' ),
			'multiple'         => true,
			'options'          => $directors,
			'show_option_all'  => false,
			'show_option_none' => false,
		);

		if ( null !== $term ) {
			$selected = get_term_meta( $term->term_id, 'ensemble-directors', true );
			$args['selected'] = array_map( 'absint', explode( ',', $selected ) );
		}
		?>
		<div class="form-field bootstrap-iso w-95 fs-13">
			<?php
			html()->select( $args );
			?>
		</div>
		<?php

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
