<?php
/**
 * Sets up the People component
 *
 * @package   Ensemble\Components\People
 * @copyright Copyright (c) 2018, Drew Jaynes
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.0.0
 */
namespace Ensemble\Components\People;

use Ensemble\Core\Interfaces\Loader;
use function Ensemble\{load};

/**
 * Implements People component functionality in Ensemble core.
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

		load( new Directors\Setup );
		load( new Staff\Setup );
	}

}
