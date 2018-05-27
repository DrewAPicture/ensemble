<?php
/**
 * Defines the contract under which user objects are built
 *
 * @package   Ensemble\Core\Interfaces
 * @copyright Copyright (c) 2018, Drew Jaynes
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.0.0
 */
namespace Ensemble\Core\Interfaces;

/**
 * Defines common traits all people objects should take.
 *
 * @since 1.0.0
 */
interface User_Model {

}

// Alias for the interface pre-1.0.2, which fixed PHP 7.2+ compatibility. Derp.
class_alias( 'Ensemble\Core\Interfaces\User_Model', 'Ensemble\Core\Interfaces\User_Object' );
