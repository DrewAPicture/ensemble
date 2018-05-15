<?php
/**
 * Defines the contract under which classes with view loading capabilities exist
 *
 * @package   Ensemble\Core\Interfaces
 * @copyright Copyright (c) 2018, Drew Jaynes
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.0.0
 */
namespace Ensemble\Core\Interfaces;

/**
 * Renderer interface for loading templates.
 *
 * @since 1.0.0
 */
interface View_Loader extends Loader {

	/**
	 * Loads a view.
	 *
	 * @since 1.0.0
	 *
	 * @param array $args Optional. Passed-thru display arguments (if any). Default empty array.
	 */
	public function load_view( $args = array() );

}
