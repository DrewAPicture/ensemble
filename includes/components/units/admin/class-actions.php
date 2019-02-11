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

use Ensemble\Components\People\{Directors, Instructors};
use Ensemble\Components\Units\Setup;
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
		// Fields.
		add_action( 'ensemble_unit_add_form_fields', array( $this, 'add_unit_fields'  ) );
		add_action( 'ensemble_unit_edit_form',       array( $this, 'edit_unit_fields' ) );

		// List table columns.
		add_filter( 'manage_edit-ensemble_unit_columns',  array( $this, 'filter_unit_table_columns' ),   100 );
		add_filter( 'manage_ensemble_unit_custom_column', array( $this, 'column_city'               ), 11, 3 );
		add_filter( 'manage_ensemble_unit_custom_column', array( $this, 'column_staff'              ), 12, 3 );

		// Save meta.
		add_action( 'create_ensemble_unit', array( $this, 'save_fields' ) );
		add_action( 'edit_ensemble_unit',   array( $this, 'save_fields' ) );

		// Hide core fields.
		add_action( 'add_tag_form_pre', array( $this, 'hide_default_add_unit_fields' ) );
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
		<div class="form-field bootstrap-iso fs-13">
			<div class="form-group">
				<?php
				html()->text( array(
					'id'    => 'tag-name', // mimicking core ID/name.
					'label' => _x( 'Name', 'unit', 'ensemble' ),
					'class' => array( 'form-control', 'w-100' ),
					'desc'  => __( 'The name is how it appears on your site.', 'ensemble' ),
				) );
				?>
			</div>
			<div class="form-group">
				<?php $this->output_city_field(); ?>
			</div>
			<div class="form-group">
				<?php $this->output_directors_field(); ?>
			</div>
			<div class="form-group">
				<?php $this->output_instructors_field(); ?>
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
			<tr class="form-field">
				<th scope="row">
					<label for="ensemble-instructors"><?php esc_html_e( 'Instructor(s)', 'ensemble' ); ?></label>
				</th>
				<td>
					<?php $this->output_instructors_field( $term ); ?>?>
				</td>
			</tr>

			<?php
			/**
			 * Fires inside the form-table container in the Units > Edit form.
			 *
			 * @since 1.0.0
			 *
			 * @param \WP_Term $term Unit term object.
			 */
			do_action( 'ensemble_units-edit_unit_fields', $term );
			?>
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
			'class' => array( 'form-control', 'w-100' ),
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
			'class'            => array( 'form-control', 'form-control-sm' ),
			'multiple'         => true,
			'options'          => $this->get_directors_as_options(),
			'show_option_all'  => false,
			'show_option_none' => false,
		);

		if ( null !== $term ) {
			$directors = get_objects_in_term( $term->term_id, ( new Setup )->get_taxonomy_slug() );

			if ( ! empty( $directors ) ) {
				$args['selected'] = $directors;
			}

			// If $term is available this is for the Units > Edit form where the label is output separately.
			unset( $args['label'] );
		}

		// Output the element.
		html()->select( $args );
	}

	/**
	 * Private helper to output the markup for the 'Instructor(s)' field.
	 *
	 * @since 1.0.0
	 *
	 * @param null|\WP_Term $term Optional. Term object. Default null (ignored).
	 */
	private function output_instructors_field( $term = null ) {
		$args = array(
			'id'               => 'unit-instructors',
			'name'             => 'unit-instructors[]',
			'label'            => __( 'Instructor(s)', 'ensemble' ),
			'class'            => array( 'form-control', 'form-control-sm' ),
			'multiple'         => true,
			'options'          => $this->get_instructors_as_options(),
			'show_option_all'  => false,
			'show_option_none' => false,
		);

		if ( null !== $term ) {
			$instructors = get_objects_in_term( $term->term_id, ( new Setup )->get_taxonomy_slug() );

			if ( ! empty( $instructors ) ) {
				$args['selected'] = $instructors;
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
			'fields'     => array( 'ID', 'display_name' ),
			'number'     => 500,
			'as_options' => true,
		);

		if ( ! empty( $ids ) ) {
			$args['include'] = $ids;
		}

		return ( new Directors\Database )->query( $args );
	}

	/**
	 * Retrieves a list of instructor ID\name pairs for use as an options array.
	 *
	 * @since 1.0.0
	 *
	 * @param array $ids Optional. Specific array of instructor IDs to query for. Default empty array.
	 * @return array Instructor ID\name pairs if any are found, otherwise an empty array.
	 */
	private function get_instructors_as_options( $ids = array() ) {
		$args = array(
			'fields'     => array( 'ID', 'display_name' ),
			'number'     => 500,
			'as_options' => true,
		);

		if ( ! empty( $ids ) ) {
			$args['include'] = $ids;
		}

		return ( new Instructors\Database )->query( $args );
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
			'cb'    => $columns['cb'],
			'name'  => $columns['name'],
			'city'  => _x( 'City', 'competing unit', 'ensemble' ),
			'staff' => _x( 'Staff', 'competing unit', 'ensemble' ),
		);

		/**
		 * Filters the list of Units list table columns directly after the Units component has modified it.
		 *
		 * @since 1.0.0
		 *
		 * @param array $new_columns Unit-defined columns and keys.
		 * @param array $columns     Original list table columns supplied to the parent callback.
		 */
		return apply_filters( 'ensemble_units-ensemble_unit_coloumns', $new_columns, $columns );
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
	public function column_staff( $value, $column_name, $unit_id ) {
		if ( 'staff' !== $column_name ) {
			return $value;
		}

		$staff_ids = get_objects_in_term( $unit_id, ( new Setup )->get_taxonomy_slug() );

		$value = '';

		if ( ! empty( $staff_ids ) ) {
			$directors = ( new Directors\Database )->query( array(
				'include' => $staff_ids,
				'fields'  => array( 'display_name' ),
			) );

			if ( ! empty( $directors ) ) {
				$value .= '<strong>' . __( 'Director(s):', 'ensemble' ) . '</strong><br />';
				$value .= implode( ', ', wp_list_pluck( $directors, 'display_name' ) ) . '<br />';
			}

			$instructors = ( new Instructors\Database )->query( array(
				'include' => $staff_ids,
				'fields'  => array( 'display_name' ),
			) );

			if ( ! empty( $instructors ) ) {
				$value .= '<strong>' . __( 'Instructor(s)', 'ensemble' ) . '</strong><br />';
				$value .= implode( ', ', wp_list_pluck( $instructors, 'display_name' ) );
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
	public function save_fields( $unit_id ) {
		$city        = sanitize_text_field( $_REQUEST['unit-city'] ?? '' );
		$directors   = $_REQUEST['unit-directors'] ?? array();
		$instructors = $_REQUEST['unit-instructors'] ?? array();

		if ( ! empty( $city ) ) {
			update_term_meta( $unit_id, 'ensemble-city', $city );
		} else {
			delete_term_meta( $unit_id, 'ensemble-city' );
		}

		if ( ! empty( $directors ) ) {
			$directors = array_map( 'absint', $directors );

			foreach ( $directors as $director ) {
				wp_add_object_terms( $director, $unit_id, ( new Setup )->get_taxonomy_slug() );
			}
		} else {
			delete_term_meta( $unit_id, 'ensemble-directors' );
		}

		if ( ! empty( $instructors ) ) {
			$instructors = array_map( 'absint', $instructors );

			foreach ( $instructors as $instructor ) {
				wp_add_object_terms( $instructor, $unit_id, ( new Setup )->get_taxonomy_slug() );
			}
		} else {
			delete_term_meta( $unit_id, 'ensemble-instructors' );
		}
	}

	/**
	 * Bit of a hack, but outputs some inline CSS at the top of the Units > Add form to hide the default form fields.
	 *
	 * @since 1.0.0
	 *
	 * @param string $taxonomy Taxonomy slug.
	 */
	function hide_default_add_unit_fields( $taxonomy ) {
		if ( $taxonomy !== ( new Setup )->get_taxonomy_slug() ) {
			return;
		}
		?>
		<style type="text/css">
			/* Hide the Name, Slug, and Description fields */
			.term-name-wrap,
			.term-slug-wrap,
			.term-description-wrap {
				display: none;
			}
		</style>
		<?php
	}

}
