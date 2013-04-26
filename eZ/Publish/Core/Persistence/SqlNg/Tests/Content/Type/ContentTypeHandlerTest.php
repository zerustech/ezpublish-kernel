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
     * Returns a handler to test, based on mock objects
     *
     * @return \eZ\Publish\Core\Persistence\SqlNg\Content\Type\Handler
     */
    protected function getHandler()
    {
        return $this->getPersistenceHandler()->contentTypeHandler();
    }

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
                'name' => array(
                    'de' => 'Test-Gruppe',
                ),
                'description' => array(
                    'de' => 'Test-Gruppe',
                ),
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
    public function testLoadGroupByIdentifier( $group )
    {
        $handler = $this->getHandler();
        $loaded = $handler->loadGroupByIdentifier( $group->identifier );

        $this->assertEquals( $group, $loaded );
    }

    /**
     * @depends testCreateGroup
     */
    public function testLoadAllGroups( $group )
    {
        $handler = $this->getHandler();
        $loaded = $handler->loadAllGroups();

        $this->assertEquals( array( $group ), $loaded );
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
     * @depends testCreateGroup
     */
    public function testDeleteGroupSuccess( $group )
    {
        $handler = $this->getHandler();
        $handler->deleteGroup( $group->id );

        return $group;
    }

    /**
     * @depends testDeleteGroupSuccess
     * @expectedException \eZ\Publish\Core\Base\Exceptions\NotFoundException
     */
    public function testDeleteNotExistingGroup( $group )
    {
        $handler = $this->getHandler();
        $handler->deleteGroup( $group->id );
    }

    /**
     * @depends testCreateGroup
     */
    public function testCreate()
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

        $type = $handler->create(
            new Persistence\Content\Type\CreateStruct( array(
                'identifier' => 'testtype',
                'status' => 0,
                'groupIds' => array( $group->id ),
                'created' => 123456789,
                'creatorId' => $this->getUser()->id,
                'modified' => 123456789,
                'modifierId' => $this->getUser()->id,
                'remoteId' => 'testtype',
                'initialLanguageId' => $this->getLanguage()->id,
                'fieldDefinitions' => array(
                    new Persistence\Content\Type\FieldDefinition( array(
                        'identifier' => 'title',
                        'fieldGroup' => '1',
                        'position' => 1,
                        'fieldType' => 'ezstring',
                        'isTranslatable' => true,
                        'isRequired' => true,
                        'isInfoCollector' => true,
                        'fieldTypeConstraints' => new Persistence\Content\FieldTypeConstraints(),
                        'defaultValue' => new Persistence\Content\FieldValue( array(
                            "data" => "Hello World"
                        ) ),
                        'isSearchable' => true,
                        'name' => array(
                            'de' => 'Test-Feld',
                        ),
                        'description' => array(
                            'de' => 'Test-Feld',
                        ),
                    ) )
                ),
                'name' => array(
                    'de' => 'Test-Typ',
                ),
                'description' => array(
                    'de' => 'Test-Typ',
                ),
            ) )
        );

        $this->assertInstanceOf(
            'eZ\\Publish\\SPI\\Persistence\\Content\\Type',
            $type
        );
        $this->assertNotNull( $type->id );
        return $type;
    }

    /**
     * @depends testCreate
     * @expectedException eZ\Publish\Core\Base\Exceptions\BadStateException
     */
    public function testDeleteNonEmptyGroup( $type )
    {
        $handler = $this->getHandler();
        $handler->deleteGroup( $type->groupIds[0] );
    }

    /**
     * @depends testCreate
     */
    public function testLoadContentTypes( $type )
    {
        $handler = $this->getHandler();
        $loaded = $handler->loadContentTypes( $type->groupIds[0], $type->status );

        $this->assertEquals( array( $type ), $loaded );
    }

    /**
     * @depends testCreate
     */
    public function testLoad( $type )
    {
        $handler = $this->getHandler();
        $loaded = $handler->load( $type->id, $type->status );

        $this->assertEquals( $type, $loaded );
    }

    /**
     * @return void
     * @expectedException \eZ\Publish\Core\Base\Exceptions\NotFoundException
     */
    public function testLoadNotFound()
    {
        $handler = $this->getHandler();
        $type = $handler->load( 1337 );
    }

    /**
     * @depends testCreate
     */
    public function testLoadDefaultVersion( $type )
    {
        $handler = $this->getHandler();
        $loaded = $handler->load( $type->id );

        $this->assertEquals( $type, $loaded );
    }

    /**
     * @depends testCreate
     */
    public function testLoadByIdentifier( $type )
    {
        $handler = $this->getHandler();
        $loaded = $handler->loadByIdentifier( $type->identifier );

        $this->assertEquals( $type, $loaded );
    }

    /**
     * @depends testCreate
     */
    public function testLoadByRemoteId( $type )
    {
        $handler = $this->getHandler();
        $loaded = $handler->loadByRemoteId( $type->remoteId );

        $this->assertEquals( $type, $loaded );
    }


    /**
     * @depends testCreate
     */
    public function testUpdate( $type )
    {
        $handler = $this->getHandler();
        $updated = $handler->update(
            $type->id,
            $type->status,
            new Persistence\Content\Type\UpdateStruct( array(
                'name' => $type->name,
                'description' => $type->description,
                'identifier' => 'updated',
                'modified' => $type->modified,
                'modifierId' => $type->modifierId,
                'remoteId' => $type->remoteId,
                'urlAliasSchema' => $type->urlAliasSchema,
                'nameSchema' => $type->nameSchema,
                'isContainer' => $type->isContainer,
                'initialLanguageId' => $type->initialLanguageId,
                'sortField' => $type->sortField,
                'sortOrder' => $type->sortOrder,
                'defaultAlwaysAvailable' => $type->defaultAlwaysAvailable,
            ) )
        );

        $this->assertInstanceOf(
            'eZ\\Publish\\SPI\\Persistence\\Content\\Type',
            $updated
        );

        $this->assertEquals( 'updated', $updated->identifier );
    }

    /**
     * @depends testCreate
     */
    public function testCopy( $type )
    {
        $handler = $this->getHandler();
        $copy = $handler->copy(
            $this->getUser()->id,
            $type->id,
            0
        );

        $this->assertInstanceOf(
            'eZ\\Publish\\SPI\\Persistence\\Content\\Type',
            $copy
        );
    }

    /**
     * @depends testCreate
     */
    public function testLink( $type )
    {
        $handler = $this->getHandler();
        $newGroup = $handler->createGroup(
            new Persistence\Content\Type\Group\CreateStruct( $values = array(
                'identifier' => 'linkgroup',
                'created' => 123456789,
                'creatorId' => $this->getUser()->id,
                'modified' => 123456789,
                'modifierId' => $this->getUser()->id,
            ) )
        );

        $this->assertTrue(
            $handler->link( $newGroup->id, $type->id, $type->status )
        );

        return $handler->load( $type->id, $type->status );
    }

    /**
     * @depends testLink
     */
    public function testUnlinkSuccess( $type )
    {
        $handler = $this->getHandler();
        $this->assertTrue(
            $handler->unlink( $type->groupIds[1], $type->id, $type->status )
        );

        return $handler->load( $type->id, $type->status );
    }

    /**
     * @depends testUnlinkSuccess
     * @expectedException \RuntimeException
     */
    public function testUnlinkFailure( $type )
    {
        $handler = $this->getHandler();
        $handler->unlink( $type->groupIds[0], $type->id, $type->status );
    }

    /**
     * @depends testCreate
     */
    public function testGetFieldDefinition( $type )
    {
        $handler = $this->getHandler();
        $fieldDefinition = $handler->getFieldDefinition(
            $type->fieldDefinitions[0]->id,
            $type->status
        );

        $this->assertInstanceOf(
            'eZ\\Publish\\SPI\\Persistence\\Content\\Type\\FieldDefinition',
            $fieldDefinition
        );
    }

    /**
     * @depends testCreate
     */
    public function testAddFieldDefinition( $type )
    {
        $fieldDefinition = new Persistence\Content\Type\FieldDefinition( array(
            'identifier' => 'text',
            'fieldGroup' => '1',
            'position' => 2,
            'fieldType' => 'eztext',
            'isTranslatable' => true,
            'isRequired' => false,
        ) );

        $handler = $this->getHandler();
        $handler->addFieldDefinition( $type->id, $type->status, $fieldDefinition );

        $this->assertNotNull( $fieldDefinition->id );

        return $handler->load( $type->id, $type->status );
    }

    /**
     * @depends testAddFieldDefinition
     */
    public function testRemoveFieldDefinition( $type )
    {
        $handler = $this->getHandler();
        $this->assertTrue(
            $handler->removeFieldDefinition( $type->id, $type->status, $type->fieldDefinitions[1]->id )
        );

        $type = $handler->load( $type->id, $type->status );
        $this->assertEquals( 1, count( $type->fieldDefinitions ) );
    }

    /**
     * @depends testCreate
     */
    public function testUpdateFieldDefinition( $type )
    {
        $fieldDefinition = $type->fieldDefinitions[0];
        $fieldDefinition->defaultValue = new Persistence\Content\FieldValue( array(
            "data" => "Hello Earth"
        ) );

        $handler = $this->getHandler();
        $this->assertTrue(
            $handler->updateFieldDefinition( $type->id, $type->status, $fieldDefinition )
        );
    }

    /**
     * @depends testCreate
     */
    public function testCreateDraft( $type )
    {
        $handler = $this->getHandler();
        $typeDraft = $handler->createDraft(
            $this->getUser()->id,
            $type->id
        );

        $this->assertInstanceOf(
            'eZ\\Publish\\SPI\\Persistence\\Content\\Type',
            $typeDraft
        );

        $this->assertEquals(
            $type->remoteId,
            $typeDraft->remoteId
        );

        return $typeDraft;
    }

    /**
     * @depends testCreateDraft
     */
    public function testPublish( $type )
    {
        $handler = $this->getHandler();
        $handler->publish( $type->id );

        $loadedType = $handler->load( $type->id );

        $this->assertEquals(
            Persistence\Content\Type::STATUS_DEFINED,
            $loadedType->status
        );

        return $loadedType;
    }

    /**
     * @depends testCreateDraft
     */
    public function testPublishedTypeHasFieldDefinitions( $type )
    {
        $this->assertCount( 1, $type->fieldDefinitions );

        $this->assertEquals(
            'title',
            $type->fieldDefinitions[0]->identifier
        );
    }

    /**
     * @depends testPublish
     */
    public function testDelete( $type )
    {
        $handler = $this->getHandler();
        $this->assertTrue(
            $handler->delete( $type->id, $type->status )
        );

        return $type;
    }

    /**
     * @depends testDelete
     * @expectedException \eZ\Publish\Core\Base\Exceptions\NotFoundException
     */
    public function testDeleteThrowsNotFoundException( $type )
    {
        $handler = $this->getHandler();
        $this->assertFalse(
            $handler->delete( $type->id, $type->status )
        );
    }

    /**
     * @return void
     * @expectedException \eZ\Publish\Core\Base\Exceptions\BadStateException
     */
    public function testDeleteThrowsBadStateException()
    {
        $this->getContent();
        $type = $this->getContentType();

        $handler = $this->getHandler();
        $handler->delete( $type->id, $type->status );
    }
}
