<?php
/**
 * Sets up the Venues component
 *
 * @package Ensemble\Components\Venues
 * @since   1.0.0
 */
namespace Ensemble\Components\Venues;

/**
 * Implements Contests component functionality in Ensemble core.
 *
 * @since 1.0.0
 *
 * @see Ensemble\Base
 */
class Init {

	/**
	 * Meta instance.
	 *
	 * @since 1.0.0
	 * @var   \Ensemble\People\Staff\Meta
	 */
	private $meta;

	/**
	 * Database instance.
	 *
	 * @since 1.0.0
	 * @var   \Ensemble\People\Staff\Database
	 */
	private $db;

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->includes();

		$this->db   = new Database();
		$this->meta = new Meta();
	}

	/**
	 * Includes needed files.
	 */
	private function includes() {
//		require_once( ENSEMBLE_PLUGIN_DIR . '/includes/admin/people/staff-admin.php' );
		require_once( ENSEMBLE_PLUGIN_DIR . '/includes/people/staff/class-database.php' );
//		require_once( ENSEMBLE_PLUGIN_DIR . '/includes/people/staff/functions.php' );
		require_once( ENSEMBLE_PLUGIN_DIR . '/includes/people/staff/meta-class-database.php' );
	}

	/**
	 * Retrieves a single staff member object.
	 *
	 * @since 1.0.0
	 *
	 * @param int $member_id Staff member ID.
	 * @return \Ensemble\People\Staff|\WP_Error Staff member object or WP_Error if there was a problem.
	 */
	public function get( $member_id ) {

	}

	/**
	 * Queries for staff.
	 *
	 * @since 1.0.0
	 *
	 * @param array $query_args Staff query arguments.
	 * @param bool  $count      Optional. Whether this is a count query. Default false.
	 * @return array|int Array of results, or int if `$count` is true.
	 */
	public function query( $query_args, $count = false ) {
		return $this->db->query( $query_args, $count );
	}

	/**
	 * Retrieves a count of staff based on the given query arguments.
	 *
	 * @since 1.0.0
	 *
	 * @param array $query_args Query arguments.
	 * @return int Number of results for the given query arguments.
	 */
	public function query_count( $query_args ) {
		return $this->db->query( $query_args, true );
	}

	/**
	 * Retrieves user meta for the given staff member.
	 *
	 * @since 1.0.0
	 *
	 * @param int    $staff_id Staff member ID.
	 * @param string $meta_key User meta key.
	 * @param bool   $single   Optional. Whether to retrieve a single user meta value. Default false.
	 *
	 * @return mixed
	 */
	public function get_meta( $staff_id, $meta_key, $single = false ) {
		return $this->meta->get( $staff_id, $meta_key, $single );
	}

}
