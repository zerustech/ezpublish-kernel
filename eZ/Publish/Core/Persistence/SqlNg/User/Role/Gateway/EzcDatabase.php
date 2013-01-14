<?php
/**
 * File containing the ContentTypeGateway class
 *
 * @copyright Copyright (C) 1999-2012 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 */

namespace eZ\Publish\Core\Persistence\SqlNg\User\Role\Gateway;

use eZ\Publish\Core\Persistence\SqlNg\User\Role\Gateway;
use eZ\Publish\Core\Persistence\Legacy\EzcDbHandler;
use eZ\Publish\SPI\Persistence\User\Policy;
use eZ\Publish\SPI\Persistence\User\RoleUpdateStruct;
use eZ\Publish\SPI\Persistence\User\Role;

/**
 * Base class for content type gateways.
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
     * Internal type ID for user groups
     */
    const GROUP_TYPE_ID = 3;

    /**
     * Construct from database handler
     *
     * @param \eZ\Publish\Core\Persistence\Legacy\EzcDbHandler $handler
     *
     * @return void
     */
    public function __construct( EzcDbHandler $handler )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Create new role
     *
     * @param \eZ\Publish\SPI\Persistence\User\Role $role
     *
     * @return Role
     */
    public function createRole( Role $role )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Loads a specified role by id
     *
     * @param mixed $roleId
     *
     * @return array
     */
    public function loadRole( $roleId )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Loads a specified role by $identifier
     *
     * @param string $identifier
     *
     * @return array
     */
    public function loadRoleByIdentifier( $identifier )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Loads all roles
     *
     * @return array
     */
    public function loadRoles()
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Loads all roles associated with the given content objects
     *
     * @param array $contentIds
     *
     * @return array
     */
    public function loadRolesForContentObjects( $contentIds )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Loads role assignments for specified content ID
     *
     * @param mixed $groupId
     * @param boolean $inherited
     *
     * @return array
     */
    public function loadRoleAssignmentsByGroupId( $groupId, $inherited = false )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Returns the user policies associated with the user
     *
     * @param mixed $userId
     *
     * @return UserPolicy[]
     */
    public function loadPoliciesByUserId( $userId )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Update role
     *
     * @param \eZ\Publish\SPI\Persistence\User\RoleUpdateStruct $role
     */
    public function updateRole( RoleUpdateStruct $role )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Delete the specified role
     *
     * @param mixed $roleId
     */
    public function deleteRole( $roleId )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Adds a policy to a role
     *
     * @param mixed $roleId
     * @param \eZ\Publish\SPI\Persistence\User\Policy $policy
     *
     * @return void
     */
    public function addPolicy( $roleId, Policy $policy )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Adds limitations to an existing policy
     *
     * @param int $policyId
     * @param array $limitations
     *
     * @return void
     */
    public function addPolicyLimitations( $policyId, array $limitations )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Removes a policy from a role
     *
     * @param mixed $policyId
     *
     * @return void
     */
    public function removePolicy( $policyId )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Remove all limitations for a policy
     *
     * @param mixed $policyId
     *
     * @return void
     */
    public function removePolicyLimitations( $policyId )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }
}
