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
     * @depends testCreateGroup
     */
    public function testCreate( $stateGroup )
    {
        $handler = $this->getObjectStateHandler();

        $language = $this->getLanguage();
        $state = $handler->create(
            $stateGroup->id,
            new Persistence\Content\ObjectState\InputStruct( array(
                'defaultLanguage' => $language->languageCode,
                'identifier' => 'test-state',
                'name' => array(
                    $language->languageCode => 'Test State',
                ),
                'description' => array(
                    $language->languageCode => 'Test State',
                ),
            ) )
        );

        $this->assertInstanceOf(
            'eZ\\Publish\\SPI\\Persistence\\Content\\ObjectState',
            $state
        );

        return $state;
    }

    /**
     * @depends testCreate
     */
    public function testLoad( $state )
    {
        $handler = $this->getObjectStateHandler();

        $loaded = $handler->load( $state->id );

        $this->assertEquals( $state, $loaded );
    }

    /**
     * @expectedException \eZ\Publish\API\Repository\Exceptions\NotFoundException
     */
    public function testLoadThrowsNotFoundException()
    {
        $handler = $this->getObjectStateHandler();

        $handler->load( PHP_INT_MAX );
    }

    /**
     * @depends testCreate
     */
    public function testLoadByIdentifier( $state )
    {
        $handler = $this->getObjectStateHandler();

        $loaded = $handler->loadByIdentifier( $state->identifier, $state->groupId );

        $this->assertEquals( $state, $loaded );
    }

    /**
     * @expectedException \eZ\Publish\API\Repository\Exceptions\NotFoundException
     */
    public function testLoadByIdentifierThrowsNotFoundException()
    {
        $handler = $this->getObjectStateHandler();

        $handler->loadByIdentifier( 'unknown', PHP_INT_MAX );
    }

    /**
     * @depends testCreate
     */
    public function testLoadObjectStates( $state )
    {
        $handler = $this->getObjectStateHandler();

        $result = $handler->loadObjectStates( $state->groupId );

        $this->assertEquals(
            array( $state ),
            $result
        );
    }

    /**
     * @depends testCreate
     */
    public function testUpdate( $state )
    {
        $handler = $this->getObjectStateHandler();

        $language = $this->getLanguage();
        $updated = $handler->update(
            $state->id,
            new Persistence\Content\ObjectState\InputStruct( array(
                'defaultLanguage' => $language->languageCode,
                'identifier' => 'test-updated',
                'name' => array(
                    $language->languageCode => 'Test Updated',
                ),
                'description' => array(
                    $language->languageCode => 'Test Updated',
                ),
            ) )
        );

        $this->assertInstanceOf(
            'eZ\\Publish\\SPI\\Persistence\\Content\\ObjectState',
            $updated
        );

        return $updated;
    }

    /**
     * @depends testCreate
     */
    public function testSetPriority( $state )
    {
        $handler = $this->getObjectStateHandler();

        $state = $handler->create(
            $state->groupId,
            new Persistence\Content\ObjectState\InputStruct( array(
                'defaultLanguage' => $state->defaultLanguage,
                'identifier' => 'test-state-2',
                'name' => array(
                    $state->defaultLanguage => 'Test State 2',
                ),
                'description' => array(
                    $state->defaultLanguage => 'Test State 2',
                ),
            ) )
        );

        $handler->setPriority( $state->id, 42 );

        $loaded = $handler->load( $state->id );
        $this->assertEquals( 1, $loaded->priority );
    }

    /**
     * @depends testCreate
     */
    public function testSetContentState( $state )
    {
        $handler = $this->getObjectStateHandler();

        $content = $this->getContent();
        $result = $handler->setContentState(
            $content->versionInfo->contentInfo->id,
            $state->groupId,
            $state->id
        );

        $this->assertEquals( true, $result );

        return $state;
    }

    /**
     * @depends testSetContentState
     */
    public function testChangeContentState( $state )
    {
        $handler = $this->getObjectStateHandler();

        $newState = $handler->create(
            $state->groupId,
            new Persistence\Content\ObjectState\InputStruct( array(
                'defaultLanguage' => $state->defaultLanguage,
                'identifier' => 'test-state-3',
                'name' => array(
                    $state->defaultLanguage => 'Test State 3',
                ),
                'description' => array(
                    $state->defaultLanguage => 'Test State 3',
                ),
            ) )
        );

        $content = $this->getContent();
        $result = $handler->setContentState(
            $content->versionInfo->contentInfo->id,
            $newState->groupId,
            $newState->id
        );

        $this->assertEquals( true, $result );

        return $newState;
    }

    /**
     * @depends testChangeContentState
     */
    public function testGetContentState( $state )
    {
        $handler = $this->getObjectStateHandler();

        $content = $this->getContent();
        $loaded = $handler->getContentState(
            $content->versionInfo->contentInfo->id,
            $state->groupId
        );

        $this->assertEquals( $state, $loaded );
    }

    /**
     * @expectedException \eZ\Publish\API\Repository\Exceptions\NotFoundException
     */
    public function testGetContentStateNotFoundException()
    {
        $handler = $this->getObjectStateHandler();

        $content = $this->getContent();
        $loaded = $handler->getContentState( PHP_INT_MAX, PHP_INT_MAX );
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
     * @depends testCreate
     * @expectedException \eZ\Publish\API\Repository\Exceptions\NotFoundException
     */
    public function testDelete( $state )
    {
        $handler = $this->getObjectStateHandler();

        $handler->delete( $state->id );

        $handler->load( $state->id );
    }

    /**
     * @expectedException \eZ\Publish\API\Repository\Exceptions\NotFoundException
     */
    public function testDeleteThrowsNotFoundException()
    {
        $handler = $this->getObjectStateHandler();

        $handler->delete( PHP_INT_MAX );
    }

    /**
     * @depends testCreateGroup
     */
    public function testDeleteGroup( $stateGroup )
    {
        $handler = $this->getObjectStateHandler();

        $handler->deleteGroup( $stateGroup->id );

        $this->assertEquals(array(), $handler->loadAllGroups() );
    }
}
