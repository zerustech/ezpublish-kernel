<?php
/**
 * File contains: eZ\Publish\Core\Persistence\SqlNg\Tests\Content\Language\LanguageHandlerTest class
 *
 * @copyright Copyright (C) 1999-2012 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 */

namespace eZ\Publish\Core\Persistence\SqlNg\Tests\Content\Language;

use eZ\Publish\Core\Persistence\SqlNg\Tests\TestCase;
use eZ\Publish\SPI\Persistence;
use eZ\Publish\Core\Persistence\SqlNg;

/**
 * Test case for Language Handler
 */
class LanguageHandlerTest extends TestCase
{
    protected function getLanguageHandler()
    {
        return $this->getPersistenceHandler()->contentLanguageHandler();
    }

    public function testCreate()
    {
        $handler = $this->getLanguageHandler();
        $language = $handler->create(
            new Persistence\Content\Language\CreateStruct( array(
                'languageCode' => 'de_DE',
                'name' => 'German',
                'isEnabled' => true,
            ) )
        );

        $this->assertInstanceOf(
            'eZ\\Publish\\SPI\\Persistence\\Content\\Language',
            $language
        );
        $this->assertNotNull( $language->id );
        return $language;
    }

    /**
     * @depends testCreate
     */
    public function testUpdate( $language )
    {
        $handler = $this->getLanguageHandler();

        $language->name = 'Deutsch';
        $handler->update( $language );
        return $language;
    }

    /**
     * @depends testUpdate
     */
    public function testLoad( $language )
    {
        $handler = $this->getLanguageHandler();
        $loaded = $handler->load( $language->id );

        $this->assertEquals( $language, $loaded );
    }

    /**
     * @expectedException \eZ\Publish\API\Repository\Exceptions\NotFoundException
     */
    public function testLoadUnknownLanguage()
    {
        $handler = $this->getLanguageHandler();
        $handler->load( 1337 );
    }

    /**
     * @depends testUpdate
     */
    public function testLoadByLanguageCode( $language )
    {
        $handler = $this->getLanguageHandler();
        $loaded = $handler->loadByLanguageCode( $language->languageCode );

        $this->assertEquals( $language, $loaded );
    }

    /**
     * @expectedException \eZ\Publish\API\Repository\Exceptions\NotFoundException
     */
    public function testLoadByUnknownLanguageCode()
    {
        $handler = $this->getLanguageHandler();
        $handler->loadByLanguageCode( 'unknown' );
    }

    /**
     * @depends testUpdate
     */
    public function testLoadAll( $language )
    {
        $handler = $this->getLanguageHandler();
        $loaded = $handler->loadAll();

        $this->assertEquals(
            array(
                $language->languageCode => $language,
            ),
            $loaded
        );
    }

    /**
     * @depends testCreate
     */
    public function testDelete( $language )
    {
        $handler = $this->getLanguageHandler();
        $handler->delete( $language->id );

        return $language;
    }

    /**
     * @depends testDelete
     * @expectedException \eZ\Publish\Core\Base\Exceptions\NotFoundException
     */
    public function testDeleteUnknown( $language )
    {
        $handler = $this->getLanguageHandler();
        $handler->delete( $language->id );
    }
}
