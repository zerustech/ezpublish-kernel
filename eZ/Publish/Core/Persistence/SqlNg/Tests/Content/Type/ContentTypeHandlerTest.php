<?php
/**
 * File contains: eZ\Publish\Core\Persistence\SqlNg\Tests\Content\Type\ContentTypeHandlerTest class
 *
 * @copyright Copyright (C) 1999-2012 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 */

namespace eZ\Publish\Core\Persistence\SqlNg\Tests\Content\Type;

use eZ\Publish\Core\Persistence\SqlNg\Tests\TestCase;
use eZ\Publish\SPI\Persistence;
use eZ\Publish\Core\Persistence\SqlNg;

/**
 * Test case for Content Type Handler.
 */
class ContentTypeHandlerTest extends TestCase
{
    /**
     * @covers eZ\Publish\Core\Persistence\SqlNg\Content\Type\Handler::__construct
     *
     * @return void
     */
    public function testCtor()
    {
        $handler = $this->getHandler();
        $this->assertInstanceOf(
            'eZ\\Publish\\Core\\Persistence\\SqlNg\\Content\\Type\\Handler',
            $handler
        );
    }

    public function testCreateGroup()
    {
        $handler = $this->getHandler();
        $group = $handler->createGroup(
            new Persistence\Content\Type\Group\CreateStruct( $values = array(
                'identifier' => 'testgroup',
                'created' => 123456789,
                'creatorId' => $this->getUser()->id,
                'modified' => 123456789,
                'modifierId' => $this->getUser()->id,
            ) )
        );

        $this->assertInstanceOf(
            'eZ\\Publish\\SPI\\Persistence\\Content\\Type\\Group',
            $group
        );

        $this->assertPropertiesCorrect( $values, $group );

        return $group;
    }

    /**
     * @depends testCreateGroup
     */
    public function testLoadGroup( $group )
    {
        $handler = $this->getHandler();
        $loaded = $handler->loadGroup( $group->id );

        $this->assertEquals( $group, $loaded );
    }

    /**
     * @depends testCreateGroup
     */
    public function testUpdateGroup( $group )
    {

        $handler = $this->getHandler();
        $group = $handler->updateGroup(
            new Persistence\Content\Type\Group\UpdateStruct( $values = array(
                'id' => $group->id,
                'identifier' => 'updated',
                'modified' => 123456789,
                'modifierId' => $this->getUser()->id,
            ) )
        );

        $this->assertInstanceOf(
            'eZ\\Publish\\SPI\\Persistence\\Content\\Type\\Group',
            $group
        );

        $this->assertPropertiesCorrect( $values, $group );
    }

    /**
     * @covers eZ\Publish\Core\Persistence\SqlNg\Content\Type\Handler::deleteGroup
     *
     * @return void
     */
    public function testDeleteGroupSuccess()
    {
        $handler = $this->getHandler();
        $handler->deleteGroup( 23 );
    }

    /**
     * @return void
     * @covers eZ\Publish\Core\Persistence\SqlNg\Content\Type\Handler::deleteGroup
     * @covers eZ\Publish\Core\Persistence\SqlNg\Exception\GroupNotEmpty
     * @expectedException eZ\Publish\Core\Persistence\SqlNg\Exception\GroupNotEmpty
     * @expectedExceptionMessage Group with ID "23" is not empty.
     */
    public function testDeleteGroupFailure()
    {
        $handler = $this->getHandler();
        $handler->deleteGroup( 23 );
    }

    /**
     * @covers eZ\Publish\Core\Persistence\SqlNg\Content\Type\Handler::loadGroupByIdentifier
     *
     * @return void
     */
    public function testLoadGroupByIdentifier()
    {
        $handler = $this->getHandler();
        $res = $handler->loadGroupByIdentifier( 'content' );

        $this->assertEquals(
            new Group(),
            $res
        );
    }

    /**
     * @covers eZ\Publish\Core\Persistence\SqlNg\Content\Type\Handler::loadAllGroups
     *
     * @return void
     */
    public function testLoadAllGroups()
    {
        $handler = $this->getHandler();
        $res = $handler->loadAllGroups();

        $this->assertEquals(
            array( new Group() ),
            $res
        );
    }

    /**
     * @covers eZ\Publish\Core\Persistence\SqlNg\Content\Type\Handler::loadContentTypes
     *
     * @return void
     */
    public function testLoadContentTypes()
    {
        $handler = $this->getHandler();
        $res = $handler->loadContentTypes( 23, 0 );

        $this->assertEquals(
            array( new Type() ),
            $res
        );
    }

    /**
     * @return void
     * @covers eZ\Publish\Core\Persistence\SqlNg\Content\Type\Handler::load
     * @covers eZ\Publish\Core\Persistence\SqlNg\Content\Type\Handler::loadFromRows
     */
    public function testLoad()
    {
        $handler = $this->getHandler();
        $type = $handler->load( 23, 1 );

        $this->assertEquals(
            new Type(),
            $type,
            'Type not loaded correctly'
        );
    }

    /**
     * @return void
     * @covers eZ\Publish\Core\Persistence\SqlNg\Content\Type\Handler::load
     * @covers eZ\Publish\Core\Persistence\SqlNg\Content\Type\Handler::loadFromRows
     * @expectedException \eZ\Publish\Core\Persistence\SqlNg\Exception\TypeNotFound
     */
    public function testLoadNotFound()
    {
        $handler = $this->getHandler();
        $type = $handler->load( 23, 1 );
    }

    /**
     * @return void
     * @covers eZ\Publish\Core\Persistence\SqlNg\Content\Type\Handler::load
     * @covers eZ\Publish\Core\Persistence\SqlNg\Content\Type\Handler::loadFromRows
     */
    public function testLoadDefaultVersion()
    {
        $handler = $this->getHandler();
        $type = $handler->load( 23 );

        $this->assertEquals(
            new Type(),
            $type,
            'Type not loaded correctly'
        );
    }

    /**
     * @return void
     * @covers eZ\Publish\Core\Persistence\SqlNg\Content\Type\Handler::loadByIdentifier
     * @covers eZ\Publish\Core\Persistence\SqlNg\Content\Type\Handler::loadFromRows
     */
    public function testLoadByIdentifier()
    {
        $handler = $this->getHandler();
        $type = $handler->loadByIdentifier( 'blogentry' );

        $this->assertEquals(
            new Type(),
            $type,
            'Type not loaded correctly'
        );
    }

    /**
     * @return void
     * @covers eZ\Publish\Core\Persistence\SqlNg\Content\Type\Handler::loadByRemoteId
     * @covers eZ\Publish\Core\Persistence\SqlNg\Content\Type\Handler::loadFromRows
     */
    public function testLoadByRemoteId()
    {
        $handler = $this->getHandler();
        $type = $handler->loadByRemoteId( 'someLongHash' );

        $this->assertEquals(
            new Type(),
            $type,
            'Type not loaded correctly'
        );
    }

    /**
     * @covers eZ\Publish\Core\Persistence\SqlNg\Content\Type\Handler::create
     *
     * @return void
     */
    public function testCreate()
    {
        $createStructFix = $this->getContenTypeCreateStructFixture();

        $handler = $this->getHandler();
        $type = $handler->create( $createStructFix );

        $this->assertInstanceOf(
            'eZ\\Publish\\SPI\\Persistence\\Content\\Type',
            $type,
            'Incorrect type returned from create()'
        );
        $this->assertEquals(
            23,
            $type->id,
            'Incorrect ID for Type.'
        );

        $this->assertEquals(
            42,
            $type->fieldDefinitions[0]->id,
            'Field definition ID not set correctly'
        );
        $this->assertEquals(
            42,
            $type->fieldDefinitions[1]->id,
            'Field definition ID not set correctly'
        );

        $this->assertEquals(
            $createStructClone,
            $createStructFix,
            'Create struct manipulated'
        );
    }

    /**
     * @covers eZ\Publish\Core\Persistence\SqlNg\Content\Type\Handler::update
     *
     * @return void
     */
    public function testUpdate()
    {
        $handler = $this->getHandler();
        $res = $handler->update(
            23, 1, new UpdateStruct()
        );

        $this->assertInstanceOf(
            'eZ\\Publish\\SPI\\Persistence\\Content\\Type',
            $res
        );
    }

    /**
     * @covers eZ\Publish\Core\Persistence\SqlNg\Content\Type\Handler::delete
     *
     * @return void
     */
    public function testDeleteSuccess()
    {
        $handler = $this->getHandler();
        $res = $handler->delete( 23, 0 );

        $this->assertTrue( $res );
    }

    /**
     * @return void
     * @covers \eZ\Publish\Core\Persistence\SqlNg\Content\Type\Handler::delete
     * @expectedException \eZ\Publish\Core\Base\Exceptions\NotFoundException
     */
    public function testDeleteThrowsNotFoundException()
    {
        $handler = $this->getHandler();
        $res = $handler->delete( 23, 0 );
    }

    /**
     * @return void
     * @covers \eZ\Publish\Core\Persistence\SqlNg\Content\Type\Handler::delete
     * @expectedException \eZ\Publish\Core\Base\Exceptions\BadStateException
     */
    public function testDeleteThrowsBadStateException()
    {
        $handler = $this->getHandler();
        $res = $handler->delete( 23, 0 );
    }

    /**
     * @covers eZ\Publish\Core\Persistence\SqlNg\Content\Type\Handler::createDraft
     *
     * @return void
     */
    public function testCreateVersion()
    {
        $res = $handlerMock->createDraft(
            42, 23
        );

        $this->assertInstanceOf(
            'eZ\\Publish\\SPI\\Persistence\\Content\\Type',
            $res
        );
    }

    /**
     * @covers eZ\Publish\Core\Persistence\SqlNg\Content\Type\Handler::copy
     *
     * @return void
     */
    public function testCopy()
    {
        $res = $handlerMock->copy(
            42, 23, 0
        );

        $this->assertInstanceOf(
            'eZ\\Publish\\SPI\\Persistence\\Content\\Type',
            $res
        );
    }

    /**
     * @covers eZ\Publish\Core\Persistence\SqlNg\Content\Type\Handler::link
     *
     * @return void
     */
    public function testLink()
    {
        $handler = $this->getHandler();
        $res = $handler->link( 3, 23, 1 );

        $this->assertTrue( $res );
    }

    /**
     * @covers eZ\Publish\Core\Persistence\SqlNg\Content\Type\Handler::unlink
     *
     * @return void
     */
    public function testUnlinkSuccess()
    {
        $handler = $this->getHandler();
        $res = $handler->unlink( 3, 23, 1 );

        $this->assertTrue( $res );
    }

    /**
     * @return void
     * @covers eZ\Publish\Core\Persistence\SqlNg\Content\Type\Handler::unlink
     * @covers eZ\Publish\Core\Persistence\SqlNg\Exception\RemoveLastGroupFromType
     * @expectedException eZ\Publish\Core\Persistence\SqlNg\Exception\RemoveLastGroupFromType
     * @expectedExceptionMessage Type with ID "23" in status "1" cannot be unlinked from its last group.
     */
    public function testUnlinkFailure()
    {
        $handler = $this->getHandler();
        $res = $handler->unlink( 3, 23, 1 );
    }

    /**
     * @covers eZ\Publish\Core\Persistence\SqlNg\Content\Type\Handler::getFieldDefinition
     *
     * @return void
     */
    public function testGetFieldDefinition()
    {
        $handler = $this->getHandler();
        $fieldDefinition = $handler->getFieldDefinition( 42, Type::STATUS_DEFINED );

        $this->assertInstanceOf(
            'eZ\\Publish\\SPI\\Persistence\\Content\\Type\\FieldDefinition',
            $fieldDefinition
        );
    }

    /**
     * @covers eZ\Publish\Core\Persistence\SqlNg\Content\Type\Handler::addFieldDefinition
     *
     * @return void
     */
    public function testAddFieldDefinition()
    {
        $fieldDef = new FieldDefinition();

        $handler = $this->getHandler();
        $handler->addFieldDefinition( 23, 1, $fieldDef );

        $this->assertEquals(
            42,
            $fieldDef->id
        );
    }

    /**
     * @covers eZ\Publish\Core\Persistence\SqlNg\Content\Type\Handler::removeFieldDefinition
     *
     * @return void
     */
    public function testRemoveFieldDefinition()
    {
        $handler = $this->getHandler();
        $res = $handler->removeFieldDefinition( 23, 1, 42 );

        $this->assertTrue( $res );
    }

    /**
     * @covers eZ\Publish\Core\Persistence\SqlNg\Content\Type\Handler::updateFieldDefinition
     *
     * @return void
     */
    public function testUpdateFieldDefinition()
    {
        $fieldDef = new FieldDefinition();

        $handler = $this->getHandler();
        $res = $handler->updateFieldDefinition( 23, 1, $fieldDef );

        $this->assertNull( $res );
    }

    /**
     * @covers eZ\Publish\Core\Persistence\SqlNg\Content\Type\Handler::publish
     *
     * @return void
     */
    public function testPublish()
    {
        $handler = $this->getHandler();
        $handler->publish( 23 );
    }

    /**
     * @covers eZ\Publish\Core\Persistence\SqlNg\Content\Type\Handler::publish
     *
     * @return void
     */
    public function testPublishNoOldType()
    {
        $handler = $this->getHandler();
        $handler->publish( 23 );
    }

    /**
     * Returns a handler to test, based on mock objects
     *
     * @return \eZ\Publish\Core\Persistence\SqlNg\Content\Type\Handler
     */
    protected function getHandler()
    {
        return $this->getPersistenceHandler()->contentTypeHandler();
    }
}
