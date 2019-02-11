<?php
/**
 * Sets up the Instructors component
 *
 * @package   Ensemble\Components\People\Instructors
 * @copyright Copyright (c) 2019, Drew Jaynes
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.1.0
 */
namespace Ensemble\Components\People\Instructors;

use Ensemble\Core\Interfaces\Loader;
use function Ensemble\{load};

/**
 * Implements Instructors functionality in Ensemble core.
 *
 * @since 1.1.0
 *
 * @see Ensemble\Core\Interfaces\Loader
 */
class Setup implements Loader {

	/**
	 * Initializes the component.
	 *
	 * @since 1.1.0
	 */
	public function load() {
		if ( is_admin() ) {
			load( new Admin\Menu );
			load( new Admin\Notices );
			load( new Admin\Actions );
		}
	}

}
