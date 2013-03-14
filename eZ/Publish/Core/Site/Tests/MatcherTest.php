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
        $this->assertSame( 'test_site', $siteAccess->identifier );
        $this->assertSame( 'test_repository', $siteAccess->repository );
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
        $this->assertSame( "ez_publish_community", $siteAccess->identifier );
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

    public function testNoMatchReturnsDefaultSite()
    {
        $matcher = $this->createSiteAccessRouter(
            array(
                $this->buildSite( 'default_site', 'host', 'share.ez.no', 80 )
            ),
            'default_site'
        );

        $userContext = new UserContext( array( 'host' => 'ez.no' ) );

        $siteAccess = $matcher->match( $userContext );

        $this->assertSame( "default_site", $siteAccess->identifier );
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
        $this->assertSame( "ez_publish_community_secure", $siteAccess->identifier );
    }

    private function createSiteAccessRouter( $sites, $defaultSiteName = null )
    {
        $matcher = new SiteAccessRouter(
            new InMemorySiteRepository( $sites ),
            $defaultSiteName
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
            $siteAccess->properties["languages"]
        );
    }

    protected function buildSite( $name, $matcherType, $host, $port, $properties = array() )
    {
        return new Site(
            array(
                'identifier' => $name,
                'name' => $name,
                'matcherType' => $matcherType,
                'host' => $host,
                'port' => $port,
                'properties' => $properties,
                "repository" => 'test_repository'
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
    protected $identifier;
    protected $repository;
    protected $properties;
}

class Site extends ValueObject
{
    protected $identifier;
    protected $name;
    protected $host;
    protected $repository;
    protected $port;
    protected $matcherType;
    protected $properties;
}

interface SiteRepository
{
    /**
     * @return Site[]
     */
    public function findAll();

    /**
     * @return Site
     */
    public function find( $identifier );

    /**
     * @param Site $site
     */
    public function add( Site $site );
}

/**
 * Populate InMemory repository from ezpublish.yml site access configuration.
 */
class InMemorySiteRepository implements SiteRepository
{
    private $sites = array();

    public function __construct( array $sites )
    {
        foreach ($sites as $site) {
            $this->add( $site );
        }
    }

    public function add( Site $site)
    {
        $this->sites[$site->identifier] = $site;
    }

    public function find( $identifier )
    {
        if ( !isset( $this->sites[$identifier] ) )
        {
            throw new \Exception("Not found");
        }

        return $this->sites[$identifier];
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
    protected $defaultSiteName;

    public function __construct( SiteRepository $siteRepository, $defaultSiteName )
    {
        $this->siteRepository = $siteRepository;
        $this->defaultSiteName = $defaultSiteName;
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
                return $this->createSiteAccess( $userContext, $site );
            }
        }

        if ($this->defaultSiteName) {
            $defaultSite = $this->siteRepository->find( $this->defaultSiteName );

            return $this->createSiteAccess( $userContext, $defaultSite);
        }

        throw new NoMatchFoundException();
    }

    private function createSiteAccess(UserContext $userContext, Site $site)
    {
        return new SiteAccess(
            array(
                'identifier' => $site->identifier,
                'repository' => $site->repository,
                "properties" => $this->resolveProperties( $userContext, $site)
            )
        );
    }

    private function resolveProperties( UserContext $userContext , Site $site)
    {
        $matchedProperties = array();
        foreach ( $this->parameterResolvers as $parameterName => $parameterResolver )
        {
            $matchedProperties[$parameterName] = $parameterResolver->resolve( $userContext, $site );
        }

        return $matchedProperties;
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
