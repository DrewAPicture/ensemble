<?php
/**
 * Contest Add and Edit
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
 * Handles adding and editing contests in the admin.
 *
 * @since 1.0.0
 *
 * @see Screen
 * @see Loader
 */
class Save extends Screen implements Loader {

	use View_Loader;

	/**
	 * Registers hook callbacks for adding and editing contests.
	 *
	 * @since 1.0.0
	 */
	public function load() {

	}

}
