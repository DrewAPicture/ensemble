<?php
/**
 * Objects: Base Object Model
 *
 * @package   Ensemble\Core
 * @copyright Copyright (c) 2018, Drew Jaynes
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.0.0
 */

namespace Ensemble\Core;

/**
 * Implements a base object model.
 *
 * @since 1.0.0
 * @abstract
 */
abstract class Model {

	/**
	 * Whether the object members have been populated.
	 *
	 * @access protected
	 * @since  1.0.0
	 * @var    bool|null
	 */
	protected $populated = null;

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
		$cache_group = static::$object_type;

		$_object = wp_cache_get( $cache_key, $cache_group );

		if ( false === $_object ) {
			$db_groups = self::get_db_groups();

			if ( isset( $db_groups->secondary ) ) {
				$_object = ensemble()->{$db_groups->primary}->{$db_groups->secondary}->get( $object_id );
			} else {
				$_object = ensemble()->{$db_groups->primary}->get( $object_id );
			}

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
	 * @see Object::get_instance()
	 * @see affwp_clean_item_cache()
	 *
	 * @param int $object_id Object ID.
	 * @return string Cache key for the object type and ID.
	 */
	public static function get_cache_key( $object_id ) {
		return md5( static::$cache_token . ':' . $object_id );
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
		if ( isset( $this->{$key} ) ) {
			return $this->{$key};
		}
	}

	/**
	 * Sets a property.
	 *
	 * @access public
	 * @since  1.0.0
	 *
	 * @see set()
	 *
	 * @param string $key   Property name.
	 * @param mixed  $value Property value.
	 */
	public function __set( $key, $value ) {
		$this->set( $key, $value );
	}

	/**
	 * Sets an object property value and optionally saves.
	 *
	 * @internal Note: Checking isset() on $this->{$key} is missing here because
	 *           this method is also used directly by __set() which is leveraged for
	 *           magic properties.
	 *
	 * @access public
	 * @since  1.0.0
	 *
	 * @param string $key   Property name.
	 * @param mixed  $value Property value.
	 * @param bool   $save  Optional. Whether to save the new value in the database.
	 * @return int|\WP_Error True if the value was set. If `$save` is true, true if the save was successful.
	 *                       WP_Error object if `$save` is true and the save was unsuccessful..
	 */
	public function set( $key, $value, $save = false ) {
		$this->$key = static::sanitize_field( $key, $value );

		if ( true === $save ) {
			// Only real properties can be saved.
			$keys = array_keys( get_class_vars( get_called_class() ) );

			if ( ! in_array( $key, $keys ) ) {
				return new \WP_Error( 'model_set_invalid_key', '', compact( $key, $keys ) );
			}

			return $this->save();
		}

		return true;
	}

	/**
	 * Saves an object with current property values.
	 *
	 * @access public
	 * @since  1.0.0
	 *
	 * @return bool True on success, false on failure.
	 */
	public function save() {
		$db_groups = self::get_db_groups();

		// Handle secondary groups.
		if ( isset( $db_groups->secondary ) ) {
			$updated = ensemble()->{$db_groups->primary}->{$db_groups->secondary}->update( $this->ID, $this->to_array() );
		} else {
			$updated = ensemble()->{$db_groups->primary}->update( $this->ID, $this->to_array() );
		}

		if ( $updated ) {
			return true;
		}

		return false;
	}

	/**
	 * Splits the db groups if there is more than one.
	 *
	 * CURIE is ':'.
	 *
	 * @access public
	 * @since  1.0.0
	 * @static
	 *
	 * @return object Object containing the primary and secondary group values.
	 */
	public static function get_db_groups() {
		$groups = [
			'primary' => static::$db_group
		];

		if ( false !== strpos( static::$db_group, ':' ) ) {
			$split = explode( ':', static::$db_group, 2 );

			if ( 2 == count( $split) ) {
				$groups['primary']   = $split[0];
				$groups['secondary'] = $split[1];
			}
		}

		return (object) $groups;
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