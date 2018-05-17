<?php
/**
 * List table used on Venues Overview
 *
 * @package   Ensemble\Components\Venues\Admin
 * @copyright Copyright (c) 2018, Drew Jaynes
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.0.0
 */
namespace Ensemble\Components\Venues\Admin;

use Ensemble\Components\Venues\{Database, Object};
use function Ensemble\Components\Venues\{get_status_label, get_type_label};

/**
 * Implements a list table for venues.
 *
 * @since 1.0.0
 *
 * @see \WP_List_Table
 */
class List_Table extends \WP_List_Table {

	/**
	 * Represents the default number of venues to show per page.
	 *
	 * @since 1.0.0
	 * @var   string
	 */
	public $per_page = 30;

	/**
	 * Total number of items found.
	 *
	 * @since 1.0.0
	 * @var   int
	 */
	public $total_count;

	/**
	 * Number of items with the 'active' status found.
	 *
	 * @since 1.0.0
	 * @var   int
	 */
	public $active_count;

	/**
	 *  Number of items with the 'inactive' status found.
	 *
	 * @since 1.0.0
	 * @var   int
	 */
	public $inactive_count;

	/**
	 * Total item count for the current query.
	 *
	 * Used for the pagination controls with non-status filtered results.
	 *
	 * @since 1.0.0
	 * @var   int
	 */
	public $current_count;

	/**
	 * Sets up the list table.
	 *
	 * @since 1.0.0
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
			'singular' => 'venue',
			'plural'   => 'venues',
		) );

		parent::__construct( $args );

		$this->calculate_counts();
	}

	/**
	 * Calculates all count properties used in views.
	 *
	 * @since 1.0.0
	 */
	public function calculate_counts() {
		$search = isset( $_GET['s'] ) ? $_GET['s'] : '';

		$this->active_count = ( new Database )->count( array(
			'status' => 'active',
			'search' => $search,
		) );

		$this->inactive_count = ( new Database )->count( array(
			'status' => 'inactive',
			'search' => $search,
		) );

		$this->total_count = $this->active_count + $this->inactive_count;
	}

	/**
	 * Retrieves the view types.
	 *
	 * @since 1.0.0
	 *
	 * @return array All available views.
	 */
	public function get_views() {
		$base = add_query_arg( 'page', 'ensemble-admin-venues', admin_url( 'admin.php' ) );

		$current         = isset( $_GET['status'] ) ? $_GET['status'] : '';
		$total_count     = '&nbsp;<span class="count">(' . $this->total_count     . ')</span>';
		$active_count = '&nbsp;<span class="count">(' . $this->active_count . ')</span>';
		$inactive_count     = '&nbsp;<span class="count">(' . $this->inactive_count     . ')</span>';

		$views = array(
			'all' => sprintf( '<a href="%1$s"%2$s>%3$s</a>',
				esc_url( remove_query_arg( 'status', $base ) ),
				$current === 'all' || $current == '' ? ' class="current"' : '',
				esc_html__( 'All', 'ensemble') . $total_count
			),

			'active' => sprintf( '<a href="%1$s"%2$s>%3$s</a>',
				esc_url( add_query_arg( 'status', 'active', $base ) ),
				$current === 'active' ? ' class="current"' : '',
				get_status_label( 'active' ) . $active_count
			),

			'inactive' => sprintf( '<a href="%1$s"%2$s>%3$s</a>',
				esc_url( add_query_arg( 'status', 'inactive', $base ) ),
				$current === 'inactive' ? ' class="current"' : '',
				get_status_label( 'inactive' ) . $inactive_count
			),
		);

		return $views;
	}

	/**
	 * Retrieves the name of the primary column.
	 *
	 * @since 1.0.0
	 *
	 * @return string Name of the primary column.
	 */
	protected function get_primary_column_name() {
		return 'name';
	}

	/**
	 * Retrieves the list of columns.
	 *
	 * @since 1.0.0
	 *
	 * @return array $columns Array of all the list table columns
	 */
	public function get_columns() {
		$columns = array(
			'name'       => __( 'Venue Name', 'ensemble' ),
			'type'       => __( 'Type', 'ensemble' ),
			'status'     => __( 'Status', 'ensemble' ),
			'date_added' => __( 'Date Added', 'ensemble' ),
		);

		/**
		 * Filters the venues list table columns.
		 *
		 * @since 1.0.0
		 *
		 * @param array      $columns The columns for this list table.
		 * @param List_Table $this    List table instance.
		 */
		return apply_filters( 'ensemble_venues_table_columns', $columns, $this );
	}

	/**
	 * Retrieves the list of sortable columns.
	 *
	 * @since 1.0.0
	 *
	 * @return array Array of all the sortable columns.
	 */
	public function get_sortable_columns() {
		return array(
			'name'       => array( 'name',       false ),
			'type'       => array( 'type',       false ),
			'status'     => array( 'status',     false ),
			'date_added' => array( 'date_added', false ),
		);
	}

	/**
	 * Runs the query for the list table to display.
	 *
	 * @since 1.0.0
	 *
	 * @return array Query results.
	 */
	public function get_data() {
		$page    = isset( $_GET['paged'] )   ? absint( $_GET['paged'] ) : 1;
		$status  = isset( $_GET['status'] )  ? $_GET['status']          : '';
		$search  = isset( $_GET['s'] )       ? $_GET['s']               : '';
		$order   = isset( $_GET['order'] )   ? $_GET['order']           : 'ASC';
		$orderby = isset( $_GET['orderby'] ) ? $_GET['orderby']         : 'name';

		$per_page = $this->get_items_per_page( 'ensemble_venues_per_page', $this->per_page );

		$args = array(
			'number'  => $per_page,
			'offset'  => $per_page * ( $page - 1 ),
			'status'  => $status,
			'search'  => $search,
			'orderby' => sanitize_text_field( $orderby ),
			'order'   => sanitize_text_field( $order )
		);

		$venues = ( new Database )->query( $args );

		// Retrieve the "current" total count for pagination purposes.
		unset( $args['number'] );

		$this->current_count = ( new Database )->count( $args );

		return $venues;
	}

	/**
	 * Prepares venue items for display.
	 *
	 * @since 1.0.0
	 */
	public function prepare_items() {
		$per_page = $this->get_items_per_page( 'ensemble_venues_per_page', $this->per_page );

		$columns  = $this->get_columns();
		$hidden   = array();
		$sortable = $this->get_sortable_columns();

		$this->_column_headers = array( $columns, $hidden, $sortable );

		$data = $this->get_data();

		$current_page = $this->get_pagenum();

		$status = isset( $_GET['status'] ) ? $_GET['status'] : 'any';

		switch( $status ) {
			case 'active':
				$total_items = $this->active_count;
				break;
			case 'inactive':
				$total_items = $this->inactive_count;
				break;
			case 'any':
			default:
				$total_items = $this->current_count;
				break;
		}

		$this->items = $data;

		$this->set_pagination_args( array(
			'total_items' => $total_items,
			'per_page'    => $per_page,
			'total_pages' => ceil( $total_items / $per_page )
		) );
	}

	/**
	 * Default display callback for list table columns.
	 *
	 * @since 1.0.0
	 *
	 * @param Object $venue     The current venue object.
	 * @param string $column_name The name of the column.
	 * @return string The column value.
	 */
	public function column_default( $venue, $column_name ) {
		$base_url = add_query_arg( 'page', 'ensemble-admin-venues', admin_url( 'admin.php' ) );

		switch( $column_name ){

			case 'name':
				if ( current_user_can( 'manage_options' ) ) {
					$value = sprintf( '<a href="%1$s" aria-label="%2$s">%3$s</a>',
						esc_url( add_query_arg( array( 'ensbl-view' => 'edit', 'venue_id' => $venue->id ), $base_url ) ),
						sprintf( _x( 'Edit %s', 'ensemble' ), $venue->name ),
						$venue->name
					);
				} else {
					$value = $venue->name;
				}
				break;
			case 'date_added':
				$value = $venue->get_date_added();
				break;

			case 'status':
				$value = isset( $venue->status ) ? get_status_label( $venue->status ) : '';
				break;

			case 'type':
				$value = isset( $venue->type ) ? get_type_label( $venue->type ) : '';
				break;

			default:
				$value = $venue->$column_name ?? '';
				break;
		}

		/**
		 * Filters the default value for each venues list table column.
		 *
		 * @since 1.0.0
		 *
		 * @param string $value   The column data.
		 * @param Object $venue The current venue object.
		 */
		return apply_filters( 'ensemble_venues_table_' . $column_name, $value, $venue );
	}

	/**
	 * Renders the message to be displayed when there are no venues.
	 *
	 * @since 1.0.0
	 */
	function no_items() {
		esc_html_e( 'No venues found.', 'ensemble' );
	}

	/**
	 * Generates and displays row action links.
	 *
	 * @since 1.0.0
	 *
	 * @param Object $venue       Current venue object.
	 * @param string $column_name Current column name.
	 * @param string $primary     Primary column name.
	 * @return string Row actions output for venues.
	 */
	protected function handle_row_actions( $venue, $column_name, $primary ) {
		if ( $primary !== $column_name ) {
			return '';
		}

		$actions  = array();
		$base_url = add_query_arg( 'page', 'ensemble-admin-venues', admin_url( 'admin.php' ) );

		if ( current_user_can( 'manage_options' ) ) {
			$actions['edit'] = sprintf( '<a href="%1$s" aria-label="%2$s">%3$s</a>',
				esc_url( add_query_arg( array( 'ensbl-view' => 'edit', 'venue_id' => $venue->id ), $base_url ) ),
				sprintf( _x( 'Edit %s', 'ensemble' ), $venue->name ),
				_x( 'Edit', 'venue', 'ensemble' )
			);

			$actions['delete'] = sprintf( '<a href="%1$s" aria-label="%2$s">%3$s</a>',
				esc_url( add_query_arg( array( 'ensbl-view' => 'delete', 'venue_id' => $venue->id ), $base_url ) ),
				sprintf( _x( 'Delete %s', 'ensemble' ), $venue->name ),
				_x( 'Delete', 'venue', 'ensemble' )
			);
		}

		/**
		 * Filters the array of row action links on the venues list table.
		 *
		 * @since 1.0.0
		 *
		 * @param string[] $actions An array of row action links.
		 * @param Object   $venue   The current venue object.
		 */
		$actions = apply_filters( 'ensemble_venues_row_actions', $actions, $venue );

		return $this->row_actions( $actions );
	}


}
