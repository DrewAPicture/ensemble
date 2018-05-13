<?php
/**
 * Defines the contract under which Meta_Database classes exist
 *
 * @package Ensemble\Core\Interfaces
 *
 * @since 1.0.0
 */
namespace Ensemble\Core\Interfaces;

/**
 * Database interface.
 *
 * @since 1.0.0
 */
interface Meta_Database extends Database {

	/**
	 * Retrieves meta for the given object type.
	 *
	 * @since 1.0.0
	 *
	 * @param int    $object_id Object ID.
	 * @param string $meta_key  Meta key.
	 * @param bool   $single    Optional. Whether to retrieve a single meta value. Default false.
	 * @return mixed
	 */
	public function get( $object_id, $meta_key, $single = false );

}
