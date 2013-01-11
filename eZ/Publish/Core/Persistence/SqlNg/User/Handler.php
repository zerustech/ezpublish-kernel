<?php
/**
 * File containing the UserHandler interface
 *
 * @copyright Copyright (C) 1999-2012 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 */

namespace eZ\Publish\Core\Persistence\SqlNg\User;

use eZ\Publish\SPI\Persistence\User;
use eZ\Publish\SPI\Persistence\User\Handler as BaseUserHandler;
use eZ\Publish\SPI\Persistence\User\Role;
use eZ\Publish\SPI\Persistence\User\RoleUpdateStruct;
use eZ\Publish\SPI\Persistence\User\Policy;
use eZ\Publish\Core\Persistence\SqlNg\User\Role\Gateway as RoleGateway;
use eZ\Publish\Core\Base\Exceptions\NotFoundException as NotFound;

/**
 * Storage Engine handler for user module
 */
class Handler implements BaseUserHandler
{
    /**
     * Construct from userGateway
     */
    public function __construct()
    {
    }

    /**
     * Create a user
     *
     * The User struct used to create the user will contain an ID which is used
     * to reference the user.
     *
     * @param \eZ\Publish\SPI\Persistence\User $user
     *
     * @return \eZ\Publish\SPI\Persistence\User
     */
    public function create( User $user )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Loads user with user ID.
     *
     * @param mixed $userId
     *
     * @return \eZ\Publish\SPI\Persistence\User
     */
    public function load( $userId )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Loads user with user login / email.
     *
     * @param string $login
     * @param boolean $alsoMatchEmail Also match user email, caller must verify that $login is a valid email address.
     *
     * @return \eZ\Publish\SPI\Persistence\User[]
     */
    public function loadByLogin( $login, $alsoMatchEmail = false )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Update the user information specified by the user struct
     *
     * @param \eZ\Publish\SPI\Persistence\User $user
     */
    public function update( User $user )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Delete user with the given ID.
     *
     * @param mixed $userId
     */
    public function delete( $userId )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Create new role
     *
     * @param \eZ\Publish\SPI\Persistence\User\Role $role
     *
     * @return \eZ\Publish\SPI\Persistence\User\Role
     */
    public function createRole( Role $role )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Loads a specified role by $roleId
     *
     * @param mixed $roleId
     *
     * @throws \eZ\Publish\API\Repository\Exceptions\NotFoundException If role is not found
     *
     * @return \eZ\Publish\SPI\Persistence\User\Role
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
     * @throws \eZ\Publish\API\Repository\Exceptions\NotFoundException If role is not found
     *
     * @return \eZ\Publish\SPI\Persistence\User\Role
     */
    public function loadRoleByIdentifier( $identifier )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Loads all roles
     *
     * @return \eZ\Publish\SPI\Persistence\User\Role[]
     */
    public function loadRoles()
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Loads roles assigned to a user/group (not including inherited roles)
     *
     * @param mixed $groupId
     *
     * @return \eZ\Publish\SPI\Persistence\User\Role[]
     */
    public function loadRolesByGroupId( $groupId )
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
     * @return \eZ\Publish\SPI\Persistence\User\Policy
     */
    public function addPolicy( $roleId, Policy $policy )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Update a policy
     *
     * Replaces limitations values with new values.
     *
     * @param \eZ\Publish\SPI\Persistence\User\Policy $policy
     */
    public function updatePolicy( Policy $policy )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Removes a policy from a role
     *
     * @param mixed $roleId
     * @param mixed $policyId
     *
     * @return void
     */
    public function removePolicy( $roleId, $policyId )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Returns the user policies associated with the user (including inherited policies from user groups)
     *
     * @param mixed $userId
     *
     * @return \eZ\Publish\SPI\Persistence\User\Policy[]
     */
    public function loadPoliciesByUserId( $userId )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Assigns role to a user or user group with given limitations
     *
     * The limitation array looks like:
     * <code>
     *  array(
     *      'Subtree' => array(
     *          '/1/2/',
     *          '/1/4/',
     *      ),
     *      'Foo' => array( 'Bar' ),
     *      …
     *  )
     * </code>
     *
     * Where the keys are the limitation identifiers, and the respective values
     * are an array of limitation values. The limitation parameter is optional.
     *
     * @param mixed $contentId The groupId or userId to assign the role to.
     * @param mixed $roleId
     * @param array $limitation
     */
    public function assignRole( $contentId, $roleId, array $limitation = null )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Un-assign a role
     *
     * @param mixed $contentId The user or user group Id to un-assign the role from.
     * @param mixed $roleId
     */
    public function unAssignRole( $contentId, $roleId )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Loads roles assignments Role
     *
     * Role Assignments with same roleId and limitationIdentifier will be merged together into one.
     *
     * @param mixed $roleId
     *
     * @return \eZ\Publish\SPI\Persistence\User\RoleAssignment[]
     */
    public function loadRoleAssignmentsByRoleId( $roleId )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Loads roles assignments to a user/group
     *
     * Role Assignments with same roleId and limitationIdentifier will be merged together into one.
     *
     * @param mixed $groupId In legacy storage engine this is the content object id roles are assigned to in ezuser_role.
     *                      By the nature of legacy this can currently also be used to get by $userId.
     * @param boolean $inherit If true also return inherited role assignments from user groups.
     *
     * @return \eZ\Publish\SPI\Persistence\User\RoleAssignment[]
     */
    public function loadRoleAssignmentsByGroupId( $groupId, $inherit = false )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }
}
