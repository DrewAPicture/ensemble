<?php
/**
 * List table used on Contests Overview
 *
 * @package   Ensemble\Components\Contests\Admin
 * @copyright Copyright (c) 2018, Drew Jaynes
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.0.0
 */
namespace Ensemble\Components\Contests\Admin;
use Ensemble\Components\Contests\Database;
use Ensemble\Components\Contests\Object;

/**
 * Implements a list table for contests.
 *
 * @since 1.0.0
 *
 * @see \WP_List_Table
 */
class List_Table extends \WP_List_Table {

	/**
	 * Represents the default number of contests to show per page.
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
			'singular' => 'contest',
			'plural'   => 'contests',
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
		$base           = add_query_arg( 'page', 'ensemble-admin-contests', admin_url( 'admin.php' ) );

		$current        = isset( $_GET['status'] ) ? $_GET['status'] : '';
		$total_count    = '&nbsp;<span class="count">(' . $this->total_count    . ')</span>';
		$active_count   = '&nbsp;<span class="count">(' . $this->active_count . ')</span>';
		$inactive_count = '&nbsp;<span class="count">(' . $this->inactive_count  . ')</span>';

		$views = array(
			'all' => sprintf( '<a href="%1$s"%2$s>%3$s</a>',
				esc_url( remove_query_arg( 'status', $base ) ),
				$current === 'all' || $current == '' ? ' class="current"' : '',
				esc_html__( 'All', 'ensemble') . $total_count
			),

			'active'   => sprintf( '<a href="%1$ss"%2$s>%3$s</a>',
				esc_url( add_query_arg( 'status', 'active', $base ) ),
				$current === 'active' ? ' class="current"' : '',
				esc_html__( 'Active', 'ensemble') . $active_count
			),

			'inactive' => sprintf( '<a href="%1$ss"%2$s>%3$s</a>',
				esc_url( add_query_arg( 'status', 'inactive', $base ) ),
				$current === 'inactive' ? ' class="current"' : '',
				esc_html__( 'Inactive', 'ensemble') . $inactive_count
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
			'cb'         => '<input type="checkbox" />',
			'name'       => __( 'Name', 'ensemble' ),
			'venues'     => __( 'Venue(s)', 'ensemble' ),
			'type'       => __( 'Type', 'ensemble' ),
			'status'     => __( 'Status', 'ensemble' ),
			'start_date' => __( 'Date', 'ensemble' ),
		);

		/**
		 * Filters the contests list table columns.
		 *
		 * @since 1.0.0
		 *
		 * @param array      $columns The columns for this list table.
		 * @param List_Table $this    List table instance.
		 */
		return apply_filters( 'ensemble_contests_table_columns', $columns, $this );
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
			'start_date' => array( 'start_date', false ),
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
		$order   = isset( $_GET['order'] )   ? $_GET['order']           : 'DESC';
		$orderby = isset( $_GET['orderby'] ) ? $_GET['orderby']         : 'start_date';

		$per_page = $this->get_items_per_page( 'ensemble_contests_per_page', $this->per_page );

		$args = array(
			'number'  => $per_page,
			'offset'  => $per_page * ( $page - 1 ),
			'status'  => $status,
			'search'  => $search,
			'orderby' => sanitize_text_field( $orderby ),
			'order'   => sanitize_text_field( $order )
		);

		$contests = ( new Database )->query( $args );

		// Retrieve the "current" total count for pagination purposes.
		unset( $args['number'] );

		$this->current_count = ( new Database )->count( $args );

		return $contests;
	}

	/**
	 * Prepares contest items for display.
	 *
	 * @since 1.0.0
	 */
	public function prepare_items() {
		$per_page = $this->get_items_per_page( 'ensemble_contests_per_page', $this->per_page );

		$columns = $this->get_columns();
		$hidden  = array();
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
	 * @param Object $contest     The current contest object.
	 * @param string $column_name The name of the column.
	 * @return string The column value.
	 */
	public function column_default( $contest, $column_name ) {
		switch( $column_name ){

			default:
				$value = isset( $contest->$column_name ) ? $contest->$column_name : '';
				break;
		}

		/**
		 * Filters the default value for each contests list table column.
		 *
		 * @since 1.0.0
		 *
		 * @param string $value   The column data.
		 * @param Object $contest The current contest object.
		 */
		return apply_filters( 'ensemble_contests_table_' . $column_name, $value, $contest );
	}

}
