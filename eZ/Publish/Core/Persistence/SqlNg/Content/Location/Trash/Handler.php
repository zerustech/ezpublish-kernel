<?php
/**
 * File containing the Trash Handler class
 *
 * @copyright Copyright (C) 1999-2012 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 */

namespace eZ\Publish\Core\Persistence\SqlNg\Content\Location\Trash;

use eZ\Publish\SPI\Persistence\Content\Location\Trashed;
use eZ\Publish\SPI\Persistence\Content\Location\Trash\Handler as BaseTrashHandler;
use eZ\Publish\Core\Persistence\SqlNg\Content\Handler as ContentHandler;
use eZ\Publish\API\Repository\Values\Content\Query\Criterion;
use eZ\Publish\Core\Persistence\SqlNg\Content\Location\Handler as LocationHandler;
use eZ\Publish\Core\Persistence\SqlNg\Content\Location\Gateway as LocationGateway;
use eZ\Publish\Core\Persistence\SqlNg\Content\Location\Mapper as LocationMapper;

/**
 * The Location Handler interface defines operations on Location elements in the storage engine.
 */
class Handler implements BaseTrashHandler
{
    /**
     * Construct from userGateway
     */
    public function __construct()
    {
    }

    /**
     * Loads the data for the trashed location identified by $id.
     * $id is the same as original location (which has been previously trashed)
     *
     * @param int $id
     *
     * @throws \eZ\Publish\API\Repository\Exceptions\NotFoundException
     *
     * @return \eZ\Publish\SPI\Persistence\Content\Location\Trashed
     */
    public function loadTrashItem( $id )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Sends a subtree starting to $locationId to the trash
     * and returns a Trashed object corresponding to $locationId.
     *
     * Moves all locations in the subtree to the Trash. The associated content
     * objects are left untouched.
     *
     * @param mixed $locationId
     *
     * @return null|\eZ\Publish\SPI\Persistence\Content\Location\Trashed null if location was deleted, otherwise Trashed object
     */
    public function trashSubtree( $locationId )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Returns a trashed location to normal state.
     *
     * Recreates the originally trashed location in the new position.
     * If this is not possible (because the old location does not exist any more),
     * a ParentNotFound exception is thrown.
     *
     * Returns newly restored location Id.
     *
     * @param mixed $trashedId
     * @param mixed $newParentId
     *
     * @return int Newly restored location id
     * @throws \eZ\Publish\Core\Base\Exceptions\NotFoundException If $newParentId is invalid
     */
    public function recover( $trashedId, $newParentId )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Returns an array of all trashed locations satisfying the $criterion (if provided),
     * sorted with SortClause objects contained in $sort (if any).
     * If no criterion is provided (null), no filter is applied
     *
     * @param \eZ\Publish\API\Repository\Values\Content\Query\Criterion $criterion
     * @param int $offset Offset to start listing from, 0 by default
     * @param int $limit Limit for the listing. Null by default (no limit)
     * @param \eZ\Publish\API\Repository\Values\Content\Query\SortClause[] $sort
     *
     * @return \eZ\Publish\SPI\Persistence\Content\Location\Trashed[]
     */
    public function findTrashItems( Criterion $criterion = null, $offset = 0, $limit = null, array $sort = null )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Empties the trash
     * Everything contained in the trash must be removed
     *
     * @return void
     */
    public function emptyTrash()
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Removes a trashed location identified by $trashedLocationId from trash
     * Associated content has to be deleted
     *
     * @param int $trashedId
     *
     * @return void
     */
    public function deleteTrashItem( $trashedId )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Triggers delete operations for $trashItem.
     * If there is no more locations for corresponding content, then it will be deleted as well.
     *
     * @param \eZ\Publish\SPI\Persistence\Content\Location\Trashed $trashItem
     *
     * @return void
     */
    protected function delete( Trashed $trashItem )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }
}

