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
use eZ\Publish\Core\Base\Exceptions\NotFoundException as NotFound;

/**
 * Base class for content type gateways.
 */
class EzcDatabase extends Gateway
{
    /**
     * Database dbHandler
     *
     * @var EzcDbHandler
     */
    protected $dbHandler;

    /**
     * Internal type ID for user groups
     */
    const GROUP_TYPE_ID = 3;

    /**
     * Construct from database dbHandler
     *
     * @param \eZ\Publish\Core\Persistence\Legacy\EzcDbHandler $dbHandler
     *
     * @return void
     */
    public function __construct( EzcDbHandler $dbHandler )
    {
        $this->dbHandler = $dbHandler;
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
        $query = $this->dbHandler->createInsertQuery();
        $query
            ->insertInto( $this->dbHandler->quoteTable( 'ezrole' ) )
            ->set(
                $this->dbHandler->quoteColumn( 'role_id' ),
                $this->dbHandler->getAutoIncrementValue( 'ezrole', 'role_id' )
            )->set(
                $this->dbHandler->quoteColumn( 'identifier' ),
                $query->bindValue( $role->identifier )
            )->set(
                $this->dbHandler->quoteColumn( 'name' ),
                $query->bindValue( json_encode( $role->name ) )
            )->set(
                $this->dbHandler->quoteColumn( 'description' ),
                $query->bindValue( json_encode( $role->description ) )
            );
        $query->prepare()->execute();

        $role->id = $this->dbHandler->lastInsertId(
            $this->dbHandler->getSequenceName( 'ezrole', 'role_id' )
        );
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
        $query = $this->dbHandler->createSelectQuery();
        $query->select(
            $this->dbHandler->aliasedColumn( $query, 'role_id', 'ezrole' ),
            $this->dbHandler->aliasedColumn( $query, 'identifier', 'ezrole' ),
            $this->dbHandler->aliasedColumn( $query, 'name', 'ezrole' ),
            $this->dbHandler->aliasedColumn( $query, 'description', 'ezrole' ),
            $this->dbHandler->aliasedColumn( $query, 'content_id', 'ezrole_content_rel' ),
            $this->dbHandler->aliasedColumn( $query, 'policy_id', 'ezrole_policy' ),
            $this->dbHandler->aliasedColumn( $query, 'limitations', 'ezrole_policy' )
        )->from(
            $this->dbHandler->quoteTable( 'ezrole' )
        )->leftJoin(
            $this->dbHandler->quoteTable( 'ezrole_content_rel' ),
            $query->expr->eq(
                $this->dbHandler->quoteColumn( 'role_id', 'ezrole_content_rel' ),
                $this->dbHandler->quoteColumn( 'role_id', 'ezrole' )
            )
        )->leftJoin(
            $this->dbHandler->quoteTable( 'ezrole_policy' ),
            $query->expr->eq(
                $this->dbHandler->quoteColumn( 'role_id', 'ezrole_policy' ),
                $this->dbHandler->quoteColumn( 'role_id', 'ezrole' )
            )
        )->where(
            $query->expr->eq(
                $this->dbHandler->quoteColumn( 'role_id', 'ezrole' ),
                $query->bindValue( $roleId, null, \PDO::PARAM_INT )
            )
        );

        $statement = $query->prepare();
        $statement->execute();

        return $statement->fetchAll( \PDO::FETCH_ASSOC );
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
        $query = $this->dbHandler->createSelectQuery();
        $query->select(
            $this->dbHandler->aliasedColumn( $query, 'role_id', 'ezrole' ),
            $this->dbHandler->aliasedColumn( $query, 'identifier', 'ezrole' ),
            $this->dbHandler->aliasedColumn( $query, 'name', 'ezrole' ),
            $this->dbHandler->aliasedColumn( $query, 'description', 'ezrole' ),
            $this->dbHandler->aliasedColumn( $query, 'content_id', 'ezrole_content_rel' ),
            $this->dbHandler->aliasedColumn( $query, 'policy_id', 'ezrole_policy' ),
            $this->dbHandler->aliasedColumn( $query, 'limitations', 'ezrole_policy' )
        )->from(
            $this->dbHandler->quoteTable( 'ezrole' )
        )->leftJoin(
            $this->dbHandler->quoteTable( 'ezrole_content_rel' ),
            $query->expr->eq(
                $this->dbHandler->quoteColumn( 'role_id', 'ezrole_content_rel' ),
                $this->dbHandler->quoteColumn( 'role_id', 'ezrole' )
            )
        )->leftJoin(
            $this->dbHandler->quoteTable( 'ezrole_policy' ),
            $query->expr->eq(
                $this->dbHandler->quoteColumn( 'role_id', 'ezrole_policy' ),
                $this->dbHandler->quoteColumn( 'role_id', 'ezrole' )
            )
        );

        $statement = $query->prepare();
        $statement->execute();

        return $statement->fetchAll( \PDO::FETCH_ASSOC );
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
        $query = $this->dbHandler->createSelectQuery();
        $query->select(
            $this->dbHandler->aliasedColumn( $query, 'content_id', 'ezrole_content_rel' ),
            $this->dbHandler->aliasedColumn( $query, 'role_id', 'ezrole' ),
            $this->dbHandler->aliasedColumn( $query, 'identifier', 'ezrole' ),
            $this->dbHandler->aliasedColumn( $query, 'name', 'ezrole' ),
            $this->dbHandler->aliasedColumn( $query, 'description', 'ezrole' ),
            $this->dbHandler->aliasedColumn( $query, 'policy_id', 'ezrole_policy' ),
            $this->dbHandler->aliasedColumn( $query, 'limitations', 'ezrole_policy' )
        )->from(
            $query->alias(
                $this->dbHandler->quoteTable( 'ezrole_content_rel' ),
                $this->dbHandler->quoteIdentifier( 'ezrole_content_rel_search' )
            )
        )->leftJoin(
            $this->dbHandler->quoteTable( 'ezrole' ),
            $query->expr->eq(
                $this->dbHandler->quoteColumn( 'role_id', 'ezrole' ),
                $this->dbHandler->quoteColumn( 'role_id', 'ezrole_content_rel_search' )
            )
        )->leftJoin(
            $this->dbHandler->quoteTable( 'ezrole_content_rel' ),
            $query->expr->eq(
                $this->dbHandler->quoteColumn( 'role_id', 'ezrole_content_rel' ),
                $this->dbHandler->quoteColumn( 'role_id', 'ezrole' )
            )
        )->leftJoin(
            $this->dbHandler->quoteTable( 'ezrole_policy' ),
            $query->expr->eq(
                $this->dbHandler->quoteColumn( 'role_id', 'ezrole_policy' ),
                $this->dbHandler->quoteColumn( 'role_id', 'ezrole' )
            )
        )->where(
            $query->expr->in(
                $this->dbHandler->quoteColumn( 'content_id', 'ezrole_content_rel_search' ),
                $contentIds
            )
        );

        $statement = $query->prepare();
        $statement->execute();

        return $statement->fetchAll( \PDO::FETCH_ASSOC );
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
        $query = $this->dbHandler->createSelectQuery();
        $query->select(
            $this->dbHandler->quoteColumn( 'content_id' ),
            $this->dbHandler->quoteColumn( 'limit_identifier' ),
            $this->dbHandler->quoteColumn( 'limit_value' ),
            $this->dbHandler->quoteColumn( 'role_id' )
        )->from(
            $this->dbHandler->quoteTable( 'ezrole_content_rel' )
        );

        if ( $inherited )
        {
            $groupIds = $this->fetchUserGroups( $groupId );
            $groupIds[] = $groupId;
            $query->where(
                $query->expr->in(
                    $this->dbHandler->quoteColumn( 'content_id' ),
                    $groupIds
                )
            );
        }
        else
        {
            $query->where(
                $query->expr->eq(
                    $this->dbHandler->quoteColumn( 'content_id' ),
                    $query->bindValue( $groupId, null, \PDO::PARAM_INT )
                )
            );
        }

        $statement = $query->prepare();
        $statement->execute();

        return $statement->fetchAll( \PDO::FETCH_ASSOC );
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
     * Fetch all group IDs the user belongs to
     *
     * @param int $userId
     *
     * @return array
     */
    protected function fetchUserGroups( $userId )
    {
        $query = $this->dbHandler->createSelectQuery();
        $query->select(
            $this->dbHandler->quoteColumn( 'path_string', 'ezcontent_location' )
        )->from(
            $this->dbHandler->quoteTable( 'ezcontent_location' )
        )->where(
            $query->expr->eq(
                $this->dbHandler->quoteColumn( 'content_id', 'ezcontent_location' ),
                $query->bindValue( $userId )
            )
        );

        $statement = $query->prepare();
        $statement->execute();

        $paths = $statement->fetchAll( \PDO::FETCH_COLUMN );
        $locationIds = array_unique(
            array_reduce(
                array_map(
                    function ( $pathString )
                    {
                        return array_filter( explode( '/', $pathString ) );
                    },
                    $paths
                ),
                'array_merge_recursive',
                array()
            )
        );

        if ( empty( $locationIds ) )
            return array();

        // Limit nodes to groups only
        $query = $this->dbHandler->createSelectQuery();
        $query->select(
            $this->dbHandler->quoteColumn( 'content_id', 'ezcontent' )
        )->from(
            $this->dbHandler->quoteTable( 'ezcontent_location' )
        )->rightJoin(
            $this->dbHandler->quoteTable( 'ezcontent' ),
            $query->expr->eq(
                $this->dbHandler->quoteColumn( 'content_id', 'ezcontent' ),
                $this->dbHandler->quoteColumn( 'content_id', 'ezcontent_location' )
            )
        )->rightJoin(
            $this->dbHandler->quoteTable( 'ezcontent_type' ),
            $query->expr->lAnd(
                $query->expr->eq(
                    $this->dbHandler->quoteColumn( 'type_id', 'ezcontent_type' ),
                    $this->dbHandler->quoteColumn( 'type_id', 'ezcontent' )
                ),
                $query->expr->eq(
                    $this->dbHandler->quoteColumn( 'identifier', 'ezcontent_type' ),
                    $query->bindValue( 'user_group', null, \PDO::PARAM_STR )
                )
            )
        )->where(
            $query->expr->in(
                $this->dbHandler->quoteColumn( 'location_id', 'ezcontent_location' ),
                $locationIds
            )
        );

        $statement = $query->prepare();
        $statement->execute();

        return $statement->fetchAll( \PDO::FETCH_COLUMN );
    }

    /**
     * Update role
     *
     * @param \eZ\Publish\SPI\Persistence\User\RoleUpdateStruct $role
     */
    public function updateRole( RoleUpdateStruct $role )
    {
        $query = $this->dbHandler->createUpdateQuery();
        $query
            ->update( $this->dbHandler->quoteTable( 'ezrole' ) )
            ->set(
                $this->dbHandler->quoteColumn( 'identifier' ),
                $query->bindValue( $role->identifier )
            )->set(
                $this->dbHandler->quoteColumn( 'name' ),
                $query->bindValue( json_encode( $role->name ) )
            )->set(
                $this->dbHandler->quoteColumn( 'description' ),
                $query->bindValue( json_encode( $role->description ) )
            )->where(
                $query->expr->eq(
                    $this->dbHandler->quoteColumn( 'role_id' ),
                    $query->bindValue( $role->id, null, \PDO::PARAM_INT )
                )
            );
        $query->prepare()->execute();
    }

    /**
     * Delete the specified role
     *
     * @param mixed $roleId
     */
    public function deleteRole( $roleId )
    {
        $query = $this->dbHandler->createDeleteQuery();
        $query
            ->deleteFrom( $this->dbHandler->quoteTable( 'ezrole' ) )
            ->where(
                $query->expr->eq(
                    $this->dbHandler->quoteColumn( 'role_id' ),
                    $query->bindValue( $roleId, null, \PDO::PARAM_INT )
                )
            );
        $statement = $query->prepare();
        $statement->execute();

        if ( $statement->rowCount() < 1 )
        {
            throw new NotFound( 'role', $roleId );
        }
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
        $query = $this->dbHandler->createInsertQuery();
        $query
            ->insertInto( $this->dbHandler->quoteTable( 'ezrole_policy' ) )
            ->set(
                $this->dbHandler->quoteColumn( 'policy_id' ),
                $this->dbHandler->getAutoIncrementValue( 'ezrole_policy', 'policy_id' )
            )->set(
                $this->dbHandler->quoteColumn( 'role_id' ),
                $query->bindValue( $roleId, null, \PDO::PARAM_INT )
            )->set(
                $this->dbHandler->quoteColumn( 'limitations' ),
                $query->bindValue(
                    json_encode( array(
                        'function' => $policy->function,
                        'module' => $policy->module,
                        'limitations' => $policy->limitations ?: '*',
                    ) ),
                    null,
                    \PDO::PARAM_STR
                )
            );
        $query->prepare()->execute();

        $policy->roleId = $roleId;
        $policy->id = $this->dbHandler->lastInsertId(
            $this->dbHandler->getSequenceName( 'ezrole_policy', 'policy_id' )
        );

        return $policy;
    }

    /**
     * Updates a policy definition
     *
     * @param Policy $policy
     *
     * @return void
     */
    public function updatePolicy( Policy $policy )
    {
        $query = $this->dbHandler->createUpdateQuery();
        $query
            ->update( $this->dbHandler->quoteTable( 'ezrole_policy' ) )
            ->set(
                $this->dbHandler->quoteColumn( 'limitations' ),
                $query->bindValue(
                    json_encode( array(
                        'function' => $policy->function,
                        'module' => $policy->module,
                        'limitations' => $policy->limitations ?: '*',
                    ) ),
                    null,
                    \PDO::PARAM_STR
                )
            )->where(
                $query->expr->eq(
                    $this->dbHandler->quoteColumn( 'policy_id' ),
                    $query->bindValue( $policy->id, null, \PDO::PARAM_INT )
                )
            );
        $query->prepare()->execute();
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
        $query = $this->dbHandler->createDeleteQuery();
        $query
            ->deleteFrom( $this->dbHandler->quoteTable( 'ezrole_policy' ) )
            ->where(
                $query->expr->eq(
                    $this->dbHandler->quoteColumn( 'policy_id' ),
                    $query->bindValue( $policyId, null, \PDO::PARAM_INT )
                )
            );
        $statement = $query->prepare();
        $statement->execute();

        if ( $statement->rowCount() < 1 )
        {
            throw new NotFound( 'policy', $policyId );
        }
    }
}
