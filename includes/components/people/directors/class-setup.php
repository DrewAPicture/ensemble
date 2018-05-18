<?php
/**
 * Sets up the Directors component
 *
 * @package   Ensemble\Components\People\Directors
 * @copyright Copyright (c) 2018, Drew Jaynes
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.0.0
 */
namespace Ensemble\Components\People\Directors;

use Ensemble\Core\Interfaces\Loader;
use function Ensemble\{load};

/**
 * Implements Staff functionality in Ensemble core.
 *
 * @since 1.0.0
 *
 * @see Ensemble\Core\Interfaces\Loader
 */
class Setup implements Loader {

	/**
	 * Initializes the component.
	 *
	 * @since 1.0.0
	 */
	public function load() {
		if ( is_admin() ) {
			load( new Admin\Menu );
			load( new Admin\Actions );
		}
	}

}
