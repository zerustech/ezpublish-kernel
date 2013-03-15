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
use eZ\Publish\API\Repository;

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

    public function testCreateRoot()
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
                    'parentId' => null,
                ) )
            ),
            'fields' => array(),
            'name' => array(
                $this->getLanguage()->languageCode => "Test-Objekt",
            ),
        ) );

        foreach ( $contentType->fieldDefinitions as $fieldDefinition )
        {
            $createStruct->fields[] = new Persistence\Content\Field( array(
                'fieldDefinitionId' => $fieldDefinition->id,
                'type' => $fieldDefinition->fieldType,
                'value' => new Persistence\Content\FieldValue( array(
                    "data" => "Hello World"
                ) ),
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
     * @depends testCreateRoot
     */
    public function testLoadRoot( $content )
    {
        $handler = $this->getContentHandler();

        $loaded = $handler->load(
            $content->versionInfo->contentInfo->id,
            $content->versionInfo->versionNo
        );

        $this->assertEquals(
            $content,
            $loaded
        );
    }

    /**
     * @depends testCreateRoot
     */
    public function testPublishRoot( $content )
    {
        $handler = $this->getContentHandler();

        $content = $handler->publish(
            $content->versionInfo->contentInfo->id,
            $content->versionInfo->versionNo,
            new Persistence\Content\MetadataUpdateStruct( array(
                'ownerId' => $this->getUser()->id,
                'publicationDate' => 123456,
                'modificationDate' => 123456,
                'mainLanguageId' => $this->getLanguage()->id,
                'alwaysAvailable' => true,
                'remoteId' => 'updated',
            ) )
        );

        return $content;
    }

    /**
     * @depends testPublishRoot
     */
    public function testLoadPublishedVersion( $content )
    {
        $handler = $this->getContentHandler();

        $loaded = $handler->load(
            $content->versionInfo->contentInfo->id,
            $content->versionInfo->versionNo
        );

        $this->assertEquals(
            $content,
            $loaded
        );
    }

    // @TODO:
    // - Test create multilang content object
    // - Test loading modified content types object

    /**
     * @depends testPublishRoot
     */
    public function testCreateChildContent( $parent )
    {
        $handler = $this->getContentHandler();

        $contentType = $this->getContentType();

        $createStruct = new Persistence\Content\CreateStruct( array(
            'typeId' => $contentType->id,
            'sectionId' => $this->getSection()->id,
            'ownerId' => $this->getUser()->id,
            'alwaysAvailable' => true,
            'remoteId' => 'testobject-child',
            'initialLanguageId' => $this->getLanguage()->id,
            'modified' => 123456789,
            'locations' => array(
                new Persistence\Content\Location\CreateStruct( array(
                    'remoteId' => 'testobject-child-location',
                    'parentId' => $parent->versionInfo->contentInfo->id,
                ) )
            ),
            'fields' => array(),
            'name' => array(
                $this->getLanguage()->languageCode => "Kind-Objekt",
            ),
        ) );

        foreach ( $contentType->fieldDefinitions as $fieldDefinition )
        {
            $createStruct->fields[] = new Persistence\Content\Field( array(
                'fieldDefinitionId' => $fieldDefinition->id,
                'type' => $fieldDefinition->fieldType,
                'value' => new Persistence\Content\FieldValue( array(
                    "data" => "Hello World"
                ) ),
                'languageCode' => $this->getLanguage()->languageCode,
            ) );
        }

        $child = $handler->create( $createStruct );

        $this->assertInstanceOf(
            'eZ\\Publish\\SPI\\Persistence\\Content',
            $child,
            'Content not created'
        );

        $child = $handler->publish(
            $child->versionInfo->contentInfo->id,
            $child->versionInfo->versionNo,
            new Persistence\Content\MetadataUpdateStruct( array(
                'ownerId' => $this->getUser()->id,
                'publicationDate' => 123456,
                'modificationDate' => 123456,
                'mainLanguageId' => $this->getLanguage()->id,
                'alwaysAvailable' => true,
                'remoteId' => 'updated-child',
            ) )
        );

        return $child;
    }

    /**
     * @depends testPublishRoot
     */
    public function testCreateDraftFromVersion( $content )
    {
        $handler = $this->getContentHandler();

        $draft = $handler->createDraftFromVersion(
            $content->versionInfo->contentInfo->id,
            $content->versionInfo->versionNo,
            $this->getUser()->id
        );

        $this->assertInstanceOf(
            'eZ\\Publish\\SPI\\Persistence\\Content',
            $draft
        );
        $this->assertEquals(
            2,
            $draft->versionInfo->versionNo
        );

        return $draft;
    }

    /**
     * @expectedException eZ\Publish\Core\Base\Exceptions\NotFoundException
     */
    public function testLoadErrorNotFound()
    {
        $handler = $this->getContentHandler();

        $handler->load( 1337, 2 );
    }

    /**
     * @depends testPublishRoot
     */
    public function testUpdateContent( $content )
    {
        $handler = $this->getContentHandler();

        $updateStruct = new Persistence\Content\UpdateStruct( array(
            'creatorId' => $this->getUser()->id,
            'modificationDate' => 12345467890,
            'initialLanguageId' => $this->getLanguage()->id,
            'fields' => array(),
        ) );

        $contentType = $this->getContentType();
        foreach ( $contentType->fieldDefinitions as $fieldDefinition )
        {
            $updateStruct->fields[] = new Persistence\Content\Field( array(
                'fieldDefinitionId' => $fieldDefinition->id,
                'type' => $fieldDefinition->fieldType,
                'value' => new Persistence\Content\FieldValue( array(
                    "data" => "Updated"
                ) ),
                'languageCode' => $this->getLanguage()->languageCode,
                'versionNo' => $content->versionInfo->versionNo,
            ) );
        }

        $updatedContent = $handler->updateContent(
            $content->versionInfo->contentInfo->id,
            $content->versionInfo->versionNo,
            $updateStruct
        );
    }

    /**
     * @depends testPublishRoot
     */
    public function testUpdateMetadata( $content )
    {
        $handler = $this->getContentHandler();

        $updatedContentInfo = $handler->updateMetadata(
            $content->versionInfo->contentInfo->id,
            new Persistence\Content\MetadataUpdateStruct( array(
                'ownerId' => $this->getUser()->id,
                'publicationDate' => 123456,
                'modificationDate' => 123456,
                'mainLanguageId' => $this->getLanguage()->id,
                'alwaysAvailable' => true,
                'remoteId' => 'updated',
            ) )
        );

        $this->assertInstanceOf(
            'eZ\\Publish\\SPI\\Persistence\\Content\\ContentInfo',
            $updatedContentInfo
        );
    }

    /**
     * @depends testPublishRoot
     */
    public function testAddRelation( $content )
    {
        $handler = $this->getContentHandler();

        $relation = $handler->addRelation(
            new Persistence\Content\Relation\CreateStruct( array(
                'destinationContentId' => 2, // Child
                'sourceContentId' => $content->versionInfo->contentInfo->id,
                'sourceContentVersionNo' => $content->versionInfo->versionNo,
                'type' => Repository\Values\Content\Relation::COMMON,
            ) )
        );

        $this->assertInstanceOf(
            'eZ\\Publish\\SPI\\Persistence\\Content\\Relation',
            $relation
        );

        return $relation;
    }

    /**
     * @depends testAddRelation
     */
    public function testLoadRelations( $relation )
    {
        $handler = $this->getContentHandler();

        $loaded = $handler->loadRelations( $relation->sourceContentId );

        $this->assertEquals(
            array( $relation ),
            $loaded
        );
    }

    /**
     * @depends testAddRelation
     */
    public function testLoadReverseRelations( $relation )
    {
        $handler = $this->getContentHandler();

        $loaded = $handler->loadReverseRelations( $relation->destinationContentId );

        $this->assertEquals(
            array( $relation ),
            $loaded
        );
    }

    /**
     * @depends testAddRelation
     */
    public function testRemoveRelation( $relation )
    {
        $handler = $this->getContentHandler();

        $this->getContentHandler()->removeRelation( $relation->id, $relation->type );

        return $relation;
    }

    /**
     * @depends testRemoveRelation
     * @expectedException eZ\Publish\Core\Base\Exceptions\NotFoundException
     */
    public function testRemoveNonExistingRelation( $relation )
    {
        $handler = $this->getContentHandler();

        $this->getContentHandler()->removeRelation( $relation->id, $relation->type );
    }

    /**
     * @depends testCreateDraftFromVersion
     */
    public function testLoadDraftsForUser( $draft )
    {
        $handler = $this->getContentHandler();

        $loaded = $handler->loadDraftsForUser( $this->getUser()->id );

        $this->assertEquals(
            array( $draft->versionInfo ),
            $loaded
        );
    }

    /**
     * @depends testPublishRoot
     */
    public function testListVersions( $content )
    {
        $handler = $this->getContentHandler();

        $versions = $handler->listVersions( $content->versionInfo->contentInfo->id );

        $this->assertEquals( 2, count( $versions ) );
    }

    /**
     * @depends testPublishRoot
     * @expectedException RuntimeException
     */
    public function testRemoveRawContentFail( $content )
    {
        $handler = $this->getContentHandler();

        $handler->removeRawContent( $content->versionInfo->contentInfo->id );
    }

    /**
     * @depends testCreateChildContent
     */
    public function testRemoveRawContentChild( $child )
    {
        $handler = $this->getContentHandler();

        $handler->removeRawContent( $child->versionInfo->contentInfo->id );

        return $child;
    }

    /**
     * @depends testRemoveRawContentChild
     * @expectedException eZ\Publish\Core\Base\Exceptions\NotFoundException
     */
    public function testRemoveRawContentChildNotFound( $child )
    {
        $handler = $this->getContentHandler();

        $handler->removeRawContent( $child->versionInfo->contentInfo->id );
    }

    /**
     * @depends testPublishRoot
     */
    public function testDeleteContentWithLocations()
    {
        $this->markTestIncomplete( "This is more a location handler test … thus pending." );
        $handler = $this->getContentHandler();

        $handler->deleteContent( $content->versionInfo->contentInfo->id );
    }

    /**
     * @depends testPublishRoot
     */
    public function testDeleteContentWithoutLocations()
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
            'locations' => array(),
            'fields' => array(),
        ) );

        foreach ( $contentType->fieldDefinitions as $fieldDefinition )
        {
            $createStruct->fields[] = new Persistence\Content\Field( array(
                'fieldDefinitionId' => $fieldDefinition->id,
                'type' => $fieldDefinition->fieldType,
                'value' => new Persistence\Content\FieldValue( array(
                    "data" => "Hello World"
                ) ),
                'languageCode' => $this->getLanguage()->languageCode,
            ) );
        }

        $content = $handler->create( $createStruct );

        $handler->deleteContent( $content->versionInfo->contentInfo->id );
    }

    /**
     * @depends testCreateDraftFromVersion
     */
    public function testDeleteVersion( $draft )
    {
        $handler = $this->getContentHandler();

        $handler->deleteVersion(
            $draft->versionInfo->contentInfo->id,
            $draft->versionInfo->versionNo
        );

        return $draft;
    }

    /**
     * @depends testDeleteVersion
     * @expectedException eZ\Publish\Core\Base\Exceptions\NotFoundException
     */
    public function testDeleteVersionNotFound( $draft )
    {
        $handler = $this->getContentHandler();

        $handler->deleteVersion(
            $draft->versionInfo->contentInfo->id,
            $draft->versionInfo->versionNo
        );
    }

    /**
     * @depends testPublishRoot
     */
    public function testCopySingleVersion( $content )
    {
        $handler = $this->getContentHandler();

        $copy = $handler->copy(
            $content->versionInfo->contentInfo->id,
            $content->versionInfo->versionNo
        );

        $this->assertInstanceOf(
            "eZ\\Publish\\SPI\\Persistence\\Content",
            $copy
        );
    }

    /**
     * @depends testPublishRoot
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
     * @expectedException eZ\Publish\Core\Base\Exceptions\NotFoundException
     */
    public function testCopyThrowsNotFoundExceptionContentNotFound()
    {
        $handler = $this->getContentHandler();
        $handler->copy( 1337 );
    }

    /**
     * @expectedException eZ\Publish\Core\Base\Exceptions\NotFoundException
     */
    public function testCopyThrowsNotFoundExceptionVersionNotFound()
    {
        $handler = $this->getContentHandler();
        $handler->copy( 1337, 1 );
    }

    /**
     * @depends testPublishRoot
     */
    public function testSetStatus( $content )
    {
        $handler = $this->getContentHandler();

        $this->assertTrue(
            $handler->setStatus(
                $content->versionInfo->contentInfo->id,
                5,
                $content->versionInfo->versionNo
            )
        );
    }
}
