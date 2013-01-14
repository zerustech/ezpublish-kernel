<?php
/**
 * File containing the EzcDatabase location gateway class
 *
 * @copyright Copyright (C) 1999-2012 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 */

namespace eZ\Publish\Core\Persistence\SqlNg\User\Gateway;

use eZ\Publish\Core\Persistence\SqlNg\User\Gateway;
use eZ\Publish\Core\Persistence\Legacy\EzcDbHandler;
use eZ\Publish\SPI\Persistence\User;

/**
 * User gateway implementation using the zeta database component.
 */
class EzcDatabase extends Gateway
{
    /**
     * Database handler
     *
     * @var EzcDbHandler
     */
    protected $handler;

    /**
     * Construct from database handler
     *
     * @param \EzcDbHandler $handler
     */
    public function __construct( EzcDbHandler $handler )
    {
        $this->handler = $handler;
    }

    /**
     * Create user
     *
     * @param user $user
     *
     * @return mixed
     */
    public function createUser( User $user )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Delete user with the given ID.
     *
     * @param mixed $userId
     */
    public function deleteUser( $userId )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Loads user with user ID.
     *
     * @param mixed $userId
     *
     * @return array
     */
    public function load( $userId )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Loads user with user ID.
     *
     * @param string $login
     * @param string|null $email
     *
     * @return array
     */
    public function loadByLoginOrMail( $login, $email = null )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Update the user information specified by the user struct
     *
     * @param User $user
     */
    public function updateUser( User $user )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Assigns role to user with given limitation
     *
     * @param mixed $contentId
     * @param mixed $roleId
     * @param array $limitation
     */
    public function assignRole( $contentId, $roleId, array $limitation )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Remove role from user
     *
     * @param mixed $contentId
     * @param mixed $roleId
     */
    public function removeRole( $contentId, $roleId )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }
}
