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

use Ensemble\Components\Venues;
use Ensemble\Components\Seasons\Setup as Seasons;
use Ensemble\Components\Contests\{Database, Model};
use function Ensemble\Components\Contests\{get_status_label, get_type_label};

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
	 * Number of items with the 'published' status found.
	 *
	 * @since 1.0.0
	 * @var   int
	 */
	public $published_count;

	/**
	 *  Number of items with the 'private' status found.
	 *
	 * @since 1.0.0
	 * @var   int
	 */
	public $private_count;

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

		$this->published_count = ( new Database )->count( array(
			'status' => 'published',
			'search' => $search,
		) );

		$this->private_count = ( new Database )->count( array(
			'status' => 'private',
			'search' => $search,
		) );

		$this->total_count = $this->published_count + $this->private_count;
	}

	/**
	 * Retrieves the view types.
	 *
	 * @since 1.0.0
	 *
	 * @return array All available views.
	 */
	public function get_views() {
		$base = add_query_arg( 'page', 'ensemble-admin-contests', admin_url( 'admin.php' ) );

		$current         = isset( $_GET['status'] ) ? $_GET['status'] : '';
		$total_count     = '&nbsp;<span class="count">(' . $this->total_count     . ')</span>';
		$published_count = '&nbsp;<span class="count">(' . $this->published_count . ')</span>';
		$private_count   = '&nbsp;<span class="count">(' . $this->private_count     . ')</span>';

		$views = array(
			'all' => sprintf( '<a href="%1$s"%2$s>%3$s</a>',
				esc_url( remove_query_arg( 'status', $base ) ),
				$current === 'all' || $current == '' ? ' class="current"' : '',
				esc_html__( 'All', 'ensemble') . $total_count
			),

			'published' => sprintf( '<a href="%1$s"%2$s>%3$s</a>',
				esc_url( add_query_arg( 'status', 'published', $base ) ),
				$current === 'published' ? ' class="current"' : '',
				get_status_label( 'published' ) . $published_count
			),

			'private' => sprintf( '<a href="%1$s"%2$s>%3$s</a>',
				esc_url( add_query_arg( 'status', 'private', $base ) ),
				$current === 'private' ? ' class="current"' : '',
				get_status_label( 'private' ) . $private_count
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
			'name'       => __( 'Name', 'ensemble' ),
			'venues'     => __( 'Venue(s)', 'ensemble' ),
			'season'     => __( 'Season', 'ensemble' ),
			'status'     => __( 'Status', 'ensemble' ),
			'start_date' => __( 'Start Date', 'ensemble' ),
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
		$page    = absint( $_REQUEST['paged'] ?? 1 );
		$status  = sanitize_key( $_REQUEST['status'] ?? '' );
		$search  = sanitize_text_field( $_REQUEST['s'] ?? '' );
		$order   = sanitize_text_field( $_REQUEST['order'] ?? 'DESC' );
		$orderby = sanitize_key( $_REQUEST['orderby'] ?? 'start_date' );
		$venue   = absint( $_REQUEST['venue_id'] ?? 0 );
		$season  = absint( $_REQUEST['season_id'] ?? 0 );

		$per_page = $this->get_items_per_page( 'ensemble_contests_per_page', $this->per_page );

		$args = array(
			'number'  => $per_page,
			'offset'  => $per_page * ( $page - 1 ),
			'status'  => $status,
			'search'  => $search,
			'orderby' => $orderby,
			'order'   => $order,
		);

		if ( ! empty( $venue ) ) {
			$args['venues'] = array( $venue );
		}

		if ( ! empty( $season ) ) {
			$contest_ids = get_objects_in_term( $season, ( new Seasons )->get_taxonomy_slug() );

			if ( ! empty( $contest_ids ) && ! is_wp_error( $contest_ids ) ) {
				$args['id'] = $contest_ids;
			}
		}

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

		$columns  = $this->get_columns();
		$hidden   = array();
		$sortable = $this->get_sortable_columns();

		$this->_column_headers = array( $columns, $hidden, $sortable );

		$data = $this->get_data();

		$current_page = $this->get_pagenum();

		$status = isset( $_GET['status'] ) ? $_GET['status'] : 'any';

		switch( $status ) {
			case 'published':
				$total_items = $this->published_count;
				break;
			case 'private':
				$total_items = $this->private_count;
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
	 * @param Model  $contest     The current contest object.
	 * @param string $column_name The name of the column.
	 *
	 * @return string The column value.
	 */
	public function column_default( $contest, $column_name ) {
		$base_url = add_query_arg( 'page', 'ensemble-admin-contests', admin_url( 'admin.php' ) );

		$value = '';

		switch( $column_name ){

			case 'name':
				if ( current_user_can( 'manage_options' ) ) {
					$value = sprintf( '<a href="%1$s" aria-label="%2$s">%3$s</a>',
						esc_url( add_query_arg( array( 'ensbl-view' => 'edit', 'contest_id' => $contest->id ), $base_url ) ),
						sprintf( _x( 'Edit %s', 'ensemble' ), $contest->name ),
						$contest->name
					);
				} else {
					$value = $contest->name;
				}

				if ( ! empty( $contest->external ) ) {
					$value .= ' &mdash; ';
					$value .= '<span class="post-state">' . _x( 'External', 'contest URL', 'ensemble' ) . '</span>';
				}

				break;

			case 'start_date':
				$value = $contest->get_start_date();
				break;

			case 'status':
				$value = isset( $contest->status ) ? get_status_label( $contest->status ) : '';
				break;

			case 'season':
				$season = wp_get_object_terms( $contest->id, ( new Seasons )->get_taxonomy_slug(), array(
					'fields' => 'names',
					'number' => 1,
				) );

				if ( ! is_wp_error( $season ) && ! empty( $season ) ) {
					$value = reset( $season );
				}
				break;

			default:
				$value = $contest->$column_name ?? '';
				break;
		}

		/**
		 * Filters the default value for each contests list table column.
		 *
		 * @since 1.0.0
		 *
		 * @param string $value   The column data.
		 * @param Model  $contest The current contest object.
		 */
		return apply_filters( 'ensemble_contests_table_' . $column_name, $value, $contest );
	}

	/**
	 * Renders the Venue(s) column value.
	 *
	 * @since 1.0.0
	 *
	 * @param Model $contest Current contest object.
	 *
	 * @return string Value of the Venue(s) column.
	 */
	public function column_venues( $contest ) {
		// Convert to an array.
		$venue_ids = array_map( 'absint', explode( ',', $contest->venues ) );

		if ( empty( $venue_ids ) ) {
			return '';
		} else {
			$venues = ( new Venues\Database )->query( array(
				'id'     => $venue_ids,
				'number' => count( $venue_ids ),
				'fields' => array( 'id', 'name' ),
			) );

			$venue_links = array();

			if ( ! empty( $venues ) ) {
				$base_url = add_query_arg( 'page', 'ensemble-admin-contests', admin_url( 'admin.php' ) );

				foreach ( $venues as $venue ) {
					$venue_links[] = sprintf( '<a href="%1$s" aria-label="%2$s">%3$s</a>',
						esc_url( add_query_arg( array( 'venue_id' => $venue->id ), $base_url ) ),
						/* translators: Venue name */
						esc_attr( sprintf( __( 'View contests for the %s venue', 'ensemble' ), $venue->name ) ),
						$venue->name
					);
				}

				return implode( ', ', $venue_links );
			}
		}
	}

	/**
	 * Renders the message to be displayed when there are no contests.
	 *
	 * @since 1.0.0
	 */
	function no_items() {
		esc_html_e( 'You haven&#8217;t created any contests yet!', 'ensemble' );
	}

	/**
	 * Generates and displays row action links.
	 *
	 * @since 1.0.0
	 *
	 * @param Model  $contest     Current contest object.
	 * @param string $column_name Current column name.
	 * @param string $primary     Primary column name.
	 *
	 * @return string Row actions output for contests.
	 */
	protected function handle_row_actions( $contest, $column_name, $primary ) {
		if ( $primary !== $column_name || ! current_user_can( 'manage_options' ) ) {
			return '';
		}

		$actions  = array();
		$base_url = add_query_arg( 'page', 'ensemble-admin-contests', admin_url( 'admin.php' ) );

		$actions['edit'] = sprintf( '<a href="%1$s" aria-label="%2$s">%3$s</a>',
			esc_url( add_query_arg( array( 'ensbl-view' => 'edit', 'contest_id' => $contest->id ), $base_url ) ),
			sprintf( _x( 'Edit %s', 'ensemble' ), $contest->name ),
			_x( 'Edit', 'contest', 'ensemble' )
		);

		$actions['delete'] = sprintf( '<a href="%1$s" aria-label="%2$s">%3$s</a>',
			esc_url( add_query_arg( array( 'ensbl-view' => 'delete', 'contest_id' => $contest->id ), $base_url ) ),
			sprintf( _x( 'Delete %s', 'ensemble' ), $contest->name ),
			_x( 'Delete', 'contest', 'ensemble' )
		);

		/**
		 * Filters the array of row action links on the contests list table.
		 *
		 * @since 1.0.0
		 *
		 * @param string[] $actions An array of row action links.
		 * @param Model    $contest The current contest object.
		 */
		$actions = apply_filters( 'ensemble_contests_row_actions', $actions, $contest );

		return $this->row_actions( $actions );
	}

}
