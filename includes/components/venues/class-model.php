<?php
/**
 * Defines an object constructor for a single Venue
 *
 * @package   Ensemble\Components\Venues
 * @copyright Copyright (c) 2018, Drew Jaynes
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.0.0
 */
namespace Ensemble\Components\Venues;

/**
 * Defines the structure of a single venue.
 *
 * @since 1.0.0
 *
 * @see \WP_User
 */
class Model extends \WP_User {

	/**
	 * Staff member title.
	 *
	 * @access public
	 * @since  1.0.0
	 * @var    string
	 */
	public $title;

}
