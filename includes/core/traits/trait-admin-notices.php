<?php
/**
 * Defines multi-dimensional logic for loading admin tabs
 *
 * @package   Ensemble\Core\Traits
 * @copyright Copyright (c) 2018, Drew Jaynes
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.0.0
 */
namespace Ensemble\Core\Traits;

use Ensemble\Core\Admin\Notices_Registry;

/**
 * Core trait used for component classes needing to load tabs.
 *
 * @since 1.0.0
 */
trait Admin_Notices {

	/**
	 * Notices registry instance.
	 *
	 * @since 1.0.0
	 * @var   \Ensemble\Util\Notices_Registry
	 */
	public $registry;

	/**
	 * Retrieves the Notices_Registry instance.
	 *
	 * @since 1.0.0
	 *
	 * @return Notices_Registry Notices registry instance.
	 */
	public function get_registry() {
		return Notices_Registry::instance();
	}

	/**
	 * Registers admin notices.
	 *
	 * @since 1.0.0
	 */
	abstract public function register_notices();

}