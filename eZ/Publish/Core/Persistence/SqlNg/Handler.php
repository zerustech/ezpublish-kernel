<?php
/**
 * File containing the Handler interface
 *
 * @copyright Copyright (C) 1999-2012 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 */

namespace eZ\Publish\Core\Persistence\SqlNg;

use eZ\Publish\SPI\Persistence\Handler as HandlerInterface;

use \eZ\Publish\Core\Persistence\Legacy\EzcDbHandler;

/**
 * The repository handler for the legacy storage engine
 */
class Handler implements HandlerInterface
{
    /**
     * Content handler
     *
     * @var \eZ\Publish\Core\Persistence\SqlNg\Content\Handler
     */
    protected $contentHandler;

    /**
     * Content mapper
     *
     * @var \eZ\Publish\Core\Persistence\SqlNg\Content\Mapper
     */
    protected $contentMapper;

    /**
     * Storage handler
     *
     * @var \eZ\Publish\Core\Persistence\SqlNg\Content\StorageHandler
     */
    protected $storageHandler;

    /**
     * Field handler
     *
     * @var \eZ\Publish\Core\Persistence\SqlNg\Content\FieldHandler
     */
    protected $fieldHandler;

    /**
     * Search handler
     *
     * @var \eZ\Publish\Core\Persistence\SqlNg\Content\Search\Handler
     */
    protected $searchHandler;

    /**
     * Content type handler
     *
     * @var \eZ\Publish\Core\Persistence\SqlNg\Content\Type\Handler
     */
    protected $contentTypeHandler;

    /**
     * Content Type gateway
     *
     * @var \eZ\Publish\Core\Persistence\SqlNg\Content\Type\Gateway
     */
    protected $contentTypeGateway;

    /**
     * Content Type update handler
     *
     * @var \eZ\Publish\Core\Persistence\SqlNg\Content\Type\Update\Handler
     */
    protected $typeUpdateHandler;

    /**
     * Location handler
     *
     * @var \eZ\Publish\Core\Persistence\SqlNg\Content\Location\Handler
     */
    protected $locationHandler;

    /**
     * Location gateway
     *
     * @var \eZ\Publish\Core\Persistence\SqlNg\Content\Location\Gateway
     */
    protected $locationGateway;

    /**
     * Location mapper
     *
     * @var \eZ\Publish\Core\Persistence\SqlNg\Content\Location\Mapper
     */
    protected $locationMapper;

    /**
     * ObjectState handler
     *
     * @var \eZ\Publish\Core\Persistence\SqlNg\Content\ObjectState\Handler
     */
    protected $objectStateHandler;

    /**
     * ObjectState gateway
     *
     * @var \eZ\Publish\Core\Persistence\SqlNg\Content\ObjectState\Gateway
     */
    protected $objectStateGateway;

    /**
     * ObjectState mapper
     *
     * @var \eZ\Publish\Core\Persistence\SqlNg\Content\ObjectState\Mapper
     */
    protected $objectStateMapper;

    /**
     * User handler
     *
     * @var \eZ\Publish\Core\Persistence\SqlNg\User\Handler
     */
    protected $userHandler;

    /**
     * Section handler
     *
     * @var \eZ\Publish\Core\Persistence\SqlNg\Content\Section\Handler
     */
    protected $sectionHandler;

    /**
     * Trash handler
     *
     * @var \eZ\Publish\Core\Persistence\SqlNg\Content\Location\Trash\Handler
     */
    protected $trashHandler;

    /**
     * Content gateway
     *
     * @var \eZ\Publish\Core\Persistence\SqlNg\Content\Gateway
     */
    protected $contentGateway;

    /**
     * Language handler
     *
     * @var \eZ\Publish\SPI\Persistence\Content\Language\Handler
     */
    protected $languageHandler;

    /**
     * Language cache
     *
     * @var \eZ\Publish\Core\Persistence\SqlNg\Content\Language\Cache
     */
    protected $languageCache;

    /**
     * Language mask generator
     *
     * @var \eZ\Publish\Core\Persistence\SqlNg\Content\Language\MaskGenerator
     */
    protected $languageMaskGenerator;

    /**
     * UrlAlias handler
     *
     * @var \eZ\Publish\Core\Persistence\SqlNg\Content\UrlAlias\Handler
     */
    protected $urlAliasHandler;

    /**
     * UrlAlias gateway
     *
     * @var \eZ\Publish\Core\Persistence\SqlNg\Content\UrlAlias\Gateway
     */
    protected $urlAliasGateway;

    /**
     * UrlAlias mapper
     *
     * @var \eZ\Publish\Core\Persistence\SqlNg\Content\UrlAlias\Mapper
     */
    protected $urlAliasMapper;

    /**
     * UrlWildcard handler
     *
     * @var \eZ\Publish\Core\Persistence\SqlNg\Content\UrlWildcard\Handler
     */
    protected $urlWildcardHandler;

    /**
     * UrlWildcard gateway
     *
     * @var \eZ\Publish\Core\Persistence\SqlNg\Content\UrlWildcard\Gateway
     */
    protected $urlWildcardGateway;

    /**
     * UrlWildcard mapper
     *
     * @var \eZ\Publish\Core\Persistence\SqlNg\Content\UrlWildcard\Mapper
     */
    protected $urlWildcardMapper;

    /**
     * @var \eZ\Publish\Core\Persistence\SqlNg\EzcDbHandler
     */
    protected $dbHandler;

    /**
     * Creates a new repository handler.
     *
     * @param \eZ\Publish\Core\Persistence\SqlNg\EzcDbHandler $dbHandler The database handler
     */
    public function __construct( EzcDbHandler $dbHandler )
    {
        $this->dbHandler = $dbHandler;
    }

    /**
     * @internal LocationHandler is injected into property to avoid circular dependency
     *
     * @return \eZ\Publish\SPI\Persistence\Content\Handler
     */
    public function contentHandler()
    {
        if ( !isset( $this->contentHandler ) )
        {
            $this->contentHandler = new Content\Handler(
            );
        }
        return $this->contentHandler;
    }

    /**
     * Returns the field value converter registry
     *
     * @return \eZ\Publish\Core\Persistence\SqlNg\Content\FieldValue\ConverterRegistry
     */
    public function getFieldValueConverterRegistry()
    {
        return $this->converterRegistry;
    }
    /**
     * Returns the storage registry
     *
     * @return Content\StorageRegistry
     */
    public function getStorageRegistry()
    {
        return $this->storageRegistry;
    }

    /**
     * @return \eZ\Publish\SPI\Persistence\Content\Search\Handler
     */
    public function searchHandler()
    {
        if ( !isset( $this->searchHandler ) )
        {
            $this->searchHandler = new Content\Search\Handler(
            );
        }
        return $this->searchHandler;
    }

    /**
     * @return \eZ\Publish\SPI\Persistence\Content\Type\Handler
     */
    public function contentTypeHandler()
    {
        if ( !isset( $this->contentTypeHandler ) )
        {
            $this->contentTypeHandler = new Content\Type\Handler(
            );
        }
        return $this->contentTypeHandler;
    }

    /**
     * @return \eZ\Publish\SPI\Persistence\Content\Language\Handler
     */
    public function contentLanguageHandler()
    {
        if ( !isset( $this->languageHandler ) )
        {
            $this->languageHandler = new Content\Language\Handler(
            );
        }
        return $this->languageHandler;
    }

    /**
     * @return \eZ\Publish\SPI\Persistence\Content\Location\Handler
     */
    public function locationHandler()
    {
        if ( !isset( $this->locationHandler ) )
        {
            $this->locationHandler = new Content\Location\Handler(
            );
        }
        return $this->locationHandler;
    }

    /**
     * @return \eZ\Publish\SPI\Persistence\Content\ObjectState\Handler
     */
    public function objectStateHandler()
    {
        if ( !isset( $this->objectStateHandler ) )
        {
            $this->objectStateHandler = new Content\ObjectState\Handler(
            );
        }
        return $this->objectStateHandler;
    }

    /**
     * @return \eZ\Publish\SPI\Persistence\User\Handler
     */
    public function userHandler()
    {
        if ( !isset( $this->userHandler ) )
        {
            $this->userHandler = new User\Handler(
            );
        }
        return $this->userHandler;
    }

    /**
     * @return \eZ\Publish\SPI\Persistence\Content\Section\Handler
     */
    public function sectionHandler()
    {
        if ( !isset( $this->sectionHandler ) )
        {
            $this->sectionHandler = new Content\Section\Handler(
            );
        }
        return $this->sectionHandler;
    }

    /**
     * @return \eZ\Publish\SPI\Persistence\Content\Location\Trash\Handler
     */
    public function trashHandler()
    {
        if ( !isset( $this->trashHandler ) )
        {
            $this->trashHandler = new Content\Location\Trash\Handler(
            );
        }

        return $this->trashHandler;
    }

    /**
     * @return \eZ\Publish\SPI\Persistence\Content\UrlAlias\Handler
     */
    public function urlAliasHandler()
    {
        if ( !isset( $this->urlAliasHandler ) )
        {
            $this->urlAliasHandler = new Content\UrlAlias\Handler(
            );
        }

        return $this->urlAliasHandler;
    }

    /**
     * @return \eZ\Publish\SPI\Persistence\Content\UrlWildcard\Handler
     */
    public function urlWildcardHandler()
    {
        if ( !isset( $this->urlWildcardHandler ) )
        {
            $this->urlWildcardHandler = new Content\UrlWildcard\Handler(
            );
        }

        return $this->urlWildcardHandler;
    }

    /**
     * Begin transaction
     *
     * Begins an transaction, make sure you'll call commit or rollback when done,
     * otherwise work will be lost.
     */
    public function beginTransaction()
    {
        $this->dbHandler->beginTransaction();
    }

    /**
     * Commit transaction
     *
     * Commit transaction, or throw exceptions if no transactions has been started.
     *
     * @throws \RuntimeException If no transaction has been started
     */
    public function commit()
    {
        try
        {
            $this->dbHandler->commit();
        }
        catch ( \ezcDbTransactionException $e )
        {
            throw new RuntimeException( $e->getMessage() );
        }
    }

    /**
     * Rollback transaction
     *
     * Rollback transaction, or throw exceptions if no transactions has been started.
     *
     * @throws \RuntimeException If no transaction has been started
     */
    public function rollback()
    {
        try
        {
            $this->dbHandler->rollback();
        }
        catch ( \ezcDbTransactionException $e )
        {
            throw new RuntimeException( $e->getMessage() );
        }
    }
}
