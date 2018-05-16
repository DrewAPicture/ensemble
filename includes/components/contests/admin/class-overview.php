<?php
/**
 * Contests Overview
 *
 * @package   Ensemble\Components\Contests\Admin
 * @copyright Copyright (c) 2018, Drew Jaynes
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.0.0
 */
namespace Ensemble\Components\Contests\Admin;

use Ensemble\Components\Contests\Setup;
use Ensemble\Core\Interfaces\Loader;
use Ensemble\Core\Traits\View_Loader;

/**
 * Handles displaying and managing the contests overview.
 *
 * @since 1.0.0
 */
class Overview extends Screen implements Loader {

	use View_Loader;

	/**
	 * Registers hook callbacks for listing contests.
	 *
	 * @since 1.0.0
	 */
	public function load() {

	}

}
