<?php
/**
 * File containing the MatcherTest class.
 *
 * @copyright Copyright (C) 1999-2013 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 */

namespace eZ\Publish\Core\Site\Tests;

use eZ\Publish\API\Repository\Values\ValueObject;

class MatcherTest extends \PHPUnit_Framework_TestCase
{
    public function testMatchSingleSiteInstallation()
    {
        $matcher = $this->createSiteAccessRouter(
            array(
                $this->buildSite(
                    'test_site', 'host', 'share.ez.no', 80
                )
            )
        );
        $userContext = new UserContext( array( 'host' => 'share.ez.no' ) );
        $siteAccess = $matcher->match( $userContext );
        $this->assertSame( 'test_site', $siteAccess->name );
        $this->assertSame( 'test_repository', $siteAccess->repositoryName );
    }

    public function testMatchMultiSiteInstallation()
    {
        $matcher = $this->createSiteAccessRouter(
            array(
                $this->buildSite( 'ez_publish_community', 'host', 'share.ez.no', 80 ),
                $this->buildSite( 'Google', 'host', 'google.com', 80 ),
            )
        );
        $userContext = new UserContext( array( 'host' => 'share.ez.no' ) );
        $siteAccess = $matcher->match( $userContext );
        $this->assertSame( "ez_publish_community", $siteAccess->name );
    }

    /**
     * @expectedException \eZ\Publish\Core\Site\Tests\NoMatchFoundException
     */
    public function testNoMatchThrowsException()
    {
        $matcher = $this->createSiteAccessRouter( array() );
        $userContext = new UserContext( array( 'host' => 'share.ez.no' ) );

        $matcher->match( $userContext );
    }

    public function testMatchPostForMultiSite()
    {
        $matcher = $this->createSiteAccessRouter(
            array(
                $this->buildSite( 'ez_publish_community', 'port', 'share.ez.no', 80 ),
                $this->buildSite( 'ez_publish_community_secure', 'port', 'share.ez.no', 443 ),
            )
        );
        $userContext = new UserContext( array( 'port' => 443 ) );
        $siteAccess = $matcher->match( $userContext );
        $this->assertSame( "ez_publish_community_secure", $siteAccess->name );
    }

    private function createSiteAccessRouter( $sites )
    {
        $matcher = new SiteAccessRouter( 
            new InMemorySiteRepository( $sites )
        );
        $matcher->addSiteMatcher( 'host', new HostSiteMatcher() );
        $matcher->addSiteMatcher( 'port', new PortSiteMatcher() );

        return $matcher;
    }

    public function testMatchLanguage()
    {
        $matcher = $this->createSiteAccessRouter(
            array(
                $this->buildSite(
                    'ez_publish_community', 'host', 'share.ez.no', 80,
                    array(
                        "languages" => array(
                            "eng-GB", "ger-DE", "fre-FR"
                        )
                    )
                ),
            )
        );
        $languageMatcherMock = $this->getMock( "eZ\\Publish\\Core\\Site\\Tests\\ParameterResolver" );
        $languageMatcherMock
            ->expects( $this->once() )
            ->method( "resolve" )
            ->will(
                $this->returnValue( array( "fre-FR", "eng-GB", "ger-DE" ) )
            );
        $matcher->addParameterResolver( "languages", $languageMatcherMock );

        $userContext = new UserContext(
            array(
                "host" => "share.ez.no",
                "headers" => array(
                    "accept-language" => array( "fre-FR" )
                )
            )
        );
        $siteAccess = $matcher->match( $userContext );
        $this->assertSame(
            array( "fre-FR", "eng-GB", "ger-DE" ),
            $siteAccess->parameters["languages"]
        );
    }

    protected function buildSite( $name, $matcherType, $host, $port, $parameters = array() )
    {
        return new Site(
            array(
                'name' => $name,
                'matcherType' => $matcherType,
                'host' => $host,
                'port' => $port,
                'parameters' => $parameters,
                "repositoryName" => 'test_repository'
            )
        );
    }

    // Tests for SiteMatchers
    public function testHostSiteMatcherMatchesOnHost()
    {
        $matcher = new HostSiteMatcher();

        $match = $matcher->match(
            new UserContext(
                array(
                    'host' => 'share.ez.no',
                )
            ),
            new Site(
                array(
                    'host' => 'share.ez.no',
                )
            )
        );

        $this->assertTrue($match);
    }


}

class SiteAccess extends ValueObject
{
    protected $name;
    protected $repositoryName;
    protected $parameters;
}

class Site extends ValueObject
{
    protected $host;
    protected $name;
    protected $repositoryName;
    protected $port;
    protected $matcherType;
    protected $parameters;
}

interface SiteRepository
{
    /**
     * @return Site[]
     */
    public function findAll();
}

class InMemorySiteRepository implements SiteRepository
{
    private $sites = array();

    public function __construct( array $sites )
    {
        $this->sites = $sites;
    }

    /**
     * @return Site[]
     */
    public function findAll()
    {
        return $this->sites;
    }
}

class SiteAccessRouter
{
    protected $siteRepository;
    protected $matchers = array();
    protected $parameterResolvers = array();

    public function __construct( SiteRepository $siteRepository )
    {
        $this->siteRepository = $siteRepository;
    }

    public function addSiteMatcher( $name, SiteMatcher $instance )
    {
        $this->matchers[$name] = $instance;
    }

    public function addParameterResolver( $name, ParameterResolver $instance )
    {
        $this->parameterResolvers[$name] = $instance;
    }

    public function match( UserContext $userContext )
    {
        $sites = $this->siteRepository->findAll();

        foreach ( $sites as $site )
        {
            $matcher = $this->matchers[$site->matcherType];
            if ( $matcher->match( $userContext, $site ) )
            {
                return new SiteAccess(
                    array(
                        'name' => $site->name,
                        'repositoryName' => $site->repositoryName,
                        "parameters" => $this->resolveParameters( $userContext, $site)
                    )
                );
            }
        }

        throw new NoMatchFoundException();
    }

    private function resolveParameters( UserContext $userContext , Site $site)
    {
        $matchedParameters = array();
        foreach ( $this->parameterResolvers as $parameterName => $parameterResolver )
        {
            $matchedParameters[$parameterName] = $parameterResolver->resolve( $userContext, $site );
        }

        return $matchedParameters;
    }
}

interface SiteMatcher
{
    /**
     * Check if Site matches.
     *
     * @return bool
     */
    public function match( UserContext $userContext, Site $site );
}

class HostSiteMatcher implements SiteMatcher
{
    public function match( UserContext $userContext, Site $site )
    {
        return $site->host == $userContext->host;
    }
}

class PortSiteMatcher implements SiteMatcher
{
    public function match( UserContext $userContext, Site $site )
    {
        return $site->port == $userContext->port;
    }
}

interface ParameterResolver
{
    /**
     * @return mixed
     */
    public function resolve( UserContext $userContext, Site $site );
}

class UserContext extends ValueObject
{
    protected $host;
    protected $port;
    protected $headers;
}

class NoMatchFoundException extends \Exception
{

}
