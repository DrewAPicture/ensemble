<?php
namespace Ensemble\Core;

use Ensemble\Tests\UnitTestCase;
use function Ensemble\Components\Contests\get_contest;

/**
 * Core model superclass tests.
 *
 * @since 1.0.0
 *
 * @coversDefaultClass \Ensemble\Core\Model
 *
 * @group core
 * @group models
 */
class Model_Tests extends UnitTestCase {

	/**
	 * Model ID.
	 *
	 * @var int
	 */
	protected static $model_id;

	/**
	 * Model fixture.
	 *
	 * @var \Ensemble\Core\Model
	 */
	protected static $model;

	/**
	 * Sets up fixtures before the test class has loaded.
	 */
	public static function wpSetUpBeforeClass() {
		self::$model_id = self::ensemble()->contest->create();
		self::$model    = get_contest( self::$model_id );
	}

	/**
	 * @covers ::get_ID()
	 */
	public function test_get_ID_should_return_the_object_id() {
		$this->assertSame( self::$model_id, self::$model->get_ID() );
	}

	/**
	 * @covers ::get_instance()
	 */
	public function test_get_instance_with_0_invalid_id_should_return_WP_Error() {
		$this->assertWPError( self::$model::get_instance( 0 ) );
	}

	/**
	 * @covers ::get_instance()
	 */
	public function test_get_instance_with_0_invalid_id_should_return_WP_Error_including_code_get_instance_invalid_id() {
		$result = self::$model::get_instance( 0 );

		$this->assertContains( 'get_instance_invalid_id', $result->get_error_codes() );
	}

	/**
	 * @covers ::get_instance()
	 */
	public function test_get_instance_with_invalid_id_should_return_WP_Error() {
		$this->assertWPError( self::$model::get_instance( 999 ) );
	}

	/**
	 * @covers ::get_instance()
	 */
	public function test_get_instance_with_invalid_id_should_return_WP_Error_including_code_invalid_object() {
		$result = self::$model::get_instance( 999 );

		$this->assertContains( 'invalid_object', $result->get_error_codes() );
	}

	/**
	 * @covers ::get_instance()
	 */
	public function test_get_instance_with_valid_id_should_return_Model_object() {
		$result = self::$model::get_instance( self::$model->get_ID() );

		$this->assertInstanceOf( 'Ensemble\\Core\\Model', $result );
	}

	/**
	 * @covers ::get_cache_key()
	 */
	public function test_get_cache_key_should_return_the_table_suffix_and_object_id() {
		$expected = md5( self::$model::db()->get_table_suffix() . ':' . self::$model_id );

		$this->assertSame( $expected, self::$model::get_cache_key( self::$model_id ) );
	}

	/**
	 * @covers ::to_array()
	 */
	public function test_to_array_should_return_an_array_of_public_object_vars() {
		$result = self::$model->to_array();

		$this->assertInternalType( 'array', $result );
	}

	/**
	 * @covers ::populate_vars()
	 */
	public function test_populate_vars_with_object_and_populated_true_should_return_the_original_object() {
		$this->assertSame( self::$model, self::$model::populate_vars( self::$model ) );
	}

	/**
	 * @covers ::populate_vars()
	 */
	public function test_populate_vars_with_array_and_populated_true_should_return_the_original_array() {
		$expected = self::$model->to_array();

		$this->assertSame( $expected, self::$model::populate_vars( self::$model->to_array() ) );
	}

	/**
	 * @covers ::sanitize_field()
	 */
	public function test_sanitize_field_should_return_sanitized_field_value() {
		$this->assertSame( self::$model->get_ID(), self::$model::sanitize_field( 'id', self::$model->get_ID() ) );
	}

}

