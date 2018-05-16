<?php
/**
 * Defines a construct under which components have the ability to retrieve a template manifest
 *
 * @package   Ensemble\Core\Interfaces
 * @copyright Copyright (c) 2018, Drew Jaynes
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.0.0
 */
namespace Ensemble\Core\Interfaces;

/**
 * Loader interface for bootstrapping components.
 *
 * @since 1.0.0
 */
interface Views_Manifest {

	/**
	 * Retrieves templates registered to the component.
	 *
	 * @since 1.0.0
	 *
	 * @return array Templates registered to the component.
	 */
	public function get_views();

}
