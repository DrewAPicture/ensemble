<?php
/**
 * Defines the contract under which component objects are built
 *
 * @package   Ensemble\Core\Interfaces
 * @copyright Copyright (c) 2018, Drew Jaynes
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.0.0
 */
namespace Ensemble\Core\Interfaces;

/**
 * Defines common traits all component objects should take.
 *
 * @since 1.0.0
 */
interface Custom_Object {

	/**
	 * Retrieves the object ID.
	 *
	 * @since 1.0.0
	 *
	 * @return int Object ID.
	 */
	public function get_ID();

	/**
	 * Retrieves the corresponding Database instance.
	 *
	 * @since 1.0.0
	 * @static
	 *
	 * @return \Ensemble\Core\Database
	 */
	public static function db();

	/**
	 * Retrieves the built cache key for the given single object.
	 *
	 * @since 1.0.0
	 * @static
	 *
	 * @param int $object_id Object ID.
	 * @return string Cache key for the object type and ID.
	 */
	public static function get_cache_key( $object_id );

}
