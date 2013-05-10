<?php
/**
 * File contains: eZ\Publish\Core\Persistence\SqlNg\Tests\Content\LocationHandlerTest class
 *
 * @copyright Copyright (C) 1999-2012 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 */

namespace eZ\Publish\Core\Persistence\SqlNg\Tests\Content;

use eZ\Publish\Core\Persistence\SqlNg\Tests\TestCase;
use eZ\Publish\SPI\Persistence;

/**
 * Test case for UrlWildcard Handler
 */
class UrlWildcardHandlerTest extends TestCase
{
    /**
     * Returns the handler to test
     *
     * @return \eZ\Publish\Core\Persistence\SqlNg\Content\UrlWildcard\Handler
     */
    protected function getUrlWildcardHandler()
    {
        return $this->getPersistenceHandler()->urlWildcardHandler();
    }

    public function testCreate()
    {
        $handler = $this->getUrlWildcardHandler();

        $urlWildcard = $handler->create(
            '/foo',
            '/bar',
            true
        );

        $this->assertInstanceOf(
            'eZ\\Publish\\SPI\\Persistence\\Content\\UrlWildcard',
            $urlWildcard
        );

        return $urlWildcard;
    }

    /**
     * @depends testCreate
     */
    public function testLoad( $urlWildcard )
    {
        $handler = $this->getUrlWildcardHandler();

        $loaded = $handler->load( $urlWildcard->id );

        $this->assertEquals( $urlWildcard, $loaded );
    }

    /**
     * @expectedException \eZ\Publish\API\Repository\Exceptions\NotFoundException
     */
    public function testLoadThrowsNotFoundException()
    {
        $handler = $this->getUrlWildcardHandler();
        $handler->load( PHP_INT_MAX );
    }

    /**
     * @depends testCreate
     */
    public function testLoadAlls( $urlWildcard )
    {
        $handler = $this->getUrlWildcardHandler();

        $result = $handler->loadAll();

        $this->assertEquals(
            array( $urlWildcard ),
            $result
        );
    }

    /**
     * @depends testCreate
     */
    public function testDelete( $urlWildcard )
    {
        $handler = $this->getUrlWildcardHandler();

        $handler->remove( $urlWildcard->id );

        $this->assertEquals(array(), $handler->loadAll() );
    }

    /**
     * @expectedException \eZ\Publish\API\Repository\Exceptions\NotFoundException
     */
    public function testDeleteThrowsNotFoundException()
    {
        $handler = $this->getUrlWildcardHandler();

        $handler->remove( PHP_INT_MAX );
    }
}
