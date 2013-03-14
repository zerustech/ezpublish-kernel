<?php

namespace eZ\Publish\Core\Site\Tests;

require_once "MatcherTest.php";

class UserContextTest extends \PHPUnit_Framework_TestCase
{
    public function testProperties()
    {
        $context = new UserContext(
            array(
                'host' => 'share.ez.no',
                'port' => 80,
                'headers' => array()
            )
        );

        $this->assertEquals( 'share.ez.no', $context->host );
        $this->assertEquals( 80, $context->port );
        $this->assertEquals( array(), $context->headers );
    }
}

