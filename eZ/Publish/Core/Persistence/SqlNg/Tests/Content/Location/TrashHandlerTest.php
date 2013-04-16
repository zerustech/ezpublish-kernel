<?php
/**
 * File contains: eZ\Publish\Core\Persistence\SqlNg\Tests\Content\Trash\TrashHandlerTest class
 *
 * @copyright Copyright (C) 1999-2012 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 */

namespace eZ\Publish\Core\Persistence\SqlNg\Tests\Content\Location;

use eZ\Publish\Core\Persistence\SqlNg\Tests\TestCase;
use eZ\Publish\Core\Persistence\SqlNg\Content\Location\Trash\Handler;
use eZ\Publish\SPI\Persistence\Content\Location\Trashed;

/**
 * Test case for TrashHandlerTest
 */
class TrashHandlerTest extends TestCase
{
    /**
     * Returns the handler to test
     *
     * @return \eZ\Publish\Core\Persistence\SqlNg\Content\Location\Handler
     */
    protected function getTrashHandler()
    {
        return $this->getPersistenceHandler()->trashHandler();
    }

    public function testTrashSubtree()
    {
        $location = $this->getLocation();

        $handler = $this->getTrashHandler();

        $trashedObject = $handler->trashSubtree( $location->id );

        $this->assertInstanceOf( 'eZ\\Publish\\SPI\\Persistence\\Content\\Location\\Trashed', $trashedObject );
        $this->assertSame( $location->id, $trashedObject->id );

        return $trashedObject;
    }

    /**
     * @depends testTrashSubtree
     */
    public function testTrashSubtreeReturnsNullIfLocationWasDeleted( $trashedObject )
    {
        $handler = $this->getTrashHandler();

        $returnValue = $handler->trashSubtree( $trashedObject->id );
        $this->assertNull( $returnValue );
    }

    /**
     * @depends testTrashSubtree
     * @expectedException eZ\Publish\Core\Base\Exceptions\NotFoundException
     */
    public function testFailLoadTrashedLocation( $trashedObject )
    {
        $handler = $this->getPersistenceHandler()->locationHandler();

        $handler->load( $trashedObject->id );
    }

    /**
     * @depends testTrashSubtree
     * @expectedException eZ\Publish\Core\Base\Exceptions\NotFoundException
     */
    public function testFailLoadTrashedLocationByRemoteId( $trashedObject )
    {
        $handler = $this->getPersistenceHandler()->locationHandler();

        $handler->loadByRemoteId( $trashedObject->remoteId );
    }

    /**
     * @depends testTrashSubtree
     */
    public function testFailLoadTrashedLocationByContent( $trashedObject )
    {
        $handler = $this->getPersistenceHandler()->locationHandler();

        $this->assertEquals(
            array(),
            $handler->loadLocationsByContent( $trashedObject->contentId )
        );
    }

    /**
     * @depends testTrashSubtree
     */
    public function testLoadTrashItem( $trashedObject )
    {
        $handler = $this->getTrashHandler();

        $loaded = $handler->loadTrashItem( $trashedObject->id );

        $this->assertEquals(
            $trashedObject,
            $loaded
        );
    }

    /**
     * @expectedException eZ\Publish\Core\Base\Exceptions\NotFoundException
     */
    public function testLoadTrashItemNotFound()
    {
        $handler = $this->getTrashHandler();

        $handler->loadTrashItem( 1337 );
    }

    /**
     * @depends testTrashSubtree
     */
    public function testRecover( $trashedObject )
    {
        $this->markTestIncomplete("Trash handler still missing.");
        $handler = $this->getTrashHandler();

        $locationId = $handler->recover( $trashedObject->id, 2 );

        $this->assertNotNull( $locationId );
    }

    public function testEmptyTrash()
    {
        $this->markTestIncomplete("Trash handler still missing.");
        $handler = $this->getTrashHandler();

        // @TODO: Fixture?

        $handler->emptyTrash();

        // @TODO: Assertions?
    }

    public function testDeleteTrashItemNoMoreLocations()
    {
        $this->markTestIncomplete("Trash handler still missing.");
        $handler = $this->getTrashHandler();

        // @TODO: Fixture?

        $handler->deleteTrashItem( 69 );

        // @TODO: Assertions?
    }

    public function testDeleteTrashItemStillHaveLocations()
    {
        $this->markTestIncomplete("Trash handler still missing.");
        $handler = $this->getTrashHandler();

        // @TODO: Fixture?

        $handler->deleteTrashItem( 69 );

        // @TODO: Assertions?
    }
}
