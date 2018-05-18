<?php
/**
 * Season Actions
 *
 * @package   Ensemble\Components\Seasons\Admin
 * @copyright Copyright (c) 2018, Drew Jaynes
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.0.0
 */
namespace Ensemble\Components\Seasons\Admin;

use Ensemble\Components\Seasons\Setup;
use Ensemble\Core\Interfaces\Loader;
use function Ensemble\html;

/**
 * Sets up logic for performing actions for seasons.
 *
 * @since 1.0.0
 *
 * @see Loader
 */
class Actions implements Loader {

	/**
	 * Registers hook callbacks for seasons actions.
	 *
	 * @since 1.0.0
	 */
	public function load() {
		// Fields.
		add_action( 'ensemble_season_add_form_fields', array( $this, 'add_season_fields'  ) );
		add_action( 'ensemble_season_edit_form',       array( $this, 'edit_season_fields' ) );

		// List table column.
		add_filter( 'manage_edit-ensemble_season_columns', array( $this, 'filter_season_table_columns' ), 100 );

		// Custom column callbacks.
		add_filter( 'manage_ensemble_season_custom_column', array( $this, 'column_start_date' ), 11, 3 );
		add_filter( 'manage_ensemble_season_custom_column', array( $this, 'column_end_date'   ), 12, 3 );

		// Save meta.
		add_action( 'create_ensemble_season', array( $this, 'save_season_meta' ) );
		add_action( 'edit_ensemble_season',   array( $this, 'save_season_meta' ) );

		// Hide core fields.
		add_action( 'add_tag_form_pre', array( $this, 'hide_add_season_fields' ) );
	}

	/**
	 * Inserts custom fields markup into the Add Season form.
	 *
	 * @since 1.0.0
	 *
	 * @param string $taxonomy Taxonomy.
	 */
	public function add_season_fields() {
		?>
		<div class="form-field bootstrap-iso fs-13">
			<div class="form-group">
				<?php
				html()->text( array(
					'id'    => 'tag-name', // mimicking core ID/name.
					'label' => _x( 'Name', 'season', 'ensemble' ),
					'class' => array( 'form-control', 'w-100' ),
					'desc'  => __( 'The name is how it appears on your site.', 'ensemble' ),
				) );
				?>
			</div>
			<div class="form-group">
				<?php $this->output_start_date_field(); ?>
			</div>
			<div class="form-group">
				<?php $this->output_end_date_field(); ?>
			</div>
			<?php
			/**
			 * Fires inside the form-field container in the Seasons > Add form.
			 *
			 * @since 1.0.0
			 *
			 * @param Actions $this Seasons\Actions class instance.
			 */
			do_action( 'ensemble_seasons-add_season_fields', $this );
			?>
		</div>
		<?php
	}

	/**
	 * Inserts custom fields into the Edit Season form.
	 *
	 * @since 1.0.0
	 *
	 * @param \WP_Term $term Season term object.
	 */
	public function edit_season_fields( $term ) {
		?>
		<table class="form-table bootstrap-iso">
			<tbody>
			<tr class="form-field">
				<th scope="row">
					<label for="ensemble-start-date"><?php echo esc_html_x( 'Start Date', 'season', 'ensemble' ); ?></label>
				</th>
				<td>
					<?php $this->output_start_date_field( $term ); ?>
				</td>
			</tr>
			<tr class="form-field">
				<th scope="row">
					<label for="ensemble-end-date"><?php echo esc_html_x( 'End Date', 'season', 'ensemble' ); ?></label>
				</th>
				<td>
					<?php $this->output_end_date_field( $term ); ?>
				</td>
			</tr>

			<?php
			/**
			 * Fires inside the form-table container in the Seasons > Edit form.
			 *
			 * @since 1.0.0
			 *
			 * @param \WP_Term $term Season term object.
			 */
			do_action( 'ensemble_seasons-edit_season_fields', $term );
			?>
			</tbody>
		</table>
		<?php
	}

	/**
	 * Private helper to output the markup for the 'Start Date' field.
	 *
	 * @since 1.0.0
	 *
	 * @param null|\WP_Term $term Optional. Term object. Default null (ignored).
	 */
	private function output_start_date_field( $term = null ) {
		$args = array(
			'id'    => 'season-start-date',
			'label' => _x( 'Start Date', 'season', 'ensemble' ),
			'class' => array( 'form-control', 'w-100', 'date', 'allow-past-dates' ),
		);

		if ( null !== $term ) {
			$start_date = get_term_meta( $term->term_id, 'ensemble-start-date', true );

			if ( $start_date ) {
				$args['value'] = date( 'm/d/Y', strtotime( $start_date ) );
			}

			// If $term is available this is for the Seasons > Edit form where the label is output separately.
			unset( $args['label'] );
		}

		// Output the element.
		html()->text( $args );
	}

	/**
	 * Private helper to output the markup for the 'End Date' field.
	 *
	 * @since 1.0.0
	 *
	 * @param null|\WP_Term $term Optional. Term object. Default null (ignored).
	 */
	private function output_end_date_field( $term = null ) {
		$args = array(
			'id'    => 'season-end-date',
			'label' => _x( 'End Date', 'season', 'ensemble' ),
			'class' => array( 'form-control', 'w-100', 'date', 'allow-past-dates' ),
		);

		if ( null !== $term ) {
			$end_date = get_term_meta( $term->term_id, 'ensemble-end-date', true );

			if ( $end_date ) {
				$args['value'] = date( 'm/d/Y', strtotime( $end_date ) );
			}

			// If $term is available this is for the Seasons > Edit form where the label is output separately.
			unset( $args['label'] );
		}

		// Output the element.
		html()->text( $args );
	}

	/**
	 * Filters the columns in the Seasons list table.
	 *
	 * @since 1.0.0
	 *
	 * @param array $columns List table columns.
	 * @return array Modified columns array.
	 */
	public function filter_season_table_columns( $columns ) {
		global $hook_suffix;

		// For some reason core is evaluating this filter on term.php. Nip that in the bud.
		if ( 'term.php' === $hook_suffix ) {
			return $columns;
		}

		$new_columns = array(
			'cb'         => $columns['cb'],
			'name'       => $columns['name'],
			'start_date' => _x( 'Start Date', 'seasons', 'ensemble' ),
			'end_date'   => _x( 'End Date', 'seasons', 'ensemble' ),
		);

		/**
		 * Filters the list of Seasons list table columns directly after the component has modified
		 * the original list.
		 *
		 * @since 1.0.0
		 *
		 * @param array $new_columns Class-defined columns and keys.
		 * @param array $columns     Original list table columns supplied to the parent callback.
		 */
		return apply_filters( 'ensemble_seasons-ensemble_class_coloumns', $new_columns, $columns );
	}

	/**
	 * Renders the contents of a single 'Start Date' column row in the Seasons list table.
	 *
	 * @since 1.0.0
	 *
	 * @param string $string      Blank string.
	 * @param string $column_name Name of the column.
	 * @param int    $term_id     Term ID.
	 * @return string Markup for the column row.
	 */
	public function column_start_date( $value, $column_name, $term_id ) {
		if ( 'start_date' !== $column_name ) {
			return $value;
		}

		$start_date = get_term_meta( $term_id, 'ensemble-start-date', true );

		if ( $start_date ) {
			$value = date( 'm/d/Y', strtotime( $start_date ) );
		}

		return $value;
	}

	/**
	 * Renders the contents of a single 'End Date' column row in the Seasons list table.
	 *
	 * @since 1.0.0
	 *
	 * @param string $string      Blank string.
	 * @param string $column_name Name of the column.
	 * @param int    $term_id     Term ID.
	 * @return string Markup for the column row.
	 */
	public function column_end_date( $value, $column_name, $term_id ) {
		if ( 'end_date' !== $column_name ) {
			return $value;
		}

		$end_date = get_term_meta( $term_id, 'ensemble-end-date', true );

		if ( $end_date ) {
			$value = date( 'm/d/Y', strtotime( $end_date ) );
		}

		return $value;
	}

	/**
	 * Handles saving custom term meta fields when adding and editing seasons.
	 *
	 * @since 1.0.0
	 *
	 * @param int $term_id Term ID.
	 */
	public function save_season_meta( $term_id ) {
		$start_date = $_REQUEST['season-start-date'] ?? '';
		$end_date   = $_REQUEST['season-end-date'] ?? '';

		if ( ! empty( $start_date ) ) {
			update_term_meta( $term_id, 'ensemble-start-date', sanitize_text_field( $start_date ) );
		} else {
			delete_term_meta( $term_id, 'ensemble-start-date' );
		}

		if ( ! empty( $end_date ) ) {
			update_term_meta( $term_id, 'ensemble-end-date', sanitize_text_field( $end_date ) );
		} else {
			delete_term_meta( $term_id, 'ensemble-end-date' );
		}
	}

	/**
	 * Bit of a hack, but outputs some inline CSS at the top of the Seasons > Add form to hide the slug field.
	 *
	 * @since 1.0.0
	 *
	 * @param string $taxonomy Taxonomy slug.
	 */
	function hide_add_season_fields( $taxonomy ) {
		if ( $taxonomy !== ( new Setup )->get_taxonomy_slug() ) {
			return;
		}
		?>
		<style type="text/css">
			/* Hide the Slug fields */
			.term-name-wrap,
			.term-slug-wrap,
			.term-description-wrap {
				display: none;
			}
		</style>
		<?php
	}

}
