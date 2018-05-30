<?php
namespace Ensemble;

use Ensemble\Tests\UnitTestCase;

/**
 * Tests for functions directly under the Ensemble namespace.
 *
 * @since 1.0.0
 */
class Functions_Tests extends UnitTestCase {

	/**
	 * @covers \Ensemble\html()
	 */
	public function test_html_should_return_an_HTML_instance() {
		$this->assertInstanceOf( 'Ensemble\\Util\\HTML', html() );
	}

	/**
	 * @covers \Ensemble\get_current_view()
	 */
	public function test_get_current_view_should_return_default_overview_if_default_not_set_and_REQUEST_var_not_set() {
		$this->assertSame( 'test', get_current_view( 'test' ) );
	}

	/**
	 * @covers \Ensemble\get_current_view()
	 */
	public function test_get_current_view_should_return_supplied_default_if_REQUEST_var_not_set() {
		$this->assertSame( 'overview', get_current_view() );
	}

	/**
	 * @covers \Ensemble\get_current_view()
	 */
	public function test_get_current_view_should_return_value_of_REQUEST_var_if_set() {
		$_REQUEST['ensbl-view'] = 'test';

		$this->assertSame( 'test', get_current_view() );

		// Clean up.
		unset( $_REQUEST['ensbl-view'] );
	}

	/**
	 * @covers \Ensemble\get_current_tab()
	 */
	public function test_get_current_tab_should_return_empty_string_if_default_not_supplied_and_REQUEST_var_not_set() {
		$this->assertSame( '', get_current_tab() );
	}

	/**
	 * @covers \Ensemble\get_current_tab()
	 */
	public function test_get_current_tab_should_return_supplied_default_if_set_and_REQUEST_var_not_set() {
		$this->assertSame( 'test', get_current_tab( 'test' ) );
	}

	/**
	 * @covers \Ensemble\get_current_tab()
	 */
	public function test_get_current_tab_should_return_value_of_REQUEST_var_if_set() {
		$_REQUEST['ensbl-tab'] = 'test';

		$this->assertSame( 'test', get_current_tab() );

		// Clean up.
		unset( $_REQUEST['ensbl-tab'] );
	}

}

