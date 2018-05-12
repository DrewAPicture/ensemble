<?php
namespace Ensemble\Core;

interface Meta_Interface {

	/**
	 * Retrieves meta for the given object type.
	 *
	 * @access public
	 * @since  1.0.0
	 *
	 * @param int    $object_id Object ID.
	 * @param string $meta_key  Meta key.
	 * @param bool   $single    Optional. Whether to retrieve a single meta value. Default false.
	 * @return mixed
	 */
	public function get( $object_id, $meta_key, $single = false );

}
