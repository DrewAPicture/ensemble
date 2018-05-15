<?php
/**
 * Defines the contract for menu classes that route requests in the admin
 *
 * @package   Ensemble\Core\Interfaces
 * @copyright Copyright (c) 2018, Drew Jaynes
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.0.0
 */
namespace Ensemble\Core\Interfaces;

/**
 * Admin interface for routing requests via a menu class
 *
 * @since 1.0.0
 */
interface Menu_Router extends Loader {

	/**
	 * Routes the current request based on arbitrary factors.
	 *
	 * @since 1.0.0
	 */
	public function route_request();

}
