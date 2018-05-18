<?php
/**
 * Defines multi-dimensional logic for loading admin tabs
 *
 * @package   Ensemble\Core\Traits
 * @copyright Copyright (c) 2018, Drew Jaynes
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.0.0
 */
namespace Ensemble\Core\Traits;

/**
 * Core trait used for component classes needing to load tabs.
 *
 * @since 1.0.0
 */
trait Tab_Loader {

	/**
	 * Registers tab-related callbacks.
	 *
	 * @since 1.0.0
	 */
	public function register_tab_callbacks() {
		add_filter( "{$this->get_tab_component()}-get_tabs",                             array( $this, 'register_tab'        ),    11 );
		add_action( "{$this->get_tab_component()}-{$this->get_tab_slug()}_tab_contents", array( $this, 'output_tab_contents' ), 10, 2 );
	}

	/**
	 * Retrieves the identifier for the component implementing tabs.
	 *
	 * @since 1.0.0
	 *
	 * @return string Tab component identifier.
	 */
	abstract public function get_tab_component();

	/**
	 * Retrieves the tab slug.
	 *
	 * @since 1.0.0
	 *
	 * @return string Tab slug.
	 */
	abstract public function get_tab_slug();

	/**
	 * Retrieves the tab label.
	 *
	 * @since 1.0.0
	 *
	 * @return string Tab label.
	 */
	abstract public function get_tab_label();

	/**
	 * Registers a tab using the slug and label.
	 *
	 * @since 1.0.0
	 *
	 * @see get_tab_slug()
	 * @see get_tab_label()
	 *
	 * @param array $tabs Existing tabs.
	 * @return array Modified tabs array.
	 */
	public function register_tab( $tabs ) {
		$tabs[ $this->get_tab_slug() ] = $this->get_tab_label();

		return $tabs;
	}

	/**
	 * Outputs the contents of the tab.
	 *
	 * @since 1.0.0
	 */
	abstract public function output_tab_contents();

}