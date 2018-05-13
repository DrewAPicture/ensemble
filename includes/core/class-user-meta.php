<?php
/**
 * Sets up the User_Meta extension of the Meta class
 *
 * @package   Ensemble\Core\Database
 * @copyright Copyright (c) 2018, Drew Jaynes
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.0.0
 */
namespace Ensemble\Core;

use Ensemble\Core\Meta_Interface as Meta;

/**
 * User meta abstraction layer.
 *
 * @since 1.0.0
 *
 * @see \Ensemble\Core\Meta
 */
class User_Meta {

	/**
	 * Retrieves meta for the given object type.
	 *
	 * @access public
	 * @since  1.0.0
	 *
	 * @param int    $object_id User ID.
	 * @param string $meta_key  Meta key.
	 * @param bool   $single    Optional. Whether to retrieve a single meta value. Default false.
	 * @return mixed
	 */
	public function get( $object_id, $meta_key, $single = false ) {
		return get_metadata( 'user', $object_id, $meta_key, $single );
	}

}
