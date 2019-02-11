<?php
/**
 * List table used in the Instructors tab view
 *
 * @package   Ensemble\Components\People\Instructors\Admin
 * @copyright Copyright (c) 2019, Drew Jaynes
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.1.0
 */
namespace Ensemble\Components\People\Instructors\Admin;

use Ensemble\Components\People\Instructors\{Database, Model};
use Ensemble\Components\Units\Setup;
use Ensemble\Util\Date;

/**
 * Implements a list table for instructors.
 *
 * @since 1.1.0
 *
 * @see \WP_List_Table
 */
class List_Table extends \WP_List_Table {

	/**
	 * Represents the default number of instructors to show per page.
	 *
	 * @since 1.1.0
	 * @var   string
	 */
	public $per_page = 30;

	/**
	 * Total number of items found.
	 *
	 * @since 1.1.0
	 * @var   int
	 */
	public $total_count;

	/**
	 * Total item count for the current query.
	 *
	 * Used for the pagination controls with non-status filtered results.
	 *
	 * @since 1.1.0
	 * @var   int
	 */
	public $current_count;

	/**
	 * Sets up the list table.
	 *
	 * @since 1.1.0
	 *
	 * @param array|string $args {
	 *     Array or string of arguments.
	 *
	 *     @type string $plural   Plural value used for labels and the objects being listed.
	 *                            This affects things such as CSS class-names and nonces used
	 *                            in the list table, e.g. 'posts'. Default empty.
	 *     @type string $singular Singular label for an object being listed, e.g. 'post'.
	 *                            Default empty
	 *     @type bool   $ajax     Whether the list table supports Ajax. This includes loading
	 *                            and sorting data, for example. If true, the class will call
	 *                            the _js_vars() method in the footer to provide variables
	 *                            to any scripts handling Ajax events. Default false.
	 *     @type string $screen   String containing the hook name used to determine the current
	 *                            screen. If left null, the current screen will be automatically set.
	 *                            Default null.
	 * }
	 */
	public function __construct( $args = array() ) {
		$args = wp_parse_args( $args, array(
			'singular' => 'instructor',
			'plural'   => 'instructors',
		) );

		parent::__construct( $args );

		$this->calculate_count();
	}

	/**
	 * Calculates all count properties used in views.
	 *
	 * @since 1.1.0
	 */
	public function calculate_count() {
		$this->total_count = ( new Database )->count();
	}

	/**
	 * Retrieves the view types.
	 *
	 * @since 1.1.0
	 *
	 * @return array All available views.
	 */
	public function get_views() {
		$base = add_query_arg( 'page', 'ensemble-admin-people-instructors', admin_url( 'admin.php' ) );

		$total_count = '&nbsp;<span class="count">(' . $this->total_count . ')</span>';

		$views = array(
			'all' => sprintf( '<a href="%1$s" class="current">%2$s</a>',
				esc_url( remove_query_arg( 'status', $base ) ),
				esc_html_x( 'All', 'instructors', 'ensemble') . $total_count
			),
		);

		return $views;
	}

	/**
	 * Retrieves the name of the primary column.
	 *
	 * @since 1.1.0
	 *
	 * @return string Name of the primary column.
	 */
	protected function get_primary_column_name() {
		return 'name';
	}

	/**
	 * Retrieves the list of columns.
	 *
	 * @since 1.1.0
	 *
	 * @return array $columns Array of all the list table columns
	 */
	public function get_columns() {
		$columns = array(
			'name'            => __( 'Name', 'ensemble' ),
			'units'           => __( 'Competing Unit(s)', 'ensemble' ),
			'user_registered' => __( 'Date Created', 'ensemble' ),
		);

		/**
		 * Filters the instructors list table columns.
		 *
		 * @since 1.1.0
		 *
		 * @param array      $columns The columns for this list table.
		 * @param List_Table $this    List table instance.
		 */
		return apply_filters( 'ensemble_instructors_table_columns', $columns, $this );
	}

	/**
	 * Retrieves the list of sortable columns.
	 *
	 * @since 1.1.0
	 *
	 * @return array Array of all the sortable columns.
	 */
	public function get_sortable_columns() {
		return array(
			'name'  => array( 'name', false ),
		);
	}

	/**
	 * Runs the query for the list table to display.
	 *
	 * @since 1.1.0
	 *
	 * @return array Query results.
	 */
	public function get_data() {
		$page    = absint( $_REQUEST['paged'] ?? 1 );
		$order   = sanitize_text_field( $_REQUEST['order'] ?? 'ASC' );
		$orderby = sanitize_key( $_REQUEST['orderby'] ?? 'display_name' );
		$unit_id = absint( $_REQUEST['unit_id'] ?? 0 );

		$per_page = $this->get_items_per_page( 'ensemble_instructors_per_page', $this->per_page );

		$args = array(
			'number'  => $per_page,
			'offset'  => $per_page * ( $page - 1 ),
			'orderby' => $orderby,
			'order'   => $order,
		);

		if ( ! empty( $unit_id ) ) {
			$instructors = get_objects_in_term( $unit_id, ( new Setup )->get_taxonomy_slug() );

			if ( ! empty( $instructors ) ) {
				$args['include'] = wp_parse_id_list( $instructors );
			}
		}

		$instructors = ( new Database )->query( $args );

		// Retrieve the "current" total count for pagination purposes.
		unset( $args['number'] );

		$this->current_count = ( new Database )->count( $args );

		return $instructors;
	}

	/**
	 * Prepares instructor items for display.
	 *
	 * @since 1.1.0
	 */
	public function prepare_items() {
		$per_page = $this->get_items_per_page( 'ensemble_instructors_per_page', $this->per_page );

		$columns  = $this->get_columns();
		$hidden   = array();
		$sortable = $this->get_sortable_columns();

		$this->_column_headers = array( $columns, $hidden, $sortable );

		$current_page = $this->get_pagenum();

		$this->items = $this->get_data();

		$total_items = $this->total_count;

		$this->set_pagination_args( array(
			'total_items' => $total_items,
			'per_page'    => $per_page,
			'total_pages' => ceil( $total_items / $per_page )
		) );
	}

	/**
	 * Default display callback for list table columns.
	 *
	 * @since 1.1.0
	 *
	 * @param Model  $instructor  The current instructor object.
	 * @param string $column_name The name of the column.
	 *
	 * @return string The column value.
	 */
	public function column_default( $instructor, $column_name ) {
		$base_url = add_query_arg( 'page', 'ensemble-admin-people-instructors', admin_url( 'admin.php' ) );

		$value = '';

		switch( $column_name ){

			case 'name':
				if ( current_user_can( 'manage_options' ) ) {
					$value = sprintf( '<a href="%1$s" aria-label="%2$s">%3$s</a>',
						esc_url( add_query_arg( array( 'ensbl-view' => 'edit', 'user_id' => $instructor->ID ), $base_url ) ),
						sprintf( _x( 'Edit %s', 'ensemble' ), $instructor->display_name ),
						$instructor->display_name
					);
				} else {
					$value = $instructor->display_name;
				}

				break;

			case 'units':
				$units = wp_get_object_terms( $instructor->ID, 'ensemble_unit', array( 'fields' => 'id=>name' ) );

				$unit_links = array();

				if ( ! empty( $units ) ) {
					foreach ( $units as $ID => $label ) {
						$unit_links[] = sprintf( '<a href="%1$s" aria-label="%2$s">%3$s</a>',
							esc_url( add_query_arg( array( 'unit_id' => $ID ), $base_url ) ),
							/* translators: Unit name */
							sprintf( __( 'View instructors for the %s unit', 'ensemble' ), $label ),
							esc_html( $label )
						);
					}

					if ( ! empty( $unit_links ) ) {
						$value = implode( ', ', $unit_links );
					}
				}
				break;

			case 'user_registered':
				$value = Date::UTC_to_WP( $instructor->user_registered, 'F j, Y g:i a' );
				break;

		}

		/**
		 * Filters the default value for each instructors list table column.
		 *
		 * @since 1.1.0
		 *
		 * @param string $value   The column data.
		 * @param Model  $instructor The current instructor object.
		 */
		return apply_filters( 'ensemble_instructors_table_' . $column_name, $value, $instructor );
	}

	/**
	 * Renders the message to be displayed when there are no instructors.
	 *
	 * @since 1.1.0
	 */
	function no_items() {
		esc_html_e( 'You haven&#8217;t created any instructors yet!', 'ensemble' );
	}

	/**
	 * Generates and displays row action links.
	 *
	 * @since 1.1.0
	 *
	 * @param Model  $instructor  Current instructor object.
	 * @param string $column_name Current column name.
	 * @param string $primary     Primary column name.
	 *
	 * @return string Row actions output for instructors.
	 */
	protected function handle_row_actions( $instructor, $column_name, $primary ) {
		if ( $primary !== $column_name || ! current_user_can( 'manage_options' ) ) {
			return '';
		}

		$actions  = array();
		$base_url = add_query_arg( 'page', 'ensemble-admin-people-instructors', admin_url( 'admin.php' ) );

		$actions['edit'] = sprintf( '<a href="%1$s" aria-label="%2$s">%3$s</a>',
			esc_url( add_query_arg( array( 'ensbl-view' => 'edit', 'user_id' => $instructor->ID ), $base_url ) ),
			sprintf( _x( 'Edit %s', 'ensemble' ), $instructor->display_name ),
			_x( 'Edit', 'instructor', 'ensemble' )
		);

		$actions['delete'] = sprintf( '<a href="%1$s" aria-label="%2$s">%3$s</a>',
			esc_url( add_query_arg( array( 'ensbl-view' => 'delete', 'user_id' => $instructor->ID ), $base_url ) ),
			sprintf( _x( 'Delete %s', 'ensemble' ), $instructor->display_name ),
			_x( 'Delete', 'instructor', 'ensemble' )
		);

		/**
		 * Filters the array of row action links on the instructors list table.
		 *
		 * @since 1.1.0
		 *
		 * @param string[] $actions An array of row action links.
		 * @param Model    $instructor The current instructor object.
		 */
		$actions = apply_filters( 'ensemble_instructors_row_actions', $actions, $instructor );

		return $this->row_actions( $actions );
	}

}
