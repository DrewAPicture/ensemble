<?php
/**
 * Defines the object construct for a single Director
 *
 * @package   Ensemble\People
 * @copyright Copyright (c) 2018, Drew Jaynes
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.0.0
 */
namespace Ensemble\People\Directors;

/**
 * Defines a Staff Member object model.
 *
 * @since 1.0.0
 *
 * @see \Ensemble\Model
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
