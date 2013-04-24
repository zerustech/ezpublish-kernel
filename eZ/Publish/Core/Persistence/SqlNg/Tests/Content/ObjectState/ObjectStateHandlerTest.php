<?php
/**
 * File contains: eZ\Publish\Core\Persistence\SqlNg\Tests\Content\Language\ObjectStateHandlerTest class
 *
 * @copyright Copyright (C) 1999-2013 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 */

namespace eZ\Publish\Core\Persistence\SqlNg\Tests\Content\ObjectState;

use eZ\Publish\Core\Persistence\SqlNg\Tests\TestCase;

use eZ\Publish\SPI\Persistence;
use eZ\Publish\Core\Persistence\SqlNg;
use eZ\Publish\API\Repository;

/**
 * Test case for Object state Handler
 */
class ObjectStateHandlerTest extends TestCase
{
    /**
     * Returns the handler to test
     *
     * @return \eZ\Publish\Core\Persistence\SqlNg\Content\ObjectState\Handler
     */
    protected function getObjectStateHandler()
    {
        return $this->getPersistenceHandler()->objectStateHandler();
    }

    public function testCreateGroup()
    {
        $handler = $this->getObjectStateHandler();

        $language = $this->getLanguage();
        $stateGroup = $handler->createGroup(
            new Persistence\Content\ObjectState\InputStruct( array(
                'defaultLanguage' => $language->languageCode,
                'identifier' => 'test-group',
                'name' => array(
                    $language->languageCode => 'Test Group',
                ),
                'description' => array(
                    $language->languageCode => 'Test Group',
                ),
            ) )
        );

        $this->assertInstanceOf(
            'eZ\\Publish\\SPI\\Persistence\\Content\\ObjectState\\Group',
            $stateGroup
        );

        return $stateGroup;
    }

    /**
     * @depends testCreateGroup
     */
    public function testLoadGroup( $stateGroup )
    {
        $handler = $this->getObjectStateHandler();

        $loaded = $handler->loadGroup( $stateGroup->id );

        $this->assertEquals( $stateGroup, $loaded );
    }

    /**
     * @expectedException \eZ\Publish\API\Repository\Exceptions\NotFoundException
     */
    public function testLoadGroupThrowsNotFoundException()
    {
        $handler = $this->getObjectStateHandler();
        $handler->loadGroup( PHP_INT_MAX );
    }

    /**
     * @depends testCreateGroup
     */
    public function testLoadGroupByIdentifier( $stateGroup )
    {
        $handler = $this->getObjectStateHandler();

        $loaded = $handler->loadGroupByIdentifier( $stateGroup->identifier );

        $this->assertEquals( $stateGroup, $loaded );
    }

    /**
     * @expectedException \eZ\Publish\API\Repository\Exceptions\NotFoundException
     */
    public function testLoadGroupByIdentifierThrowsNotFoundException()
    {
        $handler = $this->getObjectStateHandler();

        $handler->loadGroupByIdentifier( 'unknown' );
    }

    /**
     * @depends testCreateGroup
     */
    public function testLoadAllGroups( $stateGroup )
    {
        $handler = $this->getObjectStateHandler();

        $result = $handler->loadAllGroups();

        $this->assertEquals(
            array( $stateGroup ),
            $result
        );
    }

    /**
     * @depends testCreateGroup
     */
    public function testUpdateGroup( $stateGroup )
    {
        $handler = $this->getObjectStateHandler();

        $language = $this->getLanguage();
        $updated = $handler->updateGroup(
            $stateGroup->id,
            new Persistence\Content\ObjectState\InputStruct( array(
                'defaultLanguage' => $language->languageCode,
                'identifier' => 'test-group-updated',
                'name' => array(
                    $language->languageCode => 'Test Group Updated',
                ),
                'description' => array(
                    $language->languageCode => 'Test Group Updated',
                ),
            ) )
        );

        $this->assertInstanceOf(
            'eZ\\Publish\\SPI\\Persistence\\Content\\ObjectState\\Group',
            $updated
        );

        return $updated;
    }

    /**
     * @covers \eZ\Publish\Core\Persistence\SqlNg\Content\ObjectState\Handler::loadObjectStates
     *
     * @return void
     */
    public function testLoadObjectStates()
    {
        $handler = $this->getObjectStateHandler();

        $result = $handler->loadObjectStates( 2 );

        foreach ( $result as $resultItem )
        {
            $this->assertInstanceOf(
                'eZ\\Publish\\SPI\\Persistence\\Content\\ObjectState',
                $resultItem
            );
        }
    }

    /**
     * @covers \eZ\Publish\Core\Persistence\SqlNg\Content\ObjectState\Handler::deleteGroup
     *
     * @return void
     */
    public function testDeleteGroup()
    {
        $handler = $this->getObjectStateHandler();

        $handler->deleteGroup( 2 );
    }

    /**
     * @covers \eZ\Publish\Core\Persistence\SqlNg\Content\ObjectState\Handler::create
     *
     * @return void
     */
    public function testCreate()
    {
        $handler = $this->getObjectStateHandler();

        $result = $handler->create( 2, $this->getInputStructFixture() );

        $this->assertInstanceOf(
            'eZ\\Publish\\SPI\\Persistence\\Content\\ObjectState',
            $result
        );
    }

    /**
     * @covers \eZ\Publish\Core\Persistence\SqlNg\Content\ObjectState\Handler::load
     *
     * @return void
     */
    public function testLoad()
    {
        $handler = $this->getObjectStateHandler();

        $result = $handler->load( 1 );

        $this->assertInstanceOf(
            'eZ\\Publish\\SPI\\Persistence\\Content\\ObjectState',
            $result
        );
    }

    /**
     * @return void
     * @covers \eZ\Publish\Core\Persistence\SqlNg\Content\ObjectState\Handler::load
     * @expectedException \eZ\Publish\API\Repository\Exceptions\NotFoundException
     */
    public function testLoadThrowsNotFoundException()
    {
        $handler = $this->getObjectStateHandler();

        $handler->load( PHP_INT_MAX );
    }

    /**
     * @covers \eZ\Publish\Core\Persistence\SqlNg\Content\ObjectState\Handler::loadByIdentifier
     *
     * @return void
     */
    public function testLoadByIdentifier()
    {
        $handler = $this->getObjectStateHandler();

        $result = $handler->loadByIdentifier( 'not_locked', 2 );

        $this->assertInstanceOf(
            'eZ\\Publish\\SPI\\Persistence\\Content\\ObjectState',
            $result
        );
    }

    /**
     * @return void
     * @covers \eZ\Publish\Core\Persistence\SqlNg\Content\ObjectState\Handler::loadByIdentifier
     * @expectedException \eZ\Publish\API\Repository\Exceptions\NotFoundException
     */
    public function testLoadByIdentifierThrowsNotFoundException()
    {
        $handler = $this->getObjectStateHandler();

        $handler->loadByIdentifier( 'unknown', 2 );
    }

    /**
     * @covers \eZ\Publish\Core\Persistence\SqlNg\Content\ObjectState\Handler::update
     *
     * @return void
     */
    public function testUpdate()
    {
        $handler = $this->getObjectStateHandler();

        $result = $handler->update( 1, $this->getInputStructFixture() );

        $this->assertInstanceOf(
            'eZ\\Publish\\SPI\\Persistence\\Content\\ObjectState',
            $result
        );
    }

    /**
     * @covers \eZ\Publish\Core\Persistence\SqlNg\Content\ObjectState\Handler::setPriority
     *
     * @return void
     */
    public function testSetPriority()
    {
        $handler = $this->getObjectStateHandler();

        $handler->setPriority( 2, 0 );
    }

    /**
     * @covers \eZ\Publish\Core\Persistence\SqlNg\Content\ObjectState\Handler::delete
     *
     * @return void
     */
    public function testDelete()
    {
        $handler = $this->getObjectStateHandler();

        $handler->delete( 1 );
    }

    /**
     * @return void
     * @covers \eZ\Publish\Core\Persistence\SqlNg\Content\ObjectState\Handler::delete
     * @expectedException \eZ\Publish\API\Repository\Exceptions\NotFoundException
     */
    public function testDeleteThrowsNotFoundException()
    {
        $handler = $this->getObjectStateHandler();

        $handler->delete( PHP_INT_MAX );
    }

    /**
     * @covers \eZ\Publish\Core\Persistence\SqlNg\Content\ObjectState\Handler::setContentState
     *
     * @return void
     */
    public function testSetContentState()
    {
        $handler = $this->getObjectStateHandler();

        $result = $handler->setContentState( 42, 2, 2 );

        $this->assertEquals( true, $result );
    }

    /**
     * @covers \eZ\Publish\Core\Persistence\SqlNg\Content\ObjectState\Handler::getContentState
     *
     * @return void
     */
    public function testGetContentState()
    {
        $handler = $this->getObjectStateHandler();

        $result = $handler->getContentState( 42, 2 );

        $this->assertInstanceOf(
            'eZ\\Publish\\SPI\\Persistence\\Content\\ObjectState',
            $result
        );
    }

    /**
     * @covers \eZ\Publish\Core\Persistence\SqlNg\Content\ObjectState\Handler::getContentCount
     *
     * @return void
     */
    public function testGetContentCount()
    {
        $handler = $this->getObjectStateHandler();

        $result = $handler->getContentCount( 1 );

        $this->assertEquals( 185, $result );
    }

    /**
     * Returns an object state
     *
     * @return \eZ\Publish\SPI\Persistence\Content\ObjectState
     */
    protected function getObjectStateFixture()
    {
        return new ObjectState();
    }

    /**
     * Returns an object state group
     *
     * @return \eZ\Publish\SPI\Persistence\Content\ObjectState\Group
     */
    protected function getObjectStateGroupFixture()
    {
        return new Group();
    }

    /**
     * Returns the InputStruct
     *
     * @return \eZ\Publish\SPI\Persistence\Content\ObjectState\InputStruct
     */
    protected function getInputStructFixture()
    {
        return new Persistence\Content\ObjectState\InputStruct();
    }
}
