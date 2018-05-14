<?php
/**
 * Defines the object construct for a single staff member
 *
 * @package   Ensemble\Components\People
 * @copyright Copyright (c) 2018, Drew Jaynes
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.0.0
 */
namespace Ensemble\Components\People\Staff;

use Ensemble\Components;

/**
 * Defines a Staff Member object model.
 *
 * @since 1.0.0
 *
 * @see \Ensemble\Components\User_Object
 */
class Object extends Components\User_Object {

	/**
	 * Staff member title.
	 *
	 * @access public
	 * @since  1.0.0
	 * @var    string
	 */
	public $title;

}
