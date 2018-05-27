<?php
/**
 * Objects: User Object middleware
 *
 * @package   Ensemble\Components
 * @copyright Copyright (c) 2018, Drew Jaynes
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.0.0
 */
namespace Ensemble\Core;

use Ensemble\Core\Interfaces;

/**
 * Implements user object middleware.
 *
 * @since 1.0.0
 * @abstract
 */
abstract class User_Model extends \WP_User implements Interfaces\User_Model {

}

// Alias for the class pre-1.0.2, which fixed PHP 7.2+ compatibility. Derp.
class_alias( 'Ensemble\Core\User_Model', 'Ensemble\Core\User_Object' );
