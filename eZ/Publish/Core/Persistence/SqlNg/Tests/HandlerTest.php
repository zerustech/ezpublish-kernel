<?php
/**
 * File contains: eZ\Publish\Core\Persistence\SqlNg\Tests\HandlerTest class
 *
 * @copyright Copyright (C) 1999-2012 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 */

namespace eZ\Publish\Core\Persistence\SqlNg\Tests;

use eZ\Publish\Core\Persistence\SqlNg\Tests\TestCase;
use eZ\Publish\Core\Base\ConfigurationManager;
use eZ\Publish\Core\Base\ServiceContainer;
use eZ\Publish\Core\Persistence\SqlNg\Handler;

/**
 * Test case for Repository Handler
 */
class HandlerTest extends TestCase
{
    /**
     * @covers eZ\Publish\Core\Persistence\SqlNg\Handler::contentHandler
     *
     * @return void
     */
    public function testContentHandler()
    {
        $handler = $this->getPersistenceHandler();
        $contentHandler = $handler->contentHandler();

        $this->assertInstanceOf(
            'eZ\\Publish\\SPI\\Persistence\\Content\\Handler',
            $contentHandler
        );
        $this->assertInstanceOf(
            'eZ\\Publish\\Core\\Persistence\\SqlNg\\Content\\Handler',
            $contentHandler
        );
    }

    /**
     * @covers eZ\Publish\Core\Persistence\SqlNg\Handler::contentHandler
     *
     * @return void
     */
    public function testContentHandlerTwice()
    {
        $handler = $this->getPersistenceHandler();

        $this->assertSame(
            $handler->contentHandler(),
            $handler->contentHandler()
        );
    }

    /**
     * @covers eZ\Publish\Core\Persistence\SqlNg\Handler::getStorageRegistry
     *
     * @return void
     */
    public function testGetStorageRegistry()
    {
        $handler = $this->getPersistenceHandler();
        $registry = $handler->getStorageRegistry();

        $this->assertInstanceOf(
            'eZ\\Publish\\Core\\Persistence\\SqlNg\\Content\\StorageRegistry',
            $registry
        );
    }

    /**
     * @covers eZ\Publish\Core\Persistence\SqlNg\Handler::getStorageRegistry
     *
     * @return void
     */
    public function testGetStorageRegistryTwice()
    {
        $handler = $this->getPersistenceHandler();

        $this->assertSame(
            $handler->getStorageRegistry(),
            $handler->getStorageRegistry()
        );
    }

    /**
     * @covers eZ\Publish\Core\Persistence\SqlNg\Handler::searchHandler
     *
     * @return void
     */
    public function testSearchHandler()
    {
        $handler = $this->getPersistenceHandler();
        $searchHandler = $handler->searchHandler();

        $this->assertInstanceOf(
            'eZ\\Publish\\SPI\\Persistence\\Content\\Search\\Handler',
            $searchHandler
        );
        $this->assertInstanceOf(
            'eZ\\Publish\\Core\\Persistence\\SqlNg\\Content\\Search\\Handler',
            $searchHandler
        );
    }

    /**
     * @covers eZ\Publish\Core\Persistence\SqlNg\Handler::searchHandler
     *
     * @return void
     */
    public function testSearchHandlerTwice()
    {
        $handler = $this->getPersistenceHandler();

        $this->assertSame(
            $handler->searchHandler(),
            $handler->searchHandler()
        );
    }

    /**
     * @covers eZ\Publish\Core\Persistence\SqlNg\Handler::contentTypeHandler
     *
     * @return void
     */
    public function testContentTypeHandler()
    {
        $handler = $this->getPersistenceHandler();
        $contentTypeHandler = $handler->contentTypeHandler();

        $this->assertInstanceOf(
            'eZ\\Publish\\SPI\\Persistence\\Content\\Type\\Handler',
            $contentTypeHandler
        );
        $this->assertInstanceOf(
            'eZ\\Publish\\Core\\Persistence\\SqlNg\\Content\\Type\\Handler',
            $contentTypeHandler
        );
    }

    /**
     * @covers eZ\Publish\Core\Persistence\SqlNg\Handler::contentLanguageHandler
     *
     * @return void
     */
    public function testContentLanguageHandler()
    {
        $handler = $this->getPersistenceHandler();
        $contentLanguageHandler = $handler->contentLanguageHandler();

        $this->assertInstanceOf(
            'eZ\\Publish\\SPI\\Persistence\\Content\\Language\\Handler',
            $contentLanguageHandler
        );
    }

    /**
     * @covers eZ\Publish\Core\Persistence\SqlNg\Handler::contentTypeHandler
     *
     * @return void
     */
    public function testContentTypeHandlerTwice()
    {
        $handler = $this->getPersistenceHandler();

        $this->assertSame(
            $handler->contentTypeHandler(),
            $handler->contentTypeHandler()
        );
    }

    /**
     * @covers eZ\Publish\Core\Persistence\SqlNg\Handler::locationHandler
     *
     * @return void
     */
    public function testLocationHandler()
    {
        $handler = $this->getPersistenceHandler();
        $locationHandler = $handler->locationHandler();

        $this->assertInstanceOf(
            'eZ\\Publish\\SPI\\Persistence\\Content\\Location\\Handler',
            $locationHandler
        );
        $this->assertInstanceOf(
            'eZ\\Publish\\Core\\Persistence\\SqlNg\\Content\\Location\\Handler',
            $locationHandler
        );
    }

    /**
     * @covers eZ\Publish\Core\Persistence\SqlNg\Handler::locationHandler
     *
     * @return void
     */
    public function testLocationHandlerTwice()
    {
        $handler = $this->getPersistenceHandler();

        $this->assertSame(
            $handler->locationHandler(),
            $handler->locationHandler()
        );
    }

    /**
     * @covers eZ\Publish\Core\Persistence\SqlNg\Handler::userHandler
     *
     * @return void
     */
    public function testUserHandler()
    {
        $handler = $this->getPersistenceHandler();
        $userHandler = $handler->userHandler();

        $this->assertInstanceOf(
            'eZ\\Publish\\SPI\\Persistence\\User\\Handler',
            $userHandler
        );
        $this->assertInstanceOf(
            'eZ\\Publish\\Core\\Persistence\\SqlNg\\User\\Handler',
            $userHandler
        );
    }

    /**
     * @covers eZ\Publish\Core\Persistence\SqlNg\Handler::userHandler
     *
     * @return void
     */
    public function testUserHandlerTwice()
    {
        $handler = $this->getPersistenceHandler();

        $this->assertSame(
            $handler->userHandler(),
            $handler->userHandler()
        );
    }

    /**
     * @covers eZ\Publish\Core\Persistence\SqlNg\Handler::sectionHandler
     *
     * @return void
     */
    public function testSectionHandler()
    {
        $handler = $this->getPersistenceHandler();
        $sectionHandler = $handler->sectionHandler();

        $this->assertInstanceOf(
            'eZ\\Publish\\SPI\\Persistence\\Content\\Section\\Handler',
            $sectionHandler
        );
        $this->assertInstanceOf(
            'eZ\\Publish\\Core\\Persistence\\SqlNg\\Content\\Section\\Handler',
            $sectionHandler
        );
    }

    /**
     * @covers eZ\Publish\Core\Persistence\SqlNg\Handler::sectionHandler
     *
     * @return void
     */
    public function testSectionHandlerTwice()
    {
        $handler = $this->getPersistenceHandler();

        $this->assertSame(
            $handler->sectionHandler(),
            $handler->sectionHandler()
        );
    }

    /**
     * @covers eZ\Publish\Core\Persistence\SqlNg\Handler::urlAliasHandler
     *
     * @return void
     */
    public function testUrlAliasHandler()
    {
        $handler = $this->getPersistenceHandler();
        $urlAliasHandler = $handler->urlAliasHandler();

        $this->assertInstanceOf(
            'eZ\\Publish\\SPI\\Persistence\\Content\\UrlAlias\\Handler',
            $urlAliasHandler
        );
        $this->assertInstanceOf(
            'eZ\\Publish\\Core\\Persistence\\SqlNg\\Content\\UrlAlias\\Handler',
            $urlAliasHandler
        );
    }

    /**
     * @covers eZ\Publish\Core\Persistence\SqlNg\Handler::urlAliasHandler
     *
     * @return void
     */
    public function testUrlAliasHandlerTwice()
    {
        $handler = $this->getPersistenceHandler();

        $this->assertSame(
            $handler->urlAliasHandler(),
            $handler->urlAliasHandler()
        );
    }
}
