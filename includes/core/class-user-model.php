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
