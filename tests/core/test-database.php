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

