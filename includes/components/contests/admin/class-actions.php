<?php
/**
 * Contests CRUD Actions
 *
 * @package   Ensemble\Components\Contests\Admin
 * @copyright Copyright (c) 2018, Drew Jaynes
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.0.0
 */
namespace Ensemble\Components\Contests\Admin;

use Ensemble\Components\Contests\Database;
use function Ensemble\Components\Contests\get_allowed_types;
use Ensemble\Core\Interfaces\Loader;
use Ensemble\Core\Traits\View_Loader;

/**
 * Sets up logic for performing CRUD actions on contests.
 *
 * @since 1.0.0
 *
 * @see Loader
 * @see View_Loader
 */
class Actions implements Loader {

	use View_Loader;

	/**
	 * Registers hook callbacks for contest actions.
	 *
	 * @since 1.0.0
	 */
	public function load() {
		add_action( 'init', array( $this, 'add_contest'    ) );
		add_action( 'init', array( $this, 'update_contest' ) );
		add_action( 'init', array( $this, 'delete_contest' ) );
	}

	/**
	 * Processes adding a new contest.
	 *
	 * @since 1.0.0
	 */
	public function add_contest() {
		$valid_request = $_REQUEST['ensemble-add-contest'] ?? false;

		// Bail if the request doesn't even match.
		if ( false === $valid_request ) {
			return;
		}

		$redirect = add_query_arg( 'page', 'ensemble-admin-contests', admin_url( 'admin.php' ) );
		$nonce    = $_REQUEST['ensemble-add-contest-nonce'] ?? false;

		if ( ! wp_verify_nonce( $nonce, 'ensemble-add-contest-nonce' ) ) {
			// TODO add notice handler for the different cases.
			if ( wp_redirect( $redirect ) ) {
				exit;
			}
		}

		$data = array(
			'name'        => empty( $_REQUEST['contest-name'] ) ? '' : sanitize_text_field( $_REQUEST['contest-name'] ),
			'description' => empty( $_REQUEST['contest-desc'] ) ? '' : wp_kses_post( $_REQUEST['contest-desc'] ),
			'start_date'  => empty( $_REQUEST['contest-start-date'] ) ? '' : sanitize_text_field( $_REQUEST['contest-start-date'] ),
			'start_date'  => empty( $_REQUEST['contest-end-date'] ) ? '' : sanitize_text_field( $_REQUEST['contest-end-date'] ),
			'type'        => empty( $_REQUEST['contest-type'] ) ? '' : sanitize_key( $_REQUEST['contest-type'] ),
			'status'      => empty( $_REQUEST['contest-status'] ) ? '' : sanitize_key( $_REQUEST['contest-status'] ),
			'external'    => empty( $_REQUEST['contest-external'] ) ? '' : sanitize_text_field( $_REQUEST['contest-external'] ),
		);

		$data['venues'] = array_map( 'absint', $_REQUEST['contest-venues'] ?? array() );

		$added = ( new Database )->insert( $data );

		// TODO add notice handler for the different cases.
		if ( wp_redirect( $redirect ) ) {
			exit;
		}
	}

	/**
	 * Processes updating an existing contest.
	 *
	 * @since 1.0.0
	 */
	public function update_contest() {
		$valid_request = $_REQUEST['ensemble-update-contest'] ?? false;

		// Bail if the request doesn't even match.
		if ( false === $valid_request ) {
			return;
		}

		$contest_id = absint( $_REQUEST['contest-id'] ?? 0 );

		$redirect = add_query_arg( array(
			'page'       => 'ensemble-admin-contests',
			'ensbl-view' => 'edit',
			'contest_id' => $contest_id,
		), admin_url( 'admin.php' ) );

		$nonce = $_REQUEST['ensemble-update-contest-nonce'] ?? false;

		if ( ! wp_verify_nonce( $nonce, 'ensemble-update-contest-nonce' ) || 0 === $contest_id ) {
			return;
		}

		$data = array(
			'id'          => $contest_id,
			'name'        => empty( $_REQUEST['contest-name'] ) ? '' : sanitize_text_field( $_REQUEST['contest-name'] ),
			'description' => empty( $_REQUEST['contest-desc'] ) ? '' : wp_kses_post( $_REQUEST['contest-desc'] ),
			'start_date'  => empty( $_REQUEST['contest-start-date'] ) ? '' : sanitize_text_field( $_REQUEST['contest-start-date'] ),
			'end_date'    => empty( $_REQUEST['contest-end-date'] ) ? '' : sanitize_text_field( $_REQUEST['contest-end-date'] ),
			'type'        => empty( $_REQUEST['contest-type'] ) ? '' : sanitize_key( $_REQUEST['contest-type'] ),
			'status'      => empty( $_REQUEST['contest-status'] ) ? '' : sanitize_key( $_REQUEST['contest-status'] ),
			'external'    => empty( $_REQUEST['contest-external'] ) ? '' : sanitize_text_field( $_REQUEST['contest-external'] ),
		);

		if ( ! empty( $_REQUEST['contest-venues'] ) ) {
			$data['venues'] = array_map( 'absint', $_REQUEST['contest-venues'] );
		} else {
			$data['venues'] = array();
		}

		$updated = ( new Database )->update( $contest_id, $data );

		// TODO add notice handler for the different cases.
		if ( wp_redirect( $redirect ) ) {
			exit;
		}
	}

	/**
	 * Processes deleting a contest.
	 *
	 * @since 1.0.0
	 */
	public function delete_contest() {
		$valid_request = $_REQUEST['ensemble-delete-contest'] ?? false;

		// Bail if the request doesn't even match.
		if ( false === $valid_request ) {
			return;
		}

		$nonce      = $_REQUEST['ensemble-delete-contest-nonce'] ?? false;
		$answer     = sanitize_key( $_REQUEST['contest-delete'] ?? 'no' );
		$contest_id = absint( $_REQUEST['contest-id'] ?? 0 );

		$redirect = add_query_arg( 'page', 'ensemble-admin-contests', admin_url( 'admin.php' ) );

		if ( ! wp_verify_nonce( $nonce, 'ensemble-delete-contest-nonce' ) || 'no' === $answer || 0 === $contest_id ) {
			// TODO add notice handler for the different cases.
			if ( wp_redirect( $redirect ) ) {
				exit;
			}
		}

		if ( 'yes' === $answer ) {
			$deleted = ( new Database )->delete( $contest_id );

			// TODO add notice handler for the different cases.
			if ( wp_redirect( $redirect ) ) {
				exit;
			}
		}
	}

	/**
	 * Retrieves registered contest views.
	 *
	 * @since 1.0.0
	 *
	 * @return array Registered contest views.
	 */
	public function get_views() {
		return array( 'overview', 'add', 'add-wizard', 'edit', 'delete' );
	}

	/**
	 * Retrieves the path/to/the/views.
	 *
	 * @since 1.0.0
	 *
	 * @return string Path to the view templates directory.
	 */
	public function get_views_dir() {
		return __DIR__ . '/views/';
	}

}