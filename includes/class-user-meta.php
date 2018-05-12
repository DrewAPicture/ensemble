<?php
namespace Ensemble\Core;

use Ensemble\Core;

/**
 * User meta abstraction layer.
 *
 * @since 1.0.0
 *
 * @see \Ensemble\Core\Meta
 */
class User_Meta implements Core\Meta {

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
