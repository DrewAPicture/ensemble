<?php
/**
 * Defines the contract under which component objects are built
 *
 * @package   Ensemble\Components\Interfaces
 * @copyright Copyright (c) 2018, Drew Jaynes
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.0.0
 */
namespace Ensemble\Components\Interfaces;

/**
 * Defines common traits all component objects should take.
 *
 * @since 1.0.0
 */
interface Object {

	/**
	 * Retrieves the object ID.
	 *
	 * @since 1.0.0
	 *
	 * @return int Object ID.
	 */
	public function get_ID();

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
