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
        throw new \RuntimeException( "@TODO: Implement" );
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
        throw new \RuntimeException( "@TODO: Implement" );
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
        throw new \RuntimeException( "@TODO: Implement" );
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
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Inserts a new object state into database
     *
     * @param \eZ\Publish\SPI\Persistence\Content\ObjectState $objectState
     * @param int $groupId
     */
    public function insertObjectState( Persistence\Content\ObjectState $objectState, $groupId )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Updates the stored object state with provided data
     *
     * @param \eZ\Publish\SPI\Persistence\Content\ObjectState $objectState
     */
    public function updateObjectState( Persistence\Content\ObjectState $objectState )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Deletes object state identified by $stateId
     *
     * @param int $stateId
     */
    public function deleteObjectState( $stateId )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Update object state links to $newStateId
     *
     * @param int $oldStateId
     * @param int $newStateId
     */
    public function updateObjectStateLinks( $oldStateId, $newStateId )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Deletes object state links identified by $stateId
     *
     * @param int $stateId
     */
    public function deleteObjectStateLinks( $stateId )
    {
        throw new \RuntimeException( "@TODO: Implement" );
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
     * @param \eZ\Publish\SPI\Persistence\Content\ObjectState\Group $objectStateGroup
     */
    public function updateObjectStateGroup( Persistence\Content\ObjectState\InputStruct $objectStateGroup )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Deletes the object state group identified by $groupId
     *
     * @param mixed $groupId
     */
    public function deleteObjectStateGroup( $groupId )
    {
        throw new \RuntimeException( "@TODO: Implement" );
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
        throw new \RuntimeException( "@TODO: Implement" );
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
        throw new \RuntimeException( "@TODO: Implement" );
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
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Updates the object state priority to provided value
     *
     * @param mixed $stateId
     * @param int $priority
     */
    public function updateObjectStatePriority( $stateId, $priority )
    {
        throw new \RuntimeException( "@TODO: Implement" );
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
}
