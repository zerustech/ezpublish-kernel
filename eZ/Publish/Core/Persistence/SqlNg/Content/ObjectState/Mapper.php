<?php
/**
 * File containing the ObjectState Mapper class
 *
 * @copyright Copyright (C) 1999-2012 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 */

namespace eZ\Publish\Core\Persistence\SqlNg\Content\ObjectState;

use eZ\Publish\SPI\Persistence;
use eZ\Publish\SPI\Persistence\Content\Language\Handler as LanguageHandler;

/**
 * Mapper for ObjectState and object state Group objects
 */
class Mapper
{
    /**
     * Creates ObjectState object from provided $data
     *
     * @param array $data
     *
     * @return \eZ\Publish\SPI\Persistence\Content\ObjectState
     */
    public function createObjectStateFromData( array $data )
    {
        $objectState = new Persistence\Content\ObjectState();

        $objectState->id = (int)$data['ezcontent_state_state_id'];
        $objectState->groupId = $data['ezcontent_state_state_group_id'];
        $objectState->identifier = $data['ezcontent_state_identifier'];
        $objectState->priority = (int)$data['ezcontent_state_priority'];
        $objectState->defaultLanguage = $data['ezcontent_language_language_code'];
        $objectState->name = json_decode( $data['ezcontent_state_name'], true );
        $objectState->description = json_decode( $data['ezcontent_state_description'], true );

        $objectState->languageCodes = array_unique(
            array_merge(
                array_keys( $objectState->name ),
                array_keys( $objectState->description )
            )
        );

        return $objectState;
    }

    /**
     * Creates ObjectStateGroup object from provided $data
     *
     * @param array $data
     *
     * @return \eZ\Publish\SPI\Persistence\Content\ObjectState\Group
     */
    public function createObjectStateGroupFromData( array $data )
    {
        $objectStateGroup = new Persistence\Content\ObjectState\Group();

        $objectStateGroup->id = (int)$data['ezcontent_state_group_state_group_id'];
        $objectStateGroup->identifier = $data['ezcontent_state_group_identifier'];
        $objectStateGroup->defaultLanguage = $data['ezcontent_language_language_code'];
        $objectStateGroup->name = json_decode( $data['ezcontent_state_group_name'], true );
        $objectStateGroup->description = json_decode( $data['ezcontent_state_group_description'], true );

        $objectStateGroup->languageCodes = array_unique(
            array_merge(
                array_keys( $objectStateGroup->name ),
                array_keys( $objectStateGroup->description )
            )
        );

        return $objectStateGroup;
    }
}
