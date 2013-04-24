<?php
/**
 * File containing the ObjectState ezcDatabase Gateway class
 *
 * @copyright Copyright (C) 1999-2012 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 */

namespace eZ\Publish\Core\Persistence\SqlNg\Content\ObjectState\Gateway;

use eZ\Publish\SPI\Persistence;
use eZ\Publish\Core\Persistence\SqlNg\Content\ObjectState\Gateway;
use eZ\Publish\Core\Persistence\Legacy\EzcDbHandler;
use eZ\Publish\Core\Persistence\SqlNg\Content\Language\Handler as LanguageHandler;

/**
 * ObjectState ezcDatabase Gateway
 */
class EzcDatabase extends Gateway
{
    /**
     * Database handler
     *
     * @var \eZ\Publish\Core\Persistence\Legacy\EzcDbHandler
     */
    protected $dbHandler;

    /**
     * Language handler
     *
     * @var \eZ\Publish\Core\Persistence\SqlNg\Content\Language\Handler
     */
    protected $languageHandler;

    /**
     * Creates a new EzcDatabase ObjectState Gateway
     *
     * @param \eZ\Publish\Core\Persistence\Legacy\EzcDbHandler $dbHandler
     * @param \eZ\Publish\Core\Persistence\SqlNg\Content\Language\Handler $languageHandler
     * @return void
     */
    public function __construct( EzcDbHandler $dbHandler, LanguageHandler $languageHandler )
    {
        $this->dbHandler = $dbHandler;
        $this->languageHandler = $languageHandler;
    }

    /**
     * Loads data for an object state
     *
     * @param mixed $stateId
     *
     * @return array
     */
    public function loadObjectStateData( $stateId )
    {
        $query = $this->createObjectStateFindQuery();
        $query->where(
            $query->expr->eq(
                $this->dbHandler->quoteColumn( 'state_id', 'ezcontent_state' ),
                $query->bindValue( $stateId, null, \PDO::PARAM_INT )
            )
        );

        $statement = $query->prepare();
        $statement->execute();

        return $statement->fetch( \PDO::FETCH_ASSOC );
    }

    /**
     * Loads data for an object state by identifier
     *
     * @param string $identifier
     * @param mixed $groupId
     *
     * @return array
     */
    public function loadObjectStateDataByIdentifier( $identifier, $groupId )
    {
        $query = $this->createObjectStateFindQuery();
        $query->where(
            $query->expr->lAnd(
                $query->expr->eq(
                    $this->dbHandler->quoteColumn( 'identifier', 'ezcontent_state' ),
                    $query->bindValue( $identifier, null, \PDO::PARAM_INT )
                ),
                $query->expr->eq(
                    $this->dbHandler->quoteColumn( 'state_group_id', 'ezcontent_state' ),
                    $query->bindValue( $groupId, null, \PDO::PARAM_INT )
                )
            )
        );

        $statement = $query->prepare();
        $statement->execute();

        return $statement->fetch( \PDO::FETCH_ASSOC );
    }

    /**
     * Loads data for all object states belonging to group with $groupId ID
     *
     * @param mixed $groupId
     *
     * @return array
     */
    public function loadObjectStateListData( $groupId )
    {
        $query = $this->createObjectStateFindQuery();
        $query->where(
            $query->expr->eq(
                $this->dbHandler->quoteColumn( 'state_group_id', 'ezcontent_state' ),
                $query->bindValue( $groupId, null, \PDO::PARAM_INT )
            )
        );

        $statement = $query->prepare();
        $statement->execute();

        return $statement->fetchAll( \PDO::FETCH_ASSOC );
    }

    /**
     * Loads data for an object state group
     *
     * @param mixed $groupId
     *
     * @return array
     */
    public function loadObjectStateGroupData( $groupId )
    {
        $query = $this->createObjectStateGroupFindQuery();
        $query->where(
            $query->expr->eq(
                $this->dbHandler->quoteColumn( 'state_group_id', 'ezcontent_state_group' ),
                $query->bindValue( $groupId, null, \PDO::PARAM_INT )
            )
        );

        $statement = $query->prepare();
        $statement->execute();

        return $statement->fetch( \PDO::FETCH_ASSOC );
    }

    /**
     * Loads data for an object state group by identifier
     *
     * @param string $identifier
     *
     * @return array
     */
    public function loadObjectStateGroupDataByIdentifier( $identifier )
    {
        $query = $this->createObjectStateGroupFindQuery();
        $query->where(
            $query->expr->eq(
                $this->dbHandler->quoteColumn( 'identifier', 'ezcontent_state_group' ),
                $query->bindValue( $identifier, null, \PDO::PARAM_STR )
            )
        );

        $statement = $query->prepare();
        $statement->execute();

        return $statement->fetch( \PDO::FETCH_ASSOC );
    }

    /**
     * Loads data for all object state groups, filtered by $offset and $limit
     *
     * @param int $offset
     * @param int $limit
     *
     * @return array
     */
    public function loadObjectStateGroupListData( $offset, $limit )
    {
        $query = $this->createObjectStateGroupFindQuery();
        $query->limit( $limit > 0 ? $limit : PHP_INT_MAX, $offset );

        $statement = $query->prepare();
        $statement->execute();

        return $statement->fetchAll( \PDO::FETCH_ASSOC );
    }

    /**
     * Inserts a new object state into database
     *
     * @param mixed $groupId
     * @param \eZ\Publish\SPI\Persistence\Content\ObjectState\InputStruct $objectState
     * @return void
     */
    public function insertObjectState( $groupId, Persistence\Content\ObjectState\InputStruct $objectState )
    {
        $query = $this->dbHandler->createInsertQuery();
        $query->insertInto(
            $this->dbHandler->quoteTable( 'ezcontent_state' )
        )->set(
            $this->dbHandler->quoteColumn( 'state_group_id' ),
            $this->dbHandler->getAutoIncrementValue( 'ezcontent_state', 'state_id' )
        )->set(
            $this->dbHandler->quoteColumn( 'state_group_id' ),
            $query->bindValue( $groupId, null, \PDO::PARAM_INT )
        )->set(
            $this->dbHandler->quoteColumn( 'default_language_id' ),
            $query->bindValue(
                $this->languageHandler->loadByLanguageCode(
                    $objectState->defaultLanguage
                )->id,
                null,
                \PDO::PARAM_INT
            )
        )->set(
            $this->dbHandler->quoteColumn( 'identifier' ),
            $query->bindValue( $objectState->identifier )
        )->set(
            $this->dbHandler->quoteColumn( 'name' ),
            $query->bindValue( json_encode( $objectState->name ) )
        )->set(
            $this->dbHandler->quoteColumn( 'description' ),
            $query->bindValue( json_encode( $objectState->description ) )
        );

        $query->prepare()->execute();

        return (int)$this->dbHandler->lastInsertId(
            $this->dbHandler->getSequenceName( 'ezcontent_state', 'state_id' )
        );
    }

    /**
     * Updates the stored object state with provided data
     *
     * @param int $stateId
     * @param \eZ\Publish\SPI\Persistence\Content\ObjectState\InputStruct $objectState
     */
    public function updateObjectState( $stateId, Persistence\Content\ObjectState\InputStruct $objectState )
    {
        $query = $this->dbHandler->createUpdateQuery();
        $query->update(
            $this->dbHandler->quoteTable( 'ezcontent_state' )
        )->set(
            $this->dbHandler->quoteColumn( 'default_language_id' ),
            $query->bindValue(
                $this->languageHandler->loadByLanguageCode(
                    $objectState->defaultLanguage
                )->id,
                null,
                \PDO::PARAM_INT
            )
        )->set(
            $this->dbHandler->quoteColumn( 'identifier' ),
            $query->bindValue( $objectState->identifier )
        )->set(
            $this->dbHandler->quoteColumn( 'name' ),
            $query->bindValue( json_encode( $objectState->name ) )
        )->set(
            $this->dbHandler->quoteColumn( 'description' ),
            $query->bindValue( json_encode( $objectState->description ) )
        )->where(
            $query->expr->eq(
                $this->dbHandler->quoteColumn( 'state_id' ),
                $query->bindValue( $stateId, null, \PDO::PARAM_INT )
            )
        );

        $statement = $query->prepare();
        $statement->execute();

        if ( $statement->rowCount() < 1 )
        {
            throw new NotFound( 'ObjectState', $stateId );
        }
    }

    /**
     * Deletes object state identified by $stateId
     *
     * @param int $stateId
     */
    public function deleteObjectState( $stateId )
    {
        $query = $this->dbHandler->createDeleteQuery();
        $query->deleteFrom(
            $this->dbHandler->quoteTable( 'ezcontent_state' )
        )->where(
            $query->expr->eq(
                $this->dbHandler->quoteColumn( 'state_id' ),
                $query->bindValue( $stateId, null, \PDO::PARAM_INT )
            )
        );

        $query->prepare()->execute();
    }

    /**
     * Inserts a new object state group into database
     *
     * @param \eZ\Publish\SPI\Persistence\Content\ObjectState\Group $objectStateGroup
     */
    public function insertObjectStateGroup( Persistence\Content\ObjectState\InputStruct $objectStateGroup )
    {
        $query = $this->dbHandler->createInsertQuery();
        $query->insertInto(
            $this->dbHandler->quoteTable( 'ezcontent_state_group' )
        )->set(
            $this->dbHandler->quoteColumn( 'state_group_id' ),
            $this->dbHandler->getAutoIncrementValue( 'ezcontent_state_group', 'state_group_id' )
        )->set(
            $this->dbHandler->quoteColumn( 'default_language_id' ),
            $query->bindValue(
                $this->languageHandler->loadByLanguageCode(
                    $objectStateGroup->defaultLanguage
                )->id,
                null,
                \PDO::PARAM_INT
            )
        )->set(
            $this->dbHandler->quoteColumn( 'identifier' ),
            $query->bindValue( $objectStateGroup->identifier )
        )->set(
            $this->dbHandler->quoteColumn( 'name' ),
            $query->bindValue( json_encode( $objectStateGroup->name ) )
        )->set(
            $this->dbHandler->quoteColumn( 'description' ),
            $query->bindValue( json_encode( $objectStateGroup->description ) )
        );

        $query->prepare()->execute();

        return (int)$this->dbHandler->lastInsertId(
            $this->dbHandler->getSequenceName( 'ezcontent_state_group', 'state_group_id' )
        );
    }

    /**
     * Updates the stored object state group with provided data
     *
     * @param int $groupId
     * @param \eZ\Publish\SPI\Persistence\Content\ObjectState\Group $objectStateGroup
     */
    public function updateObjectStateGroup( $groupId, Persistence\Content\ObjectState\InputStruct $objectStateGroup )
    {
        $query = $this->dbHandler->createUpdateQuery();
        $query->update(
            $this->dbHandler->quoteTable( 'ezcontent_state_group' )
        )->set(
            $this->dbHandler->quoteColumn( 'default_language_id' ),
            $query->bindValue(
                $this->languageHandler->loadByLanguageCode(
                    $objectStateGroup->defaultLanguage
                )->id,
                null,
                \PDO::PARAM_INT
            )
        )->set(
            $this->dbHandler->quoteColumn( 'identifier' ),
            $query->bindValue( $objectStateGroup->identifier )
        )->set(
            $this->dbHandler->quoteColumn( 'name' ),
            $query->bindValue( json_encode( $objectStateGroup->name ) )
        )->set(
            $this->dbHandler->quoteColumn( 'description' ),
            $query->bindValue( json_encode( $objectStateGroup->description ) )
        )->where(
            $query->expr->eq(
                $this->dbHandler->quoteColumn( 'state_group_id' ),
                $query->bindValue( $groupId, null, \PDO::PARAM_INT )
            )
        );

        $statement = $query->prepare();
        $statement->execute();

        if ( $statement->rowCount() < 1 )
        {
            throw new NotFound( 'ObjectStateGroup', $groupId );
        }
    }

    /**
     * Deletes the object state group identified by $groupId
     *
     * @param mixed $groupId
     */
    public function deleteObjectStateGroup( $groupId )
    {
        $query = $this->dbHandler->createDeleteQuery();
        $query->deleteFrom(
            $this->dbHandler->quoteTable( 'ezcontent_state_group' )
        )->where(
            $query->expr->eq(
                $this->dbHandler->quoteColumn( 'state_group_id' ),
                $query->bindValue( $groupId, null, \PDO::PARAM_INT )
            )
        );

        $query->prepare()->execute();
    }

    /**
     * Sets the object state $stateId to content with $contentId ID
     *
     * @param mixed $contentId
     * @param mixed $groupId
     * @param mixed $stateId
     */
    public function setContentState( $contentId, $groupId, $stateId )
    {
        // First find out if $contentId is related to existing states in $groupId
        $query = $this->dbHandler->createSelectQuery();
        $query->select(
            $this->dbHandler->aliasedColumn( $query, 'state_id', 'ezcontent_state_link' )
        )->from(
            $this->dbHandler->quoteTable( 'ezcontent_state_link' )
        )->where(
            $query->expr->lAnd(
                $query->expr->eq(
                    $this->dbHandler->quoteColumn( 'state_group_id', 'ezcontent_state_link' ),
                    $query->bindValue( $groupId, null, \PDO::PARAM_INT )
                ),
                $query->expr->eq(
                    $this->dbHandler->quoteColumn( 'content_id', 'ezcontent_state_link' ),
                    $query->bindValue( $contentId, null, \PDO::PARAM_INT )
                )
            )
        );

        $statement = $query->prepare();
        $statement->execute();

        $rows = $statement->fetchAll( \PDO::FETCH_ASSOC );

        if ( !empty( $rows ) )
        {
            // We already have a state assigned to $contentId, update to new one
            $query = $this->dbHandler->createUpdateQuery();
            $query->update(
                $this->dbHandler->quoteTable( 'ezcontent_state_link' )
            )->set(
                $this->dbHandler->quoteColumn( 'state_id' ),
                $query->bindValue( $stateId, null, \PDO::PARAM_INT )
            )->where(
                $query->expr->lAnd(
                    $query->expr->eq(
                        $this->dbHandler->quoteColumn( 'state_group_id', 'ezcontent_state_link' ),
                        $query->bindValue( $groupId, null, \PDO::PARAM_INT )
                    ),
                    $query->expr->eq(
                        $this->dbHandler->quoteColumn( 'content_id', 'ezcontent_state_link' ),
                        $query->bindValue( $contentId, null, \PDO::PARAM_INT )
                    )
                )
            );

            $query->prepare()->execute();
        }
        else
        {
            // No state assigned to $contentId from specified group, create assignment
            $query = $this->dbHandler->createInsertQuery();
            $query->insertInto(
                $this->dbHandler->quoteTable( 'ezcontent_state_link' )
            )->set(
                $this->dbHandler->quoteColumn( 'content_id' ),
                $query->bindValue( $contentId, null, \PDO::PARAM_INT )
            )->set(
                $this->dbHandler->quoteColumn( 'state_group_id' ),
                $query->bindValue( $groupId, null, \PDO::PARAM_INT )
            )->set(
                $this->dbHandler->quoteColumn( 'state_id' ),
                $query->bindValue( $stateId, null, \PDO::PARAM_INT )
            );

            $query->prepare()->execute();
        }
    }

    /**
     * Loads object state data for $contentId content from $stateGroupId state group
     *
     * @param int $contentId
     * @param int $stateGroupId
     *
     * @return array
     */
    public function loadObjectStateDataForContent( $contentId, $stateGroupId )
    {
        $query = $this->createObjectStateFindQuery();
        $query->innerJoin(
            $this->dbHandler->quoteTable( 'ezcontent_state_link' ),
            $query->expr->eq(
                $this->dbHandler->quoteColumn( 'state_id', 'ezcontent_state' ),
                $this->dbHandler->quoteColumn( 'state_id', 'ezcontent_state_link' )
            )
        )->where(
            $query->expr->lAnd(
                $query->expr->eq(
                    $this->dbHandler->quoteColumn( 'state_group_id', 'ezcontent_state_link' ),
                    $query->bindValue( $stateGroupId, null, \PDO::PARAM_INT )
                ),
                $query->expr->eq(
                    $this->dbHandler->quoteColumn( 'content_id', 'ezcontent_state_link' ),
                    $query->bindValue( $contentId, null, \PDO::PARAM_INT )
                )
            )
        );

        $statement = $query->prepare();
        $statement->execute();

        return $statement->fetch( \PDO::FETCH_ASSOC );
    }

    /**
     * Returns the number of objects which are in this state
     *
     * @param mixed $stateId
     *
     * @return int
     */
    public function getContentCount( $stateId )
    {
        $query = $this->dbHandler->createSelectQuery();
        $query->select(
            $query->alias( $query->expr->count( '*' ), 'count' )
        )->from(
            $this->dbHandler->quoteTable( 'ezcontent_state_link' )
        )->where(
            $query->expr->eq(
                $this->dbHandler->quoteColumn( 'state_id' ),
                $query->bindValue( $stateId, null, \PDO::PARAM_INT )
            )
        );

        $statement = $query->prepare();
        $statement->execute();

        $count = $statement->fetchColumn();

        return $count !== null ? (int)$count : 0;
    }

    /**
     * Updates the object state priority to provided value
     *
     * @param mixed $stateId
     * @param int $priority
     */
    public function updateObjectStatePriority( $stateId, $priority )
    {
        $query = $this->dbHandler->createUpdateQuery();
        $query->update(
            $this->dbHandler->quoteTable( 'ezcontent_state' )
        )->set(
            $this->dbHandler->quoteColumn( 'priority' ),
            $query->bindValue( $priority, null, \PDO::PARAM_INT )
        )->where(
            $query->expr->eq(
                $this->dbHandler->quoteColumn( 'state_id' ),
                $query->bindValue( $stateId, null, \PDO::PARAM_INT )
            )
        );

        $query->prepare()->execute();
    }

    /**
     * Creates a generalized query for fetching object state group(s)
     *
     * @return \ezcQuerySelect
     */
    protected function createObjectStateGroupFindQuery()
    {
        $query = $this->dbHandler->createSelectQuery();
        $query->select(
            // Object state group
            $this->dbHandler->aliasedColumn( $query, 'state_group_id', 'ezcontent_state_group' ),
            $this->dbHandler->aliasedColumn( $query, 'language_code', 'ezcontent_language' ),
            $this->dbHandler->aliasedColumn( $query, 'identifier', 'ezcontent_state_group' ),
            $this->dbHandler->aliasedColumn( $query, 'name', 'ezcontent_state_group' ),
            $this->dbHandler->aliasedColumn( $query, 'description', 'ezcontent_state_group' )
        )->from(
            $this->dbHandler->quoteTable( 'ezcontent_state_group' )
        )->innerJoin(
            $this->dbHandler->quoteTable( 'ezcontent_language' ),
            $query->expr->eq(
                $this->dbHandler->quoteColumn( 'language_id', 'ezcontent_language' ),
                $this->dbHandler->quoteColumn( 'default_language_id', 'ezcontent_state_group' )
            )
        );

        return $query;
    }

    /**
     * Creates a generalized query for fetching object state(s)
     *
     * @return \ezcQuerySelect
     */
    protected function createObjectStateFindQuery()
    {
        $query = $this->dbHandler->createSelectQuery();
        $query->select(
            // Object state
            $this->dbHandler->aliasedColumn( $query, 'state_id', 'ezcontent_state' ),
            $this->dbHandler->aliasedColumn( $query, 'state_group_id', 'ezcontent_state' ),
            $this->dbHandler->aliasedColumn( $query, 'language_code', 'ezcontent_language' ),
            $this->dbHandler->aliasedColumn( $query, 'identifier', 'ezcontent_state' ),
            $this->dbHandler->aliasedColumn( $query, 'priority', 'ezcontent_state' ),
            $this->dbHandler->aliasedColumn( $query, 'name', 'ezcontent_state' ),
            $this->dbHandler->aliasedColumn( $query, 'description', 'ezcontent_state' )
        )->from(
            $this->dbHandler->quoteTable( 'ezcontent_state' )
        )->innerJoin(
            $this->dbHandler->quoteTable( 'ezcontent_language' ),
            $query->expr->eq(
                $this->dbHandler->quoteColumn( 'language_id', 'ezcontent_language' ),
                $this->dbHandler->quoteColumn( 'default_language_id', 'ezcontent_state' )
            )
        );

        return $query;
    }
}
