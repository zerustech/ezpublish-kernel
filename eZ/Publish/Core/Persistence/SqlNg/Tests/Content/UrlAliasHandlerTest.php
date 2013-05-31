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
 * Test case for UrlAlias Handler
 */
class UrlAliasHandlerTest extends TestCase
{
    /**
     * Method called on database initialization before each test case
     *
     * @return void
     */
    protected function applyCustomStatements()
    {
        $this->applyStatements(
            $this->getStatements(
                __DIR__ . '/../_fixture/initial_data.' . self::$db . '.sql'
            )
        );
    }

    /**
     * Returns the handler to test
     *
     * @return \eZ\Publish\Core\Persistence\SqlNg\Content\UrlAlias\Handler
     */
    protected function getUrlAliasHandler()
    {
        return $this->getPersistenceHandler()->urlAliasHandler();
    }

    public function testCreateCustomUrlAlias()
    {
        $handler = $this->getUrlAliasHandler();

        $location = $this->getLocation();

        $urlAlias = $handler->createCustomUrlAlias(
            $location->id,
            '/custom'
        );

        $this->assertInstanceOf(
            'eZ\\Publish\\SPI\\Persistence\\Content\\UrlAlias',
            $urlAlias
        );

        return $urlAlias;
    }

    /**
     * @depends testCreateCustomUrlAlias
     */
    public function testLoadCustomUrlAlias( $urlAlias )
    {
        $handler = $this->getUrlAliasHandler();

        $loaded = $handler->loadUrlAlias( $urlAlias->id );

        $this->assertEquals( $urlAlias, $loaded );
    }

    /**
     * @depends testCreateCustomUrlAlias
     */
    public function testListCustomUrlAlias( $urlAlias )
    {
        $handler = $this->getUrlAliasHandler();

        $loaded = $handler->listURLAliasesForLocation( $urlAlias->destination, true );

        $this->assertEquals( array( $urlAlias ), $loaded );
    }

    /**
     * @expectedException \eZ\Publish\API\Repository\Exceptions\NotFoundException
     */
    public function testLoadCustomUrlAliasThrowsNotFoundException()
    {
        $handler = $this->getUrlAliasHandler();
        $handler->loadUrlAlias( PHP_INT_MAX );
    }

    /**
     * @depends testCreateCustomUrlAlias
     * @expectedException \eZ\Publish\API\Repository\Exceptions\NotFoundException
     */
    public function testDeleteCustomUrlAlias( $urlAlias )
    {
        $handler = $this->getUrlAliasHandler();

        $handler->removeUrlAliases( array( $urlAlias ) );

        $this->assertEquals(array(), $handler->loadUrlAlias( $urlAlias->id ) );
    }

    /**
     * @expectedException \eZ\Publish\API\Repository\Exceptions\NotFoundException
     */
    public function testDeleteCustomUrlAliasThrowsNotFoundException()
    {
        $handler = $this->getUrlAliasHandler();

        $handler->removeUrlAliases( array(
            new Persistence\Content\UrlAlias( array( 'id' => PHP_INT_MAX ) )
        ) );
    }

    public function testCreateCustomUrlAliasWithForward()
    {
        $handler = $this->getUrlAliasHandler();

        $location = $this->getLocation();

        $urlAlias = $handler->createCustomUrlAlias(
            $location->id,
            '/custom/forwarded',
            true
        );

        $this->assertInstanceOf(
            'eZ\\Publish\\SPI\\Persistence\\Content\\UrlAlias',
            $urlAlias
        );

        return $urlAlias;
    }

    /**
     * @depends testCreateCustomUrlAliasWithForward
     */
    public function testLoadCustomUrlAliasWithForward( $urlAlias )
    {
        $handler = $this->getUrlAliasHandler();

        $loaded = $handler->loadUrlAlias( $urlAlias->id );

        $this->assertEquals( $urlAlias, $loaded );
    }

    public function testCreateCustomUrlAliasAlwaysAvailable()
    {
        $handler = $this->getUrlAliasHandler();

        $location = $this->getLocation();
        $content  = $this->getPersistenceHandler()->contentHandler()->loadContentInfo( $location->contentId );

        $urlAlias = $handler->createCustomUrlAlias(
            $location->id,
            '/custom/always-available',
            false,
            $content->mainLanguageCode,
            true
        );

        $this->assertInstanceOf(
            'eZ\\Publish\\SPI\\Persistence\\Content\\UrlAlias',
            $urlAlias
        );

        return $urlAlias;
    }

    /**
     * @depends testCreateCustomUrlAliasAlwaysAvailable
     */
    public function testLoadCustomUrlAliasAlwaysAvailable( $urlAlias )
    {
        $handler = $this->getUrlAliasHandler();

        $loaded = $handler->loadUrlAlias( $urlAlias->id );

        $this->assertEquals( $urlAlias, $loaded );
    }

    public function testCreateGlobalUrlAlias()
    {
        $handler = $this->getUrlAliasHandler();

        $urlAlias = $handler->createGlobalUrlAlias(
            'module:content/search?SearchText=eZ',
            '/global'
        );

        $this->assertInstanceOf(
            'eZ\\Publish\\SPI\\Persistence\\Content\\UrlAlias',
            $urlAlias
        );

        return $urlAlias;
    }

    /**
     * @depends testCreateGlobalUrlAlias
     */
    public function testLoadGlobalUrlAlias( $urlAlias )
    {
        $handler = $this->getUrlAliasHandler();

        $loaded = $handler->loadUrlAlias( $urlAlias->id );

        $this->assertEquals( $urlAlias, $loaded );
    }

    /**
     * @depends testCreateGlobalUrlAlias
     */
    public function testListGlobalUrlAlias( $urlAlias )
    {
        $handler = $this->getUrlAliasHandler();

        $loaded = $handler->listGlobalUrlAliases();

        $this->assertEquals( array( $urlAlias ), $loaded );
    }

    /**
     * @expectedException \eZ\Publish\API\Repository\Exceptions\NotFoundException
     */
    public function testLoadGlobalUrlAliasThrowsNotFoundException()
    {
        $handler = $this->getUrlAliasHandler();
        $handler->loadUrlAlias( PHP_INT_MAX );
    }

    public function testCreateGlobalUrlAliasWithForward()
    {
        $handler = $this->getUrlAliasHandler();

        $urlAlias = $handler->createGlobalUrlAlias(
            'module:content/search?SearchText=eZForward',
            '/global/forward',
            true
        );

        $this->assertInstanceOf(
            'eZ\\Publish\\SPI\\Persistence\\Content\\UrlAlias',
            $urlAlias
        );

        return $urlAlias;
    }

    /**
     * @depends testCreateGlobalUrlAliasWithForward
     */
    public function testLoadGlobalUrlAliasWithForward( $urlAlias )
    {
        $handler = $this->getUrlAliasHandler();

        $loaded = $handler->loadUrlAlias( $urlAlias->id );

        $this->assertEquals( $urlAlias, $loaded );
    }

    public function testCreateGlobalUrlAliasAlwaysAvailable()
    {
        $handler = $this->getUrlAliasHandler();

        $location = $this->getLocation();
        $content  = $this->getPersistenceHandler()->contentHandler()->loadContentInfo( $location->contentId );

        $urlAlias = $handler->createGlobalUrlAlias(
            'module:content/search?SearchText=eZAlwaysAvailable',
            '/global/always-available',
            true,
            $content->mainLanguageCode,
            true
        );

        $this->assertInstanceOf(
            'eZ\\Publish\\SPI\\Persistence\\Content\\UrlAlias',
            $urlAlias
        );

        return $urlAlias;
    }

    /**
     * @depends testCreateGlobalUrlAliasAlwaysAvailable
     */
    public function testLoadGlobalUrlAliasAlwaysAvailable( $urlAlias )
    {
        $handler = $this->getUrlAliasHandler();

        $loaded = $handler->loadUrlAlias( $urlAlias->id );

        $this->assertEquals( $urlAlias, $loaded );
    }

    public function testLookup()
    {
        $this->markTestIncomplete( "@TODO: Write tests for this." );
    }

    public function testPublishUrlAliasForLocation()
    {
        $this->markTestIncomplete( "@TODO: Write tests for this." );
    }

    public function testLocationMoved()
    {
        $this->markTestIncomplete( "@TODO: Write tests for this." );
    }

    public function testLocationCopied()
    {
        $this->markTestIncomplete( "@TODO: Write tests for this." );
    }

    public function testLocationDeleted()
    {
        $this->markTestIncomplete( "@TODO: Write tests for this." );
    }
}
