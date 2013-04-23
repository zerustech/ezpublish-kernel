<?php
/**
 * File contains: eZ\Publish\Core\Persistence\SqlNg\Tests\Content\Section\SectionHandlerTest class
 *
 * @copyright Copyright (C) 1999-2012 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 */

namespace eZ\Publish\Core\Persistence\SqlNg\Tests\Content\Section;

use eZ\Publish\Core\Persistence\SqlNg\Tests\TestCase;
use eZ\Publish\SPI\Persistence;
use eZ\Publish\Core\Persistence\SqlNg;

/**
 * Test case for Section Handler
 */
class SectionHandlerTest extends TestCase
{
    /**
     * Returns the section handler to test
     *
     * @return \eZ\Publish\Core\Persistence\SqlNg\Content\Section\Handler
     */
    protected function getSectionHandler()
    {
        return $this->getPersistenceHandler()->sectionHandler();
    }

    public function testCtor()
    {
        $handler = $this->getSectionHandler();
        $this->assertInstanceOf(
            'eZ\\Publish\\Core\\Persistence\\SqlNg\\Content\\Section\\Handler',
            $handler
        );
    }

    public function testCreate()
    {
        $handler = $this->getSectionHandler();

        $section = $handler->create( 'Test Section', 'testsection' );

        $this->assertInstanceOf(
            'eZ\\Publish\\SPI\\Persistence\\Content\\Section',
            $section,
            'Section not created'
        );
        $this->assertNotNull( $section->id );
        return $section;
    }

    /**
     * @depends testCreate
     */
    public function testUpdate( $section )
    {
        $handler = $this->getSectionHandler();

        $section = $handler->update(
            $section->id,
            'Updated Section',
            'updated'
        );

        $this->assertEquals(
            'updated',
            $section->identifier
        );

        return $section;
    }

    /**
     * @expectedException eZ\Publish\Core\Base\Exceptions\NotFoundException
     */
    public function testUpdateNonExisting()
    {
        $handler = $this->getSectionHandler();

        $handler->update( 1337, '', '' );
    }

    /**
     * @depends testUpdate
     */
    public function testLoad( $section )
    {
        $handler = $this->getSectionHandler();

        $loaded = $handler->load( $section->id );

        $this->assertEquals(
            $section,
            $loaded
        );
    }

    /**
     * @expectedException eZ\Publish\Core\Base\Exceptions\NotFoundException
     */
    public function testLoadNotFound()
    {
        $handler = $this->getSectionHandler();

        $handler->load( 1337 );
    }

    /**
     * @depends testUpdate
     */
    public function testLoadAll( $section )
    {
        $handler = $this->getSectionHandler();

        $loaded = $handler->loadAll();

        $this->assertEquals(
            array( $section ),
            $loaded
        );
    }

    /**
     * @depends testUpdate
     */
    public function testLoadByIdentifier( $section )
    {
        $handler = $this->getSectionHandler();

        $loaded = $handler->loadByIdentifier( $section->identifier );

        $this->assertEquals(
            $section,
            $loaded
        );
    }

    /**
     * @expectedException eZ\Publish\Core\Base\Exceptions\NotFoundException
     */
    public function testLoadByIdentifierNotFound()
    {
        $handler = $this->getSectionHandler();

        $handler->loadByIdentifier( 'not_existing' );
    }

    /**
     * @depends testCreate
     */
    public function testDelete( $section )
    {
        $handler = $this->getSectionHandler();

        $handler->delete( $section->id );

        return $section;
    }

    /**
     * @depends testDelete
     * @expectedException eZ\Publish\Core\Base\Exceptions\NotFoundException
     */
    public function testDeleteNotFound( $section )
    {
        $handler = $this->getSectionHandler();

        $handler->delete( $section->id );
    }

    /**
     * @depends testCreate
     */
    public function testAssign()
    {
        $handler = $this->getSectionHandler();

        $section = $this->getSection();
        $content = $this->getContent();
        $handler->assign( $section->id, $content->versionInfo->contentInfo->id );

        return $section;
    }

    /**
     * @depends testAssign
     */
    public function testAssignCount( $section )
    {
        $handler = $this->getSectionHandler();

        $this->assertEquals(
            1,
            $handler->assignmentsCount( $section->id )
        );
    }

    /**
     * @depends testAssign
     * @expectedException eZ\Publish\Core\Base\Exceptions\BadStateException
     */
    public function testDeleteBadState( $section )
    {
        $handler = $this->getSectionHandler();

        $handler->delete( $section->id );
    }
}
