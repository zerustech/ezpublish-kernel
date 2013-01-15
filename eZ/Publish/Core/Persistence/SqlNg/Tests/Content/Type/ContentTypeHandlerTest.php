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

    /**
     * @covers eZ\Publish\Core\Persistence\SqlNg\Content\Type\Handler::createGroup
     *
     * @return void
     */
    public function testCreateGroup()
    {
        $handler = $this->getHandler();
        $group = $handler->createGroup(
            new Persistence\Content\Type\Group\CreateStruct()
        );

        $this->assertInstanceOf(
            'eZ\\Publish\\SPI\\Persistence\\Content\\Type\\Group',
            $group
        );
        $this->assertEquals(
            23,
            $group->id
        );
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
