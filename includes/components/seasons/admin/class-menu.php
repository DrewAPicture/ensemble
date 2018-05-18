<?php
/**
 * Sets up the Seasons admin menu item
 *
 * @package   Ensemble\Components\Seasons\Admin
 * @copyright Copyright (c) 2018, Drew Jaynes
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.0.0
 */
namespace Ensemble\Components\Seasons\Admin;

use Ensemble\Core\Interfaces\Loader;
use function Ensemble\{load};

/**
 * Sets up the Seasons menu.
 *
 * @since 1.0.0
 *
 * @see Loader
 */
class Menu implements Loader {

	/**
	 * Initializes menu registrations.
	 *
	 * @since 1.0.0
	 */
	public function load() {
		add_action( 'admin_menu', array( $this, 'register_submenu' ), 30 );
	}

	/**
	 * Registers the Contests submenu.
	 *
	 * @since 1.0.0
	 */
	public function register_submenu() {
		add_submenu_page(
			'ensemble-admin',
			__( 'Seasons', 'ensemble' ),
			__( 'Seasons', 'ensemble' ),
			'manage_options',
			'edit-tags.php?taxonomy=ensemble_season'
		);
	}

}
