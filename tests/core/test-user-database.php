<?php
namespace Ensemble\Core;

use Ensemble\Tests\UnitTestCase;

/**
 * Core User_Database middleware tests.
 *
 * @since 1.0.0
 *
 * @coversDefaultClass \Ensemble\Core\User_Database
 *
 * @group core
 * @group database
 * @group users
 */
class User_Database_Tests extends UnitTestCase {

	/**
	 * Abstract Core\User_Database fixture.
	 *
	 * @var \Ensemble\Core\User_Database
	 */
	protected static $db;

	/**
	 * Sets up fixtures before the test class has loaded.
	 */
	public static function wpSetUpBeforeClass() {

		// Use an anonymous class instead of a phpunit mock, because PHP 7.
		self::$db = self::get_user_db();
	}

	/**
	 * @covers ::get()
	 */
	public function test_get_with_an_invalid_user_id_should_return_false() {
		$this->assertFalse( self::$db->get( 999 ) );
	}

	/**
	 * @covers ::query()
	 */
	public function test_query_should_return_an_array_if_default_count_is_false() {
		$result = self::$db->query( array(
			'fields' => 'ids',
		) );

		$this->assertInternalType( 'array', $result );
	}

	/**
	 * @covers ::query()
	 */
	public function test_query_should_return_an_integer_if_count_is_true() {
		$this->assertTrue( is_numeric( self::$db->query( array(), true ) ) );
	}

	/**
	 * @covers ::count()
	 */
	public function test_count_should_return_an_integer() {
		$this->assertTrue( is_numeric( self::$db->count() ) );
	}

	/**
	 * Builds a "mock" abstract Core\User_Database object.
	 *
	 * @since 1.0.2
	 *
	 * @see Testable_Abstract
	 *
	 * @param array $args {
	 *     Optional. Arguments for overriding default method returns.
	 *
	 *     @type \WP_User  $get   Get method return.
	 *     @type array|int $query Query method return.
	 *     @type int       $count Count query method return.
	 * }
	 * @return User_Database "Mocked" Core\User_Database instance, except it's a fully-qualified object.
	 */
	protected static function get_user_db( $args = array() ) {
		if ( empty( $args ) ) {
			$overrides = null;
		} else {
			$overrides = $args;
		}

		// $overrides passed through the constructor to Testable_Abstract->set_overrides().
		$user_db_object = new class( $overrides ) extends User_Database {

			public function get( $user_id ) {
				if ( $get = $this->get_override( 'get' ) ) {
					return $get;
				} else {
					return parent::get( $user_id );
				}
			}

			public function query( $query_args, $count = false ) {
				if ( $query = $this->get_override( 'query' ) ) {
					return $query;
				} else {
					return parent::query( $query_args, $count );
				}
			}

			public function count( $query_args = array() ) {
				if ( $count = $this->get_override( 'count' ) ) {
					return $count;
				} else {
					return parent::count( $query_args );
				}
			}

		};

		return $user_db_object;
	}

}

