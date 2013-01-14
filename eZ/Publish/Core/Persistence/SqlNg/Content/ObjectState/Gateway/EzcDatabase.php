<?php
/**
 * File containing the ObjectState ezcDatabase Gateway class
 *
 * @copyright Copyright (C) 1999-2012 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 */

namespace eZ\Publish\Core\Persistence\SqlNg\Content\ObjectState\Gateway;

use eZ\Publish\Core\Persistence\SqlNg\Content\ObjectState\Gateway;
use eZ\Publish\Core\Persistence\SqlNg\Content\Language\MaskGenerator;
use eZ\Publish\Core\Persistence\Legacy\EzcDbHandler;
use eZ\Publish\SPI\Persistence\Content\ObjectState;
use eZ\Publish\SPI\Persistence\Content\ObjectState\Group;

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
     * Language mask generator
     *
     * @var \eZ\Publish\Core\Persistence\SqlNg\Content\Language\MaskGenerator
     */
    protected $maskGenerator;

    /**
     * Creates a new EzcDatabase ObjectState Gateway
     *
     * @param \eZ\Publish\Core\Persistence\Legacy\EzcDbHandler $dbHandler
     * @param \eZ\Publish\Core\Persistence\SqlNg\Content\Language\MaskGenerator $maskGenerator
     */
    public function __construct( EzcDbHandler $dbHandler, MaskGenerator $maskGenerator )
    {
        throw new \RuntimeException( "@TODO: Implement" );
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
        throw new \RuntimeException( "@TODO: Implement" );
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
        throw new \RuntimeException( "@TODO: Implement" );
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
    public function insertObjectState( ObjectState $objectState, $groupId )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Updates the stored object state with provided data
     *
     * @param \eZ\Publish\SPI\Persistence\Content\ObjectState $objectState
     */
    public function updateObjectState( ObjectState $objectState )
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
    public function insertObjectStateGroup( Group $objectStateGroup )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Updates the stored object state group with provided data
     *
     * @param \eZ\Publish\SPI\Persistence\Content\ObjectState\Group $objectStateGroup
     */
    public function updateObjectStateGroup( Group $objectStateGroup )
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
}
