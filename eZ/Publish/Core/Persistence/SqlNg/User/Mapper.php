<?php
/**
 * File containing the User mapper
 *
 * @copyright Copyright (C) 1999-2012 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 */

namespace eZ\Publish\Core\Persistence\SqlNg\User;

use eZ\Publish\SPI\Persistence\User;
use eZ\Publish\SPI\Persistence\User\Role;
use eZ\Publish\SPI\Persistence\User\Policy;
use eZ\Publish\SPI\Persistence\User\RoleAssignment;

/**
 * mapper for User related objects
 */
class Mapper
{
    /**
     * Map user data into user object
     *
     * @param array $data
     *
     * @return \eZ\Publish\SPI\Persistence\User
     */
    public function mapUser( array $data )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Map data for a set of user data
     *
     * @param array $data
     *
     * @return \eZ\Publish\SPI\Persistence\User[]
     */
    public function mapUsers( array $data )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Map policy data to an array of policies
     *
     * @param array $data
     *
     * @return \eZ\Publish\SPI\Persistence\User\Policy
     */
    public function mapPolicies( array $data )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Map role data to a role
     *
     * @param array $data
     *
     * @return \eZ\Publish\SPI\Persistence\User\Role
     */
    public function mapRole( array $data )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Map data for a set of roles
     *
     * @param array $data
     * @param boolean $indexById
     *
     * @return \eZ\Publish\SPI\Persistence\User\Role[]
     */
    public function mapRoles( array $data, $indexById = false )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Map data for a set of role assignments
     *
     * @param array $data
     *
     * @return \eZ\Publish\SPI\Persistence\User\RoleAssignment[]
     */
    public function mapRoleAssignments( array $data, array $roleData )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }
}
