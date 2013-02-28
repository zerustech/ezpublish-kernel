<?php
/**
 * File contains: eZ\Publish\Core\Persistence\Legacy\Tests\Content\SearchHandlerTest class
 *
 * @copyright Copyright (C) 1999-2013 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 */

namespace eZ\Publish\Core\Persistence\SqlNg\Tests\Content;

use eZ\Publish\Core\Persistence\SqlNg\Tests\TestCase;

use eZ\Publish\SPI\Persistence;
use eZ\Publish\Core\Persistence\SqlNg;
use eZ\Publish\API\Repository\Values\Content;

/**
 * Test case for ContentSearchHandler
 */
class SearchHandlerTest extends TestCase
{
    /**
     * Returns the handler to test
     *
     * @return \eZ\Publish\Core\Persistence\SqlNg\Content\Search\Handler
     */
    protected function getSearchHandler()
    {
        return $this->getPersistenceHandler()->searchHandler();
    }

    // - status
    // - logical
    // - parent-location
    // - content-type

    public function getQueries()
    {
        return array(
            array(
                new Content\Query( array(
                    'criterion' => new Content\Query\Criterion\ContentId(
                        $this->getContent()->versionInfo->contentInfo->id
                    )
                ) ),
                array(
                    $this->getContent()->versionInfo->contentInfo->id
                )
            ),
            array(
                new Content\Query( array(
                    'criterion' => new Content\Query\Criterion\ContentId( 1337 )
                ) ),
                array()
            ),
            array(
                new Content\Query( array(
                    'criterion' => new Content\Query\Criterion\Subtree(
                        '/' . $this->getLocation()->id . '/'
                    )
                ) ),
                array(
                    $this->getContent()->versionInfo->contentInfo->id
                )
            ),
            array(
                new Content\Query( array(
                    'criterion' => new Content\Query\Criterion\Subtree( '/1337' )
                ) ),
                array()
            ),
            array(
                new Content\Query( array(
                    'criterion' => new Content\Query\Criterion\LocationId(
                        $this->getLocation()->id
                    )
                ) ),
                array(
                    $this->getContent()->versionInfo->contentInfo->id
                )
            ),
            array(
                new Content\Query( array(
                    'criterion' => new Content\Query\Criterion\LocationId( 1337 )
                ) ),
                array()
            ),
            array(
                new Content\Query( array(
                    'criterion' => new Content\Query\Criterion\RemoteId(
                        $this->getContent()->versionInfo->contentInfo->remoteId
                    )
                ) ),
                array(
                    $this->getContent()->versionInfo->contentInfo->id
                )
            ),
            array(
                new Content\Query( array(
                    'criterion' => new Content\Query\Criterion\RemoteId( 'unknown' )
                ) ),
                array()
            ),
        );
    }

    public function testIndexContent()
    {
        $handler = $this->getSearchHandler();

        $this->getLocation();
        $result = $handler->indexContent($this->getContent());
    }

    /**
     * @depends testIndexContent
     * @dataProvider getQueries
     */
    public function testFindContent($query, $expected)
    {
        $handler = $this->getSearchHandler();

        $result = $handler->findContent($query);
        $result = array_map(
            function (Content\Search\SearchHit $hit) {
                return $hit->valueObject->versionInfo->contentInfo->id;
            },
            $result->searchHits
        );

        $this->assertEquals(
            $expected,
            $result
        );
    }
}

