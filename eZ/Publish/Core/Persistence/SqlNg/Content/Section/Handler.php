<?php
/**
 * File containing the Section Handler class
 *
 * @copyright Copyright (C) 1999-2012 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 */

namespace eZ\Publish\Core\Persistence\SqlNg\Content\Section;

use eZ\Publish\SPI\Persistence\Content\Section\Handler as BaseSectionHandler;
use eZ\Publish\SPI\Persistence;

use eZ\Publish\Core\Base\Exceptions\NotFoundException as NotFound;
use eZ\Publish\Core\Base\Exceptions\BadStateException;

/**
 * Section Handler
 */
class Handler implements BaseSectionHandler
{
    /**
     * Section Gateway
     *
     * @var \eZ\Publish\Core\Persistence\SqlNg\Content\Section\Gateway
     */
    protected $sectionGateway;

    /**
     * Creates a new Section Handler
     *
     * @param \eZ\Publish\Core\Persistence\SqlNg\Content\Section\Gateway $sectionGateway
     */
    public function __construct( Gateway $sectionGateway  )
    {
        $this->sectionGateway = $sectionGateway;
    }

    /**
     * Create a new section
     *
     * @param string $name
     * @param string $identifier
     *
     * @return \eZ\Publish\SPI\Persistence\Content\Section
     */
    public function create( $name, $identifier )
    {
        return new Persistence\Content\Section( array(
            'id' => $this->sectionGateway->insertSection(
                $name,
                $identifier
            ),
            'name' => $name,
            'identifier' => $identifier,
        ) );
    }

    /**
     * Update name and identifier of a section
     *
     * @param mixed $id
     * @param string $name
     * @param string $identifier
     *
     * @return \eZ\Publish\SPI\Persistence\Content\Section
     */
    public function update( $id, $name, $identifier )
    {
        $this->sectionGateway->updateSection( $id, $name, $identifier );

        return new Persistence\Content\Section( array(
            'id' => $id,
            'name' => $name,
            'identifier' => $identifier,
        ) );
    }

    /**
     * Get section data
     *
     * @param mixed $id
     *
     * @throws \eZ\Publish\API\Repository\Exceptions\NotFoundException If section is not found
     *
     * @return \eZ\Publish\SPI\Persistence\Content\Section
     */
    public function load( $id )
    {
        $rows = $this->sectionGateway->loadSectionData( $id );

        if ( empty( $rows ) )
        {
            throw new NotFound( "Section", $id );
        }
        return $this->createSectionFromArray( reset( $rows ) );
    }

    /**
     * Get all section data
     *
     * @return \eZ\Publish\SPI\Persistence\Content\Section[]
     */
    public function loadAll()
    {
        return $this->createSectionsFromArray(
            $this->sectionGateway->loadAllSectionData()
        );
    }

    /**
     * Get section data by identifier
     *
     * @param string $identifier
     *
     * @throws \eZ\Publish\API\Repository\Exceptions\NotFoundException If section is not found
     *
     * @return \eZ\Publish\SPI\Persistence\Content\Section
     */
    public function loadByIdentifier( $identifier )
    {
        $rows = $this->sectionGateway->loadSectionDataByIdentifier( $identifier );

        if ( empty( $rows ) )
        {
            throw new NotFound( "Section", $identifier );
        }
        return $this->createSectionFromArray( reset( $rows ) );
    }

    /**
     * Delete a section
     *
     * Might throw an exception if the section is still associated with some
     * content objects. Make sure that no content objects are associated with
     * the section any more *before* calling this method.
     *
     * @param mixed $id
     */
    public function delete( $id )
    {
        try
        {
            $this->sectionGateway->deleteSection( $id );
        }
        catch ( \PDOException $e )
        {
            throw new BadStateException(
                "section",
                "Depending objects exist",
                $e
            );
        }
    }

    /**
     * Assigns section to single content object
     *
     * @param mixed $sectionId
     * @param mixed $contentId
     */
    public function assign( $sectionId, $contentId )
    {
        $this->sectionGateway->assignSectionToContent( $sectionId, $contentId );
    }

    /**
     * Number of content assignments a Section has
     *
     * @param mixed $sectionId
     *
     * @return int
     */
    public function assignmentsCount( $sectionId )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Creates a Section from the given $data
     *
     * @param array $data
     *
     * @return \eZ\Publish\SPI\Persistence\Content\Section[]
     */
    protected function createSectionsFromArray( array $data )
    {
        $sections = array();
        foreach ( $data as $sectionData )
        {
            $sections[] = $this->createSectionFromArray( $sectionData );
        }
        return $sections;
    }

    /**
     * Creates a Section from the given $data
     *
     * @param array $data
     *
     * @return \eZ\Publish\SPI\Persistence\Content\Section
     */
    protected function createSectionFromArray( array $data )
    {
        return new Persistence\Content\Section( array(
            'id' => (int)$data['section_id'],
            'name' => $data['name'],
            'identifier' => $data['identifier'],
        ) );
    }
}
