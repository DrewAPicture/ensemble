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

}

