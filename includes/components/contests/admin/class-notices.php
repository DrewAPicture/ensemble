<?php
/**
 * Sets up the Contests admin notices
 *
 * @package   Ensemble\Components\Contests\Admin
 * @copyright Copyright (c) 2018, Drew Jaynes
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.0.0
 */
namespace Ensemble\Components\Contests\Admin;

use Ensemble\Core\Admin\Notices_Registry;
use Ensemble\Core\Interfaces\Loader;
use Ensemble\Core\Traits\Admin_Notices;

/**
 * Admin notices for contests.
 *
 * @since 1.0.0
 *
 * @see Loader
 */
class Notices implements Loader {

	use Admin_Notices;

	/**
	 * Sets up callbacks for registering admin notices.
	 *
	 * @since 1.0.0
	 */
	public function load() {
		$this->register_notices();
	}

	/**
	 * Registers admin notices.
	 *
	 * @since 1.0.0
	 */
	public function register_notices() {
		$registry = $this->get_registry();
	}

}
