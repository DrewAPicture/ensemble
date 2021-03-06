<?php
/**
 * Bootstraps Ensemble Components
 *
 * @package   Ensemble\Core
 * @copyright Copyright (c) 2018, Drew Jaynes
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.0.0
 */
namespace Ensemble\Core;

use Ensemble\Components as Component;
use function Ensemble\{load};

/**
 * Sets up components.
 *
 * @since 1.0.0
 */
class Components implements Interfaces\Loader {

	/**
	 * Initializes the components.
	 *
	 * @since 1.0.0
	 */
	public function load() {
		// Taxonomies needed to be loaded first to be available to other components.
		load( new Component\Seasons\Setup );
		load( new Component\Units\Setup );
		load( new Component\Classifications\Setup );

		load( new Component\Venues\Setup );
		load( new Component\Contests\Setup );
		load( new Component\People\Directors\Setup );
		load( new Component\People\Instructors\Setup );
		load( new Component\Integrations\Setup );
	}

}
