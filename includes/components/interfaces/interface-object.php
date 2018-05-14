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
	 * Retrieves the built cache key for the given single object.
	 *
	 * @since 1.0.0
	 * @static
	 *
	 * @param int $object_id Object ID.
	 * @return string Cache key for the object type and ID.
	 */
	public static function get_cache_key( $object_id );

	/**
	 * Splits the db groups if there is more than one.
	 *
	 * CURIE is ':'.
	 *
	 * @since 1.0.0
	 * @static
	 *
	 * @return mixed Object containing the primary and secondary group values.
	 */
	public static function get_db_groups();

}
