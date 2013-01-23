<?php
/**
 * File containing the Location Handler class
 *
 * @copyright Copyright (C) 1999-2012 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 */

namespace eZ\Publish\Core\Persistence\SqlNg\Content\Location;

use eZ\Publish\SPI\Persistence\Content\Location;
use eZ\Publish\SPI\Persistence\Content\Location\CreateStruct;
use eZ\Publish\SPI\Persistence\Content\Location\UpdateStruct;
use eZ\Publish\SPI\Persistence\Content\Location\Handler as BaseLocationHandler;
use eZ\Publish\Core\Persistence\SqlNg\Content\Handler as ContentHandler;
use eZ\Publish\Core\Persistence\SqlNg\Content\Mapper as ContentMapper;
use eZ\Publish\Core\Persistence\SqlNg\Content\Location\Gateway as LocationGateway;
use eZ\Publish\Core\Persistence\SqlNg\Content\Location\Mapper as LocationMapper;
use eZ\Publish\Core\Persistence\SqlNg\Content\UrlAlias\Handler as UrlAliasHandler;
use eZ\Publish\SPI\Persistence\Content\MetadataUpdateStruct;

/**
 * The Location Handler interface defines operations on Location elements in the storage engine.
 */
class Handler implements BaseLocationHandler
{
    /**
     * Gateway for handling location data
     *
     * @var \eZ\Publish\Core\Persistence\SqlNg\Content\Location\Gateway
     */
    protected $locationGateway;

    /**
     * Location locationMapper
     *
     * @var \eZ\Publish\Core\Persistence\SqlNg\Content\Location\Mapper
     */
    protected $locationMapper;

    /**
     * Content handler
     *
     * @var \eZ\Publish\Core\Persistence\SqlNg\Content\Handler
     */
    protected $contentHandler;

    /**
     * Content locationMapper
     *
     * @var \eZ\Publish\Core\Persistence\SqlNg\Content\Mapper
     */
    protected $contentMapper;

    /**
     * Construct from userGateway
     *
     * @param \eZ\Publish\Core\Persistence\SqlNg\Content\Location\Gateway $locationGateway
     * @param \eZ\Publish\Core\Persistence\SqlNg\Content\Location\Mapper $locationMapper
     * @param \eZ\Publish\Core\Persistence\SqlNg\Content\Handler $contentHandler
     * @param \eZ\Publish\Core\Persistence\SqlNg\Content\Mapper $contentMapper
     *
     * @return \eZ\Publish\Core\Persistence\SqlNg\Content\Location\Handler
     */
    public function __construct(
        LocationGateway $locationGateway,
        LocationMapper $locationMapper,
        ContentHandler $contentHandler,
        ContentMapper $contentMapper
    )
    {
        $this->locationGateway = $locationGateway;
        $this->locationMapper = $locationMapper;
        $this->contentHandler = $contentHandler;
        $this->contentMapper = $contentMapper;
    }

    /**
     * Loads the data for the location identified by $locationId.
     *
     * @param int $locationId
     *
     * @return \eZ\Publish\SPI\Persistence\Content\Location
     */
    public function load( $locationId )
    {
        return $this->locationMapper->createLocationFromRow(
            $this->locationGateway->getBasicNodeData( $locationId )
        );
    }

    /**
     * Loads the data for the location identified by $remoteId.
     *
     * @param string $remoteId
     *
     * @throws \eZ\Publish\API\Repository\Exceptions\NotFoundException
     *
     * @return \eZ\Publish\SPI\Persistence\Content\Location
     */
    public function loadByRemoteId( $remoteId )
    {
        return $this->locationMapper->createLocationFromRow(
            $this->locationGateway->getBasicNodeDataByRemoteId( $remoteId )
        );
    }

    /**
     * Loads all locations for $contentId, optionally limited to a sub tree
     * identified by $rootLocationId
     *
     * @param int $contentId
     * @param int $rootLocationId
     *
     * @return \eZ\Publish\SPI\Persistence\Content\Location[]
     */
    public function loadLocationsByContent( $contentId, $rootLocationId = null )
    {
        return $this->locationMapper->createLocationsFromRows(
            $this->locationGateway->loadLocationDataByContent( $contentId, $rootLocationId )
        );
    }

    /**
     * Copy location object identified by $sourceId, into destination identified by $destinationParentId.
     *
     * Performs a deep copy of the location identified by $sourceId and all of
     * its child locations, copying the most recent published content object
     * for each location to a new content object without any additional version
     * information. Relations are not copied. URLs are not touched at all.
     *
     * @todo update subtree modification time, optionally retain dates and set creator
     *
     * @param mixed $sourceId
     * @param mixed $destinationParentId
     *
     * @return Location the newly created Location.
     */
    public function copySubtree( $sourceId, $destinationParentId )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Moves location identified by $sourceId into new parent identified by $destinationParentId.
     *
     * Performs a full move of the location identified by $sourceId to a new
     * destination, identified by $destinationParentId. Relations do not need
     * to be updated, since they refer to Content. URLs are not touched.
     *
     * @param mixed $sourceId
     * @param mixed $destinationParentId
     *
     * @return boolean
     */
    public function move( $sourceId, $destinationParentId )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Marks the given nodes and all ancestors as modified
     *
     * Optionally a time stamp with the modification date may be specified,
     * otherwise the current time is used.
     *
     * @param int|string $locationId
     * @param int $timestamp
     *
     * @return void
     */
    public function markSubtreeModified( $locationId, $timestamp = null )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Sets a location to be hidden, and it self + all children to invisible.
     *
     * @param mixed $id Location ID
     */
    public function hide( $id )
    {
        $sourceNodeData = $this->locationGateway->getBasicNodeData( $id );

        $this->locationGateway->hideSubtree( $sourceNodeData['path_string'] );
    }

    /**
     * Sets a location to be unhidden, and self + children to visible unless a parent is hiding the tree.
     * If not make sure only children down to first hidden node is marked visible.
     *
     * @param mixed $id
     */
    public function unHide( $id )
    {
        $sourceNodeData = $this->locationGateway->getBasicNodeData( $id );

        $this->locationGateway->unhideSubtree( $sourceNodeData['path_string'] );
    }

    /**
     * Swaps the content object being pointed to by a location object.
     *
     * Make the location identified by $locationId1 refer to the Content
     * referred to by $locationId2 and vice versa.
     *
     * @param mixed $locationId1
     * @param mixed $locationId2
     *
     * @return boolean
     */
    public function swap( $locationId1, $locationId2 )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Updates an existing location.
     *
     * @param \eZ\Publish\SPI\Persistence\Content\Location\UpdateStruct $location
     * @param int $locationId
     *
     * @return boolean
     */
    public function update( UpdateStruct $location, $locationId )
    {
        $this->locationGateway->update( $location, $locationId );
    }

    /**
     * Creates a new location rooted at $location->parentId.
     *
     * @param \eZ\Publish\SPI\Persistence\Content\Location\CreateStruct $createStruct
     *
     * @return \eZ\Publish\SPI\Persistence\Content\Location
     */
    public function create( CreateStruct $createStruct )
    {
        $locationStruct = $this->locationGateway->create(
            $createStruct,
            $createStruct->parentId ? $this->locationGateway->getBasicNodeData( $createStruct->parentId ) : null,
            Gateway::PUBLISHED
        );

        return $locationStruct;
    }

    /**
     * Removes all Locations under and including $locationId.
     *
     * Performs a recursive delete on the location identified by $locationId,
     * including all of its child locations. Content which is not referred to
     * by any other location is automatically removed. Content which looses its
     * main Location will get the first of its other Locations assigned as the
     * new main Location.
     *
     * @param mixed $locationId
     *
     * @return boolean
     */
    public function removeSubtree( $locationId )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Set section on all content objects in the subtree
     *
     * @param mixed $locationId
     * @param mixed $sectionId
     *
     * @return void
     */
    public function setSectionForSubtree( $locationId, $sectionId )
    {
        $nodeData = $this->locationGateway->getBasicNodeData( $locationId );

        $this->locationGateway->setSectionForSubtree( $nodeData['path_string'], $sectionId );
    }

    /**
     * Changes main location of content identified by given $contentId to location identified by given $locationId
     *
     * Updates ezcontentobject_tree and eznode_assignment tables (eznode_assignment for content current version number).
     *
     * @param mixed $contentId
     * @param mixed $locationId
     *
     * @return void
     */
    public function changeMainLocation( $contentId, $locationId )
    {
        $parentLocationId = $this->load( $locationId )->parentId;

        $this->locationGateway->changeMainLocation(
            $contentId,
            $locationId,
            $this->contentHandler->loadContentInfo( $contentId )->currentVersionNo,
            $parentLocationId
        );

        $this->setSectionForSubtree(
            $locationId,
            $this->contentHandler->loadContentInfo( $this->load( $parentLocationId )->contentId )->sectionId
        );
    }
}
