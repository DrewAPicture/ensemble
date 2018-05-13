<?php
/**
 * Defines an object constructor for a single Venue
 *
 * @package Ensemble\Components\Venues
 *
 * @since 1.0.0
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
