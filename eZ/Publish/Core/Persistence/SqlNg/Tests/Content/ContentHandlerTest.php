<?php
/**
 * File contains: eZ\Publish\Core\Persistence\SqlNg\Tests\Content\ContentHandlerTest class
 *
 * @copyright Copyright (C) 1999-2012 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 */

namespace eZ\Publish\Core\Persistence\SqlNg\Tests\Content;

use eZ\Publish\Core\Persistence\SqlNg\Tests\TestCase;

use eZ\Publish\SPI\Persistence;
use eZ\Publish\Core\Persistence\SqlNg;

/**
 * Test case for Content Handler
 */
class ContentHandlerTest extends TestCase
{
    /**
     * Returns the handler to test
     *
     * @return \eZ\Publish\Core\Persistence\SqlNg\Content\Handler
     */
    protected function getContentHandler()
    {
        return $this->getPersistenceHandler()->contentHandler();
    }

    public function testCtor()
    {
        $handler = $this->getContentHandler();
        $this->assertInstanceOf(
            'eZ\\Publish\\Core\\Persistence\\SqlNg\\Content\\Handler',
            $handler
        );
    }

    public function testCreate()
    {
        $handler = $this->getContentHandler();

        $contentType = $this->getContentType();

        $createStruct = new Persistence\Content\CreateStruct( array(
            'typeId' => $contentType->id,
            'sectionId' => $this->getSection()->id,
            'ownerId' => $this->getUser()->id,
            'alwaysAvailable' => true,
            'remoteId' => 'testobject',
            'initialLanguageId' => $this->getLanguage()->id,
            'modified' => 123456789,
            'locations' => array(
                new Persistence\Content\Location\CreateStruct( array(
                    'remoteId' => 'testobject-location',
                    'parentId' => 1,
                ) )
            ),
            'fields' => array(),
        ) );

        foreach ( $contentType->fieldDefinitions as $fieldDefinition )
        {
            $createStruct->fields[] = new Persistence\Content\Field( array(
                'fieldDefinitionId' => $fieldDefinition->id,
                'type' => $fieldDefinition->fieldType,
                'value' => 'Hello World!',
                'languageCode' => $this->getLanguage()->languageCode,
            ) );
        }

        $content = $handler->create( $createStruct );

        $this->assertInstanceOf(
            'eZ\\Publish\\SPI\\Persistence\\Content',
            $content,
            'Content not created'
        );
        $this->assertInstanceOf(
            '\\eZ\\Publish\\SPI\\Persistence\\Content\\VersionInfo',
            $content->versionInfo,
            'Version infos not created'
        );
        $this->assertNotNull( $content->versionInfo->id );
        $this->assertNotNull( $content->versionInfo->contentInfo->id );
        $this->assertEquals(
            2,
            count( $content->fields ),
            'Fields not set correctly in version'
        );

        return $content;
    }

    /**
     * @depends testCreate
     */
    public function testPublishFirstVersion( $content )
    {
        $handler = $this->getContentHandler();

        $content = $handler->publish(
            $content->versionInfo->contentInfo->id,
            $content->versionInfo->versionNo,
            new Persistence\Content\MetadataUpdateStruct( array(
            ) )
        );

        return $content;
    }

    /**
     * @depends testPublishFirstVersion
     */
    public function testPublishPublishedVersion( $content )
    {
        $handler = $this->getContentHandler();

        $metadataUpdateStruct = new MetadataUpdateStruct();
        $content = $handler->publish( $content->versionInfo->contentInfo->id, $content->versionInfo->versionNo, $metadataUpdateStruct );

        return $content;
    }

    /**
     * @depends testPublishFirstVersion
     */
    public function testCreateDraftFromVersion( $content )
    {
        $handler = $this->getContentHandler();

        $draft = $handler->createDraftFromVersion( $content->versionInfo->contentInfo->id, 2, 14 );

        $this->assertInstanceOf(
            'eZ\\Publish\\SPI\\Persistence\\Content',
            $draft
        );
        $this->assertEquals(
            42,
            $draft->versionInfo->id
        );
    }

    /**
     * @depends testPublishFirstVersion
     */
    public function testLoad( $content )
    {
        $handler = $this->getContentHandler();

        $loaded = $handler->load( $content->versionInfo->contentInfo->id, 2, array( 'eng-GB' ) );

        $this->assertEquals(
            $content,
            $loaded
        );
    }

    public function testLoadErrorNotFound()
    {
        $handler = $this->getContentHandler();

        $handler->load( 1337, 2, array( 'eng-GB' ) );
    }

    /**
     * @depends testPublishFirstVersion
     */
    public function testUpdateContent( $content )
    {
        $handler = $this->getContentHandler();

        $updatedContent = $handler->updateContent(
            14, // ContentId
            4, // VersionNo
            new UpdateStruct( array(
                'creatorId' => 14,
                'modificationDate' => time(),
                'initialLanguageId' => 2,
                'fields' => array(
                    new Field( array(
                        'id' => $content->versionInfo->contentInfo->id,
                        'fieldDefinitionId' => 42,
                        'type' => 'some-type',
                        'value' => new FieldValue(),
                    ) ),
                    new Field( array(
                        'id' => $content->versionInfo->contentInfo->id,
                        'fieldDefinitionId' => 43,
                        'type' => 'some-type',
                        'value' => new FieldValue(),
                    ) ),
                )
            ) )
        );

        // @TODO: Assertions
    }

    /**
     * @depends testPublishFirstVersion
     */
    public function testUpdateMetadata( $content )
    {
        $handler = $this->getContentHandler();

        $updatedContentInfo = $handler->updateMetadata(
            14, // ContentId
            new MetadataUpdateStruct( array(
                'ownerId' => 14,
                'name' => 'Some name',
                'modificationDate' => time(),
                'alwaysAvailable' => true
            ) )
        );

        self::assertInstanceOf(
            'eZ\\Publish\\SPI\\Persistence\\Content\\ContentInfo',
            $updatedContentInfo
        );
    }

    /**
     * @depends testPublishFirstVersion
     */
    public function testAddRelation( $content )
    {
        $handler = $this->getContentHandler();

        $relation = $handler->addRelation(
            new Relation\CreateStruct( array(
                'destinationContentId' => 66,
                'sourceContentId' => $content->versionInfo->contentInfo->id,
                'sourceContentVersionNo' => 1,
                'type' => RelationValue::COMMON,
            ) )
        );

        $this->assertEquals(
            array(),
            $relation
        );

        return $content;
    }

    /**
     * @depends testAddRelation
     */
    public function testLoadRelations( $content )
    {
        $handler = $this->getContentHandler();

        $relations = $handler->loadRelations( $content->versionInfo->contentInfo->id );

        $this->assertEquals(
            array(),
            $relations
        );
    }

    /**
     * @depends testAddRelation
     */
    public function testLoadReverseRelations( $content )
    {
        $handler = $this->getContentHandler();

        $relations = $handler->loadReverseRelations( $content->versionInfo->contentInfo->id );

        $this->assertEquals(
            array(),
            $relations
        );
    }

    /**
     * @depends testAddRelation
     */
    public function testRemoveRelation( $content )
    {
        $handler = $this->getContentHandler();

        $this->getContentHandler()->removeRelation( 1 );
    }

    /**
     * @depends testPublishFirstVersion
     */
    public function testLoadDraftsForUser( $content )
    {
        $handler = $this->getContentHandler();

        $draft = $handler->loadDraftsForUser( $content->versionInfo->contentInfo->id );

        $this->assertEquals(
            array( new VersionInfo() ),
            $draft
        );
    }

    /**
     * @depends testPublishFirstVersion
     */
    public function testListVersions( $content )
    {
        $handler = $this->getContentHandler();

        $versions = $handler->listVersions( $content->versionInfo->contentInfo->id );

        $this->assertEquals(
            array( new VersionInfo() ),
            $versions
        );
    }

    /**
     * @depends testPublishFirstVersion
     */
    public function testRemoveRawContent( $content )
    {
        $handler = $this->getContentHandler();

        $handler->removeRawContent( $content->versionInfo->contentInfo->id );
    }

    /**
     * @depends testPublishFirstVersion
     */
    public function testDeleteContentWithLocations( $content )
    {
        $handler = $this->getContentHandler();

        $handlerMock->deleteContent( $content->versionInfo->contentInfo->id );
    }

    /**
     * @depends testPublishFirstVersion
     */
    public function testDeleteContentWithoutLocations( $content )
    {
        $handler = $this->getContentHandler();

        $handlerMock->deleteContent( $content->versionInfo->contentInfo->id );
    }

    /**
     * @depends testPublishFirstVersion
     */
    public function testDeleteVersion( $content )
    {
        $handler = $this->getContentHandler();

        $handler->deleteVersion( 225, 2 );
    }

    /**
     * @depends testPublishFirstVersion
     */
    public function testCopySingleVersion( $content )
    {
        $handler = $this->getContentHandler();

        $content = $handler->copy( $content->versionInfo->contentInfo->id, 32 );

        $this->assertInstanceOf(
            "eZ\\Publish\\SPI\\Persistence\\Content",
            $content
        );
    }

    /**
     * @depends testPublishFirstVersion
     */
    public function testCopyAllVersions( $content )
    {
        $handler = $this->getContentHandler();

        $content = $handler->copy( $content->versionInfo->contentInfo->id );

        $this->assertInstanceOf(
            "eZ\\Publish\\SPI\\Persistence\\Content",
            $content
        );
    }

    /**
     * @depends testPublishFirstVersion
     */
    public function testCopyThrowsNotFoundExceptionContentNotFound( $content )
    {
        $handler = $this->getContentHandler();

        $result = $handler->copy( $content->versionInfo->contentInfo->id );
    }

    /**
     * @depends testPublishFirstVersion
     */
    public function testCopyThrowsNotFoundExceptionVersionNotFound( $content )
    {
        $handler = $this->getContentHandler();

        $result = $handler->copy( $content->versionInfo->contentInfo->id, 32 );
    }

    /**
     * @depends testPublishFirstVersion
     */
    public function testSetStatus( $content )
    {
        $handler = $this->getContentHandler();

        $this->assertTrue(
            $handler->setStatus( $content->versionInfo->contentInfo->id, 2, 5 )
        );
    }
}
