<?php
/**
 * File containing the UrlAlias Handler
 *
 * @copyright Copyright (C) 1999-2012 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 */

namespace eZ\Publish\Core\Persistence\SqlNg\Content\UrlAlias;

use eZ\Publish\SPI\Persistence\Content\UrlAlias\Handler as UrlAliasHandlerInterface;
use eZ\Publish\Core\Persistence\SqlNg\Content\Language\Handler as LanguageHandler;
use eZ\Publish\Core\Persistence\SqlNg\Content\Search\TransformationProcessor;
use eZ\Publish\Core\Persistence\SqlNg\Content\Location\Handler as LocationHandler;
use eZ\Publish\Core\Persistence\SqlNg\Content\Location\Gateway as LocationGateway;
use eZ\Publish\SPI\Persistence\Content\UrlAlias;
use eZ\Publish\Core\Base\Exceptions\NotFoundException;
use eZ\Publish\Core\Base\Exceptions\ForbiddenException;
use PHPUnit_Framework_IncompleteTestError;

/**
 * The UrlAlias Handler provides nice urls management.
 *
 * Its methods operate on a representation of the url alias data structure held
 * inside a storage engine.
 */
class Handler implements UrlAliasHandlerInterface
{
    /**
     * UrlAlias Gateway
     *
     * @var \eZ\Publish\Core\Persistence\SqlNg\Content\UrlAlias\Gateway
     */
    protected $gateway;

    /**
     * UrlAlias Mapper
     *
     * @var \eZ\Publish\Core\Persistence\SqlNg\Content\UrlAlias\Mapper
     */
    protected $mapper;

    /**
     * Caching language handler
     *
     * @var \eZ\Publish\Core\Persistence\SqlNg\Content\Language\Handler
     */
    protected $languageHandler;

    /**
     * Location Handler
     *
     * @var \eZ\Publish\Core\Persistence\SqlNg\Content\Location\Handler
     */
    protected $locationHandler;

    /**
     * Creates a new UrlAlias Handler
     *
     * @param \eZ\Publish\Core\Persistence\SqlNg\Content\UrlAlias\Gateway $gateway
     * @param \eZ\Publish\Core\Persistence\SqlNg\Content\UrlAlias\Mapper $mapper
     * @param \eZ\Publish\Core\Persistence\SqlNg\Content\Location\Gateway $locationGateway
     * @param \eZ\Publish\Core\Persistence\SqlNg\Content\Language\Handler $languageHandler
     * @param \eZ\Publish\Core\Persistence\SqlNg\Content\Search\TransformationProcessor $transformationProcessor
     * @param array $configuration
     */
    public function __construct(
        LanguageHandler $languageHandler,
        LocationHandler $locationHandler,
        Gateway $gateway,
        Mapper $mapper
    )
    {
        $this->languageHandler = $languageHandler;
        $this->locationHandler = $locationHandler;
        $this->gateway = $gateway;
        $this->mapper = $mapper;
    }

    /**
     * This method creates or updates an urlalias from a new or changed content name in a language
     * (if published). It also can be used to create an alias for a new location of content.
     * On update the old alias is linked to the new one (i.e. a history alias is generated).
     *
     * $alwaysAvailable controls whether the url alias is accessible in all
     * languages.
     *
     * @param mixed $locationId
     * @param mixed $parentLocationId
     * @param string $name the new name computed by the name schema or url alias schema
     * @param string $languageCode
     * @param boolean $alwaysAvailable
     * @param boolean $isLanguageMain used only for legacy storage for updating ezcontentobject_tree.path_identification_string
     *
     * @return void
     */
    public function publishUrlAliasForLocation(
        $locationId,
        $parentLocationId,
        $name,
        $languageCode,
        $alwaysAvailable = false,
        $isLanguageMain = false
    )
    {
        // @TODO: The URLification of the name should probably happen in the
        // Business layer. Why implement it in the Storage engine?
        $name = $name;

        $path = '';
        if ($parentLocationId !== null) {
            // @TODO: Fetch parent path
        }

        $path .= '/' . $name;

        $this->gateway->updateOldAliasesForLocation( $locationId );

        $urlAliasId = $this->gateway->createUrlAlias( UrlAlias::LOCATION, $locationId, false, false, false );

        $pathHash = $this->hashPath( $path );
        foreach ( $this->getLanguages( $languageCode ) as $languageId )
        {
            $this->gateway->addTranslatedPath( $urlAliasId, $path, $pathHash, $languageId );
        }

        return $this->loadUrlAlias( $urlAliasId );
    }

    /**
     * Get languages for url alias.
     *
     * The $languageCode might be null, currently we return all
     * languzage IDs then.
     *
     * @param mixed $languageCode
     * @return array
     */
    protected function getLanguages( $languageCode )
    {
        $languages = array();
        if ( $languageCode )
        {
            $languages = array(
                $this->languageHandler->loadByLanguageCode( $languageCode )->id,
            );
        }
        else
        {
            $languages = array_map(
                function ( $language ) {
                    return $language->id;
                },
                $this->languageHandler->loadAll()
            );
        }

        return $languages;
    }

    /**
     * Create a user chosen $alias pointing to $locationId in $languageCode.
     *
     * If $languageCode is null the $alias is created in the system's default
     * language. $alwaysAvailable makes the alias available in all languages.
     *
     * @param mixed $locationId
     * @param string $path
     * @param boolean $forwarding
     * @param string $languageCode
     * @param boolean $alwaysAvailable
     *
     * @return \eZ\Publish\SPI\Persistence\Content\UrlAlias
     */
    public function createCustomUrlAlias( $locationId, $path, $forwarding = false, $languageCode = null, $alwaysAvailable = false )
    {
        $pathHash = $this->hashPath( $path );
        $urlAliasId = $this->gateway->createUrlAlias(
            UrlAlias::LOCATION,
            $locationId,
            $forwarding,
            false,
            true
        );

        foreach ( $this->getLanguages( $languageCode ) as $languageId )
        {
            $this->gateway->addTranslatedPath(
                $urlAliasId,
                $path,
                $pathHash,
                $languageId
            );
        }

        return $this->loadUrlAlias( $urlAliasId );
    }

    /**
     * Create a user chosen $alias pointing to a resource in $languageCode.
     * This method does not handle location resources - if a user enters a location target
     * the createCustomUrlAlias method has to be used.
     *
     * If $languageCode is null the $alias is created in the system's default
     * language. $alwaysAvailable makes the alias available in all languages.
     *
     * @throws \eZ\Publish\API\Repository\Exceptions\ForbiddenException if the path already exists for the given language
     *
     * @param string $resource
     * @param string $path
     * @param boolean $forwarding
     * @param string $languageCode
     * @param boolean $alwaysAvailable
     *
     * @return \eZ\Publish\SPI\Persistence\Content\UrlAlias
     */
    public function createGlobalUrlAlias( $resource, $path, $forwarding = false, $languageCode = null, $alwaysAvailable = false )
    {
        $pathHash = $this->hashPath( $path );
        $urlAliasId = $this->gateway->createUrlAlias(
            UrlAlias::RESOURCE,
            $resource,
            $forwarding,
            false,
            true
        );

        foreach ( $this->getLanguages( $languageCode ) as $languageId )
        {
            $this->gateway->addTranslatedPath(
                $urlAliasId,
                $path,
                $pathHash,
                $languageId
            );
        }

        return $this->loadUrlAlias( $urlAliasId );
    }

    /**
     * List of user generated or autogenerated url entries, pointing to $locationId.
     *
     * @param mixed $locationId
     * @param boolean $custom if true the user generated aliases are listed otherwise the autogenerated
     *
     * @return \eZ\Publish\SPI\Persistence\Content\UrlAlias[]
     */
    public function listURLAliasesForLocation( $locationId, $custom = false )
    {
        return $this->mapper->extractUrlAliasListFromData(
            $this->gateway->loadForLocation( $locationId, $custom )
        );
    }

    /**
     * List global aliases.
     *
     * @param string|null $languageCode
     * @param int $offset
     * @param int $limit
     *
     * @return \eZ\Publish\SPI\Persistence\Content\UrlAlias[]
     */
    public function listGlobalURLAliases( $languageCode = null, $offset = 0, $limit = -1 )
    {
        return $this->mapper->extractUrlAliasListFromData(
            $this->gateway->loadGlobalUrlAliases( $languageCode, $offset, $limit === -1 ? null : $limit )
        );
    }

    /**
     * Removes url aliases.
     *
     * Autogenerated aliases are not removed by this method.
     *
     * @param \eZ\Publish\SPI\Persistence\Content\UrlAlias[] $urlAliases
     *
     * @return boolean
     */
    public function removeURLAliases( array $urlAliases )
    {
        $this->gateway->removeAliases(
            array_map(
                function ( UrlAlias $urlAlias ) {
                    return $urlAlias->id;
                },
                $urlAliases
            )
        );
    }

    /**
     * Looks up a url alias for the given url
     *
     * @throws \eZ\Publish\API\Repository\Exceptions\NotFoundException
     * @throws \PHPUnit_Framework_IncompleteTestError
     * @throws \eZ\Publish\Core\Base\Exceptions\NotFoundException
     *
     * @param string $url
     *
     * @return \eZ\Publish\SPI\Persistence\Content\UrlAlias
     */
    public function lookup( $url )
    {
        throw new \PHPUnit_Framework_IncompleteTestError( "@TODO: Implement" );
    }

    /**
     * Loads URL alias by given $id
     *
     * @throws \eZ\Publish\API\Repository\Exceptions\NotFoundException
     *
     * @param string $id
     *
     * @return \eZ\Publish\SPI\Persistence\Content\UrlAlias
     */
    public function loadUrlAlias( $id )
    {
        return $this->mapper->extractUrlAliasFromData(
            $this->gateway->load( $id )
        );
    }

    /**
     * Notifies the underlying engine that a location has moved.
     *
     * This method triggers the change of the autogenerated aliases.
     *
     * @param mixed $locationId
     * @param mixed $oldParentId
     * @param mixed $newParentId
     *
     * @return void
     */
    public function locationMoved( $locationId, $oldParentId, $newParentId )
    {
        // throw new \PHPUnit_Framework_IncompleteTestError( "@TODO: Implement" );
    }

    /**
     * Notifies the underlying engine that a location was copied.
     *
     * This method triggers the creation of the autogenerated aliases for the copied locations
     *
     * @param mixed $locationId
     * @param mixed $oldParentId
     * @param mixed $newParentId
     *
     * @return void
     */
    public function locationCopied( $locationId, $newLocationId, $newParentId )
    {
        // throw new \PHPUnit_Framework_IncompleteTestError( "@TODO: Implement" );
    }

    /**
     * Notifies the underlying engine that a location was deleted or moved to trash
     *
     * @param mixed $locationId
     *
     * @return void
     */
    public function locationDeleted( $locationId )
    {
        // throw new \PHPUnit_Framework_IncompleteTestError( "@TODO: Implement" );
    }

    /**
     * Create a hash for the given path
     *
     * @REFACTOR: Should probably be moved into its own class
     *
     * @param string $path
     * @return string
     */
    protected function hashPath( $path )
    {
        return hash( "sha256", $path, true );
    }
}
