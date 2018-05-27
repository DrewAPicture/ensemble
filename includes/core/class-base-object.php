<?php
/**
 * Objects: Base Component Object
 *
 * @package   Ensemble\Core
 * @copyright Copyright (c) 2018, Drew Jaynes
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.0.0
 */
namespace Ensemble\Core;

/**
 * Implements a base object.
 *
 * @since 1.0.0
 * @abstract
 */
abstract class Base_Object implements Interfaces\Custom_Object {

	/**
	 * Whether the object members have been populated.
	 *
	 * @access protected
	 * @since  1.0.0
	 * @var    bool|null
	 */
	protected $populated = null;

	/**
	 * Retrieves the object ID.
	 *
	 * @since 1.0.0
	 *
	 * @return int Object ID.
	 */
	public function get_ID() {
		return $this->id;
	}

	/**
	 * Retrieves the object instance.
	 *
	 * @access public
	 * @since  1.0.0
	 * @static
	 *
	 * @param int $object Object ID.
	 * @return object|\WP_Error Object instance or WP_Error object if there was a problem.
	 */
	public static function get_instance( $object_id ) {
		if ( ! (int) $object_id ) {
			return new \WP_Error( 'get_instance_invalid_id' );
		}

		$Sub_Class   = get_called_class();
		$cache_key   = self::get_cache_key( $object_id );
		$cache_group = static::db()->get_cache_group();

		$_object = wp_cache_get( $cache_key, $cache_group );

		if ( false === $_object ) {
			$_object = static::db()->get( $object_id );

			if ( is_wp_error( $_object ) ) {
				return $_object;
			}

			$_object = self::populate_vars( $_object );

			wp_cache_add( $cache_key, $_object, $cache_group );
		} elseif ( empty( $_object->populated ) ) {
			$_object = self::populate_vars( $_object );
		}
		return new $Sub_Class( $_object );
	}

	/**
	 * Retrieves the built cache key for the given single object.
	 *
	 * @access public
	 * @since  1.0.0
	 * @static
	 *
	 * @see Base_Object::get_instance()
	 * @see clean_item_cache()
	 *
	 * @param int $object_id Object ID.
	 *
	 * @return string Cache key for the object type and ID.
	 */
	public static function get_cache_key( $object_id ) {
		return md5( static::db()->get_table_suffix() . ':' . $object_id );
	}

	/**
	 * Runs during object instantiation.
	 *
	 * @access public
	 * @since  1.0.0
	 *
	 * @param mixed $object Object to populate members for.
	 */
	public function __construct( $object ) {
		foreach ( get_object_vars( $object ) as $key => $value ) {
			$this->$key = $value;
		}
	}

	/**
	 * Retrieves the value of a given property.
	 *
	 * @access public
	 * @since  1.0.0
	 *
	 * @param string $key Property to retrieve a value for.
	 * @return mixed Otherwise, the value of the property if set.
	 */
	public function __get( $key ) {
		if ( method_exists( "get_{$key}" ) ) {
			return call_user_func( "get_{$key}" );
		} elseif ( isset( $this->{$key} ) ) {
			return $this->{$key};
		}
	}

	/**
	 * Converts the given object to an array.
	 *
	 * @access public
	 * @since  1.0.0
	 *
	 * @param mixed $object Object.
	 * @return array Array version of the given object.
	 */
	public function to_array() {
		return get_object_vars( $this );
	}

	/**
	 * Populates object members.
	 *
	 * @access public
	 * @since  1.0.0
	 * @static
	 *
	 * @param object|array $object_data Object or array of object data.
	 * @param array        $extra_vars  Optional. Additional vars to ensure get populated.
	 *                                  Default empty array.
	 * @return object|array Object or data array with filled members.
	 */
	public static function populate_vars( $object_data, $extra_vars = array() ) {
		if ( is_object( $object_data ) ) {
			if ( isset( $object_data->populated ) && empty( $extra_vars ) ) {
				return $object_data;
			}

			$vars   = get_object_vars( $object_data );
			$fields = empty( $extra_vars ) ? $vars : wp_parse_args( $extra_vars, $vars );

			foreach ( $fields as $field => $value ) {
				$object_data->$field = static::sanitize_field( $field, $value );

				$object_data->populated = true;
			}
		} elseif ( is_array( $object_data ) ) {
			if ( isset( $object_data['populated'] ) && empty( $extra_vars ) ) {
				return $object_data;
			}

			$fields = empty( $extra_vars ) ? $object_data : wp_parse_args( $extra_vars, $object_data );

			foreach ( $fields as $field => $value ) {
				$object_data[ $field ] = static::sanitize_field( $field, $value );

				$object_data['populated'] = true;
			}
		}
		return $object_data;
	}

	/**
	 * Sanitizes a given object field's value.
	 *
	 * Sub-class should override this method.
	 *
	 * @access public
	 * @since  1.0.0
	 * @static
	 *
	 * @param string $field Object field.
	 * @param mixed  $value Object field value.
	 * @return mixed Sanitized value for the given field.
	 */
	public static function sanitize_field( $field, $value ) {
		return $value;
	}

}