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

        $location = $this->getPersistenceHandler()->locationHandler()->load( 10 );
        $content  = $this->getPersistenceHandler()->contentHandler()->loadContentInfo( $location->contentId );


        $urlAlias = $handler->createCustomUrlAlias(
            $location->id,
            '/some/path',
            false,
            $content->mainLanguageCode,
            false
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
     * @expectedException \eZ\Publish\API\Repository\Exceptions\NotFoundException
     */
    public function testLoadCustomUrlAliasThrowsNotFoundException()
    {
        $handler = $this->getUrlAliasHandler();
        $handler->loadUrlAlias( PHP_INT_MAX );
    }

    /**
     * @depends testCreateCustomUrlAlias
     */
    public function testDeleteCustomUrlAlias( $urlAlias )
    {
        $handler = $this->getUrlAliasHandler();

        $handler->removeUrlAliases( array( $urlAlias ) );

        $this->assertEquals(array(), $handler->loadAll() );
    }

    /**
     * @expectedException \eZ\Publish\API\Repository\Exceptions\NotFoundException
     */
    public function testDeleteCustomUrlAliasThrowsNotFoundException()
    {
        $handler = $this->getUrlAliasHandler();

        $handler->removeUrlAliases( array( PHP_INT_MAX ) );
    }
}
