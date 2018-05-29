<?php
namespace Ensemble\Core;

use Ensemble\Tests\UnitTestCase;

/**
 * Core Database superclass tests.
 *
 * @since 1.0.0
 *
 * @coversDefaultClass \Ensemble\Core\Database
 *
 * @group database
 * @group core
 */
class Database_Tests extends UnitTestCase {

	/**
	 * Abstract Core\Database fixture.
	 *
	 * @var \Ensemble\Core\Database
	 */
	protected static $db;

	/**
	 * Sets up fixtures before the test class has loaded.
	 */
	public static function wpSetUpBeforeClass() {

		/*
		 * Use an anonymous class instead of a phpunit mock, because PHP 7.
		 *
		 * Setting up abstract methods with return values from an actual subclass
		 * allows the CRUD methods to be tested with real fixture data. Boom.
		 */
		self::$db = new class extends Database {
			public function get_cache_group() { return 'contests'; }

			public function get_query_object_type() { return 'Ensemble\\Components\\Contests\\Model'; }

			public function get_table_suffix() { return 'ensemble_contests'; }

			public function get_version() { return '1.0'; }

			public function get_columns() { return array(); }

			public function get_column_defaults() { return array(); }

			public function query( $query_args = array(), $count = false ) { return true === $count ? 1 : array(); }
		};
	}


	/**
	 * @covers ::get_primary_key()
	 */
	public function test_get_primary_key_should_get_the_primary_key() {
		$this->assertSame( 'id', $this->mock_DB()->get_primary_key() );
	}

	/**
	 * Mocks a copy of the Ensemble\Core\Database class.
	 *
	 * @return \Ensemble\Core\Database
	 */
	protected function mock_DB() {
		return $this->getMockForAbstractClass( 'Ensemble\\Core\\Database' );
	}
}

