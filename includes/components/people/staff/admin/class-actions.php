<?php
/**
 * Staff Actions
 *
 * @package   Ensemble\Components\People\Staff\Admin
 * @copyright Copyright (c) 2018, Drew Jaynes
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.0.0
 */
namespace Ensemble\Components\People\Staff\Admin;

use Ensemble\Core\Interfaces\Loader;
use Ensemble\Core\Traits\{View_Loader, Tab_Loader};

/**
 * Sets up logic for performing staff-related actions.
 *
 * @since 1.0.0
 *
 * @see Loader
 * @see View_Loader
 * @see Tab_Loader
 */
class Actions implements Loader {

	use View_Loader, Tab_Loader;

	/**
	 * Registers hook callbacks for staff actions.
	 *
	 * @since 1.0.0
	 */
	public function load() {
		$this->register_tab_callbacks();
	}

	/**
	 * Retrieves registered staff views.
	 *
	 * @since 1.0.0
	 *
	 * @return array Registered director views.
	 */
	public function get_views() {
		return array( 'tab' );
	}

	/**
	 * Retrieves the path/to/the/views.
	 *
	 * @since 1.0.0
	 *
	 * @return string Path to the view templates directory.
	 */
	public function get_views_dir() {
		return __DIR__ . '/views/';
	}

	/**
	 * Retrieves the identifier for the component whose tabs API
	 * this component is hooking into.
	 *
	 * @since 1.0.0
	 *
	 * @return string Tab component identifier.
	 */
	public function get_tab_component() {
		return 'ensemble_people';
	}

	/**
	 * Retrieves the tab slug.
	 *
	 * @since 1.0.0
	 *
	 * @return string Tab slug.
	 */
	public function get_tab_slug() {
		return 'staff';
	}

	/**
	 * Retrieves the tab label.
	 *
	 * @since 1.0.0
	 *
	 * @return string Tab label.
	 */
	public function get_tab_label() {
		return __( 'Circuit Staff', 'ensemble' );
	}

	/**
	 * Outputs the contents of the tab.
	 *
	 * @since 1.0.0
	 */
	public function output_tab_contents() {
		$this->load_view( 'tab' );
	}

}