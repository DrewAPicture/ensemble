<?php
/**
 * Classification Actions
 *
 * @package   Ensemble\Components\Classifications\Admin
 * @copyright Copyright (c) 2018, Drew Jaynes
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.0.0
 */
namespace Ensemble\Components\Classifications\Admin;

use Ensemble\Components\Classifications\Setup;
use Ensemble\Core\Interfaces\Loader;
use function Ensemble\html;

/**
 * Sets up logic for performing actions for classifications.
 *
 * @since 1.0.0
 *
 * @see Loader
 */
class Actions implements Loader {

	/**
	 * Registers hook callbacks for classifications actions.
	 *
	 * @since 1.0.0
	 */
	public function load() {
		// Units > Add and > Edit fields.
		add_action( 'ensemble_units-add_unit_fields',  array( $this, 'add_unit_class_field'  ) );
		add_action( 'ensemble_units-edit_unit_fields', array( $this, 'edit_unit_class_field' ) );

		// Units > Add List table columns.
		add_filter( 'ensemble_units-ensemble_unit_coloumns', array( $this, 'filter_unit_table_columns' )        );
		add_filter( 'manage_ensemble_unit_custom_column',    array( $this, 'column_class'              ), 13, 3 );

		// Save custom meta on add and edit.
		add_action( 'create_ensemble_unit', array( $this, 'save_unit_meta' ) );
		add_action( 'edit_ensemble_unit',   array( $this, 'save_unit_meta' ) );

		// Filter the Classifications list table columns.
		add_filter( 'manage_edit-ensemble_class_columns', array( $this, 'filter_class_table_columns' ), 100 );

		// Hide (unimportant) slug field on Classifications > Add.
		add_action( 'add_tag_form_pre', array( $this, 'hide_add_class_slug_field' ) );
	}

	/**
	 * Inserts a 'Classification' field into the Add Unit form.
	 *
	 * @since 1.0.0
	 *
	 * @param string $taxonomy Taxonomy.
	 */
	public function add_unit_class_field() {
		?>
		<div class="form-group">
			<?php $this->output_class_field(); ?>
		</div>
		<?php
	}

	/**
	 * Inserts a 'Classification' field in the Edit Unit form.
	 *
	 * @since 1.0.0
	 *
	 * @param \WP_Term $term Unit term object.
	 */
	public function edit_unit_class_field( $term ) {
		?>
		<tr class="form-field">
			<th scope="row">
				<label for="ensemble-city"><?php esc_html_e( 'Classification', 'ensemble' ); ?></label>
			</th>
			<td>
				<?php $this->output_class_field( $term ); ?>
			</td>
		</tr>
		<?php
	}

	/**
	 * Private helper to output the markup for the 'Classification' field for units.
	 *
	 * @since 1.0.0
	 *
	 * @param null|\WP_Term $term Optional. Term object. Default null (ignored).
	 */
	private function output_class_field( $term = null ) {
		$classifications = get_terms( array(
			'taxonomy'   => ( new Setup )->get_taxonomy_slug(),
			'hide_empty' => false,
			'fields'   => 'id=>name',
		) );

		$args = array(
			'id'               => 'unit-class',
			'label'            => __( 'Classification', 'ensemble' ),
			'class'            => array( 'form-control' ),
			'options'          => $classifications,
			'show_option_all'  => false,
			'show_option_none' => false,
		);

		if ( null !== $term ) {
			$class_meta = get_term_meta( $term->term_id, 'ensemble-class', true );

			if ( $class_meta ) {
				$args['selected'] = $class_meta;
			}

			// If $term is available this is for the Units > Edit form where the label is output separately.
			unset( $args['label'] );
		}

		// Output the element.
		html()->select( $args );
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
		$columns['class'] = _x( 'Class', 'classification', 'ensemble' );

		return $columns;
	}

	/**
	 * Renders the contents of a single 'Class' column row in the Units list table.
	 *
	 * @since 1.0.0
	 *
	 * @param string $string      Blank string.
	 * @param string $column_name Name of the column.
	 * @param int    $term_id     Term ID.
	 * @return string Markup for the column row.
	 */
	public function column_class( $value, $column_name, $unit_id ) {
		if ( 'class' !== $column_name ) {
			return $value;
		}

		$class_meta = get_term_meta( $unit_id, 'ensemble-class', true );

		if ( $class_meta ) {
			$class = get_term_by( 'id', $class_meta, ( new Setup )->get_taxonomy_slug() );

			if ( $class ) {
				$value = $class->name;
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
	public function save_unit_meta( $unit_id ) {
		$class = $_REQUEST['unit-class'] ?? '';

		if ( ! empty( $class ) ) {
			update_term_meta( $unit_id, 'ensemble-class', $class );
		} else {
			delete_term_meta( $unit_id, 'ensemble-class' );
		}
	}

	/**
	 * Filters the columns in the Classifications list table.
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
			'cb'          => $columns['cb'],
			'name'        => $columns['name'],
			'description' => $columns['description'],
		);

		/**
		 * Filters the list of Classifications list table columns directly after the component has modified
		 * the original list.
		 *
		 * @since 1.0.0
		 *
		 * @param array $new_columns Class-defined columns and keys.
		 * @param array $columns     Original list table columns supplied to the parent callback.
		 */
		return apply_filters( 'ensemble_classifications-ensemble_class_columns', $new_columns, $columns );
	}


	/**
	 * Bit of a hack, but outputs some inline CSS at the top of the Classifications > Add form to hide the slug field.
	 *
	 * @since 1.0.0
	 *
	 * @param string $taxonomy Taxonomy slug.
	 */
	function hide_add_class_slug_field( $taxonomy ) {
		if ( $taxonomy !== ( new Setup )->get_taxonomy_slug() ) {
			return;
		}
		?>
		<style type="text/css">
			/* Hide the Slug fields */
			.term-slug-wrap {
				display: none;
			}
		</style>
		<?php
	}

}
