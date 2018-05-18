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
		add_action( 'create_ensemble_unit', array( $this, 'save_meta' ) );
		add_action( 'edit_ensemble_unit',   array( $this, 'save_meta' ) );
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
			<div class="form-group">
				<?php $this->output_city_field(); ?>
			</div>
			<div class="form-group">
				<?php $this->output_directors_field(); ?>
			</div>
			<?php
			/**
			 * Fires inside the form-field container in the Units > Add form.
			 *
			 * @since 1.0.0
			 *
			 * @param Actions $this Units\Actions class instance.
			 */
			do_action( 'ensemble_units-add_unit_fields', $this );
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
			'class' => array( 'mb-3' ),
		);

		if ( null !== $term ) {
			$city_meta = get_term_meta( $term->term_id, 'ensemble-city', true );

			if ( $city_meta ) {
				$args['value'] = $city_meta;
			}

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
			$director_meta = get_term_meta( $term->term_id, 'ensemble-directors', true );

			if ( $director_meta ) {
				$args['selected'] = wp_parse_id_list( explode( ',', $director_meta ) );
			}

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
	 * @param array $ids Optional. Specific array of director IDs to query for. Default empty array.
	 * @return array Director ID\name pairs if any are found, otherwise an empty array.
	 */
	private function get_directors_as_options( $ids = array() ) {
		$args = array(
			'fields' => array( 'ID', 'display_name' ),
			'number' => 500,
		);

		if ( ! empty( $ids ) ) {
			$args['include'] = $ids;
		}

		$directors_results = ( new Directors\Database )->query( $args );

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

		$city_meta = get_term_meta( $unit_id, 'ensemble-city', true );

		if ( $city_meta ) {
			$value = $city_meta;
		}

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

		$directors_meta = get_term_meta( $unit_id, 'ensemble-directors', true );

		if ( $directors_meta ) {
			$ids = wp_parse_id_list( explode( ',', $directors_meta ) );

			$directors = array_values( $this->get_directors_as_options( $ids ) );

			if ( ! empty( $directors ) ) {
				$value = implode( ', ', $directors );
			}

		}

		return $value;
	}

	/**
	 * Handles saving custom term meta fields when adding and editing units.
	 *
	 * @since 1.0.0
	 *
	 * @param int $unit_id Unit term ID.
	 */
	public function save_meta( $unit_id ) {
		$city      = $_REQUEST['unit-city'] ?? '';
		$directors = $_REQUEST['unit-directors'] ?? array();

		if ( ! empty( $city ) ) {
			update_term_meta( $unit_id, 'ensemble-city', $city, true );
		} else {
			delete_term_meta( $unit_id, 'ensemble-city' );
		}

		if ( ! empty( $directors ) ) {
			$directors = implode( ',', $directors );

			update_term_meta( $unit_id, 'ensemble-directors', $directors );
		} else {
			delete_term_meta( $unit_id, 'ensemble-directors' );
		}
	}
}
