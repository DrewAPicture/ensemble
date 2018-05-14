<?php
/**
 * Objects: User Object
 *
 * @package   Ensemble\Components
 * @copyright Copyright (c) 2018, Drew Jaynes
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.0.0
 */
namespace Ensemble\Components;

use Ensemble\Components\Interfaces\User_Object as User_Object;

/**
 * Implements user object middleware.
 *
 * @since 1.0.0
 * @abstract
 */
abstract class User_Object extends \WP_User implements User_Object {

}