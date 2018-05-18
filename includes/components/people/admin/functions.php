<?php
/**
 * People Admin Functions
 *
 * @package   Ensemble\Functions
 * @copyright Copyright (c) 2018, Drew Jaynes
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.0.0
 */
namespace Ensemble\Components\People\Admin;

/**
 * Retrieves the list of tabs for the People overview screen.
 *
 * @since 1.0.0
 *
 * @return array Array of People tabs in the form of slug\label pairs.
 */
function get_tabs() {
	$settings_tab = array(
		'settings' => _x( 'Settings', 'people', 'ensemble' )
	);

	/**
	 * Filters the tabs displayed on the People screen.
	 *
	 * Sub-components extending some aspect of People should register tabs here.
	 *
	 * @since 1.0.0
	 *
	 * @param array $tabs Array of people tabs.
	 */
	$tabs = apply_filters( 'ensemble_people-get_tabs', array() );

	return array_merge( $tabs, $settings_tab );
}

/**
 * Renders the contents of a given People tab (if it exists).
 *
 * @since 1.0.0
 *
 * @param string $tab People tab slug.
 */
function render_tab_contents( $tab ) {
	$tabs = get_tabs();

	if ( 'settings' === $tab ) {
		echo 'Settings go here.';
	} elseif ( array_key_exists( $tab, $tabs ) ) {
		/**
		 * Fires when the given tab output is called for.
		 *
		 * Will only fire for tabs registered via the {@see 'ensemble_people-get_tabs'} filter.
		 *
		 * The dynamic portion of the hook name, `$tab`, refers to the tab slug.
		 *
		 * @since 1.0.0
		 *
		 * @param string $tab  Current tab slug.
		 * @param arrray $tabs Array of people tabs.
		 */
		do_action( "ensemble_people-{$tab}_tab_contents", $tab, $tabs );
	}
}
