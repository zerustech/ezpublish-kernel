<?php
/**
 * File containing the Handler interface
 *
 * @copyright Copyright (C) 1999-2012 \eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag
 */

namespace eZ\Publish\Core\Persistence\SqlNg;

use eZ\Publish\SPI\Persistence;
use eZ\Publish\Core\Persistence\Legacy\EzcDbHandler;

/**
 * The repository handler for the legacy storage engine
 *
 * @TODO:
 * * Use some generic data input format (see data.json)
 * * Make sure all relevant contents are created, the current data set might be
 *   slightly too minimal.
 */
class RepositoryInitializer
{
    /**
     * Repository handler
     *
     * @var Handler
     */
    protected $handler;

    /**
     * Database handler
     *
     * @var EzcDbHandler
     */
    protected $database;

    public function __construct( Handler $handler, EzcDbHandler $database )
    {
        $this->handler = $handler;
        $this->database = $database;
    }

    /**
     * Initialize base repository
     *
     * @return void
     */
    public function initialize()
    {
        $importUser = $this->createImportUser();

        $language = $this->createLanguage( 'eng-GB', 'English (Great-Britain)' );

        $standardSection = $this->handler->sectionHandler()->create( 'Standard', 'standard' );
        $usersSection = $this->handler->sectionHandler()->create( 'Users', 'users' );

        // Content Type Groups
        $contentContentTypeGroup = $this->createTypeGroup( $importUser, 'Content' );
        $usersContentTypeGroup = $this->createTypeGroup( $importUser, 'Users' );

        // Content Types
        $landingPageType = $this->createLandingPageType( $importUser, $language, $contentContentTypeGroup );
        $userGroupType = $this->createUserGroupType( $importUser, $language, $usersContentTypeGroup );
        $userType = $this->createUserType( $importUser, $language, $usersContentTypeGroup );

        // Root location
        $rootLocationCreate = new Persistence\Content\Location\CreateStruct(
            array(
                'remoteId' => '629709ba256fe317c3ddcee35453a96a',
                'mainLocationId' => '1',
                'sortField' => 1,
                'sortOrder' => 1,
            )
        );
        $rootLocation = $this->handler->locationHandler()->create( $rootLocationCreate );

        $userGroup = $this->createRootUserGroup( $importUser, $userGroupType, $usersSection, $rootLocation, $language );
        $userRoot = $userGroup->versionInfo->contentInfo->mainLocationId;

        $this->createUser( $importUser, $userType, $usersSection, $userRoot, $language, '1bb4fe25487f05527efa8bfd394cecc7', 'anonymous', '4e6f6184135228ccd45f8233d72a0363' );
        $adminUser = $this->createUser( $importUser, $userType, $usersSection, $userRoot, $language, 'faaeb9be3bd98ed09f606fc16d144eca', 'admin', 'c78e3b0f3d9244ed8c6d1c29464bdff9' );

        $home = $this->createHome( $adminUser, $landingPageType, $standardSection, $rootLocation, $language );

        // Reown everything to admin user
        $this->database->exec(
            sprintf(
                'UPDATE ezcontenttype SET creator_id = %d, modifier_id = %d',
                $adminUser->id,
                $adminUser->id
            )
        );

        $this->database->exec(
            sprintf(
                'UPDATE ezcontenttype_group SET creator_id = %d, modifier_id = %d',
                $adminUser->id,
                $adminUser->id
            )
        );

        $this->database->exec(
            sprintf(
                'UPDATE ezcontent SET owner_id = %d',
                $adminUser->id
            )
        );

        $this->database->exec(
            sprintf(
                'UPDATE ezcontent_version SET creator_id = %d',
                $adminUser->id
            )
        );

        $this->handler->userHandler()->delete( $importUser->id );
    }

    protected function createImportUser()
    {
        return $this->handler->userHandler()->create(
            new Persistence\User(
                array(
                    'id' => 1,
                    'login' => 'import',
                    'email' => 'nospam@ez.no',
                    'passwordHash' => '*',
                    'hashAlgorithm' => '2',
                )
            )
        );
    }

    protected function createLanguage( $code, $name )
    {
        return $this->handler->contentLanguageHandler()->create(
            new Persistence\Content\Language\CreateStruct(
                array(
                    'languageCode' => $code,
                    'name' => $name,
                    'isEnabled' => true,
                )
            )
        );
    }

    protected function createTypeGroup($user, $name)
    {
        return $this->handler->contentTypeHandler()->createGroup(
            new Persistence\Content\Type\Group\CreateStruct(
                array(
                    'name' => array(),
                    'description' => array(),
                    'identifier' => $name,
                    'created' => time(),
                    'modified' => time(),
                    'creatorId' => $user->id,
                    'modifierId' => $user->id,
                )
            )
        );
    }

    protected function createUserGroupType( $user, $language, $usersContentTypeGroup )
    {
        $userGroupTypeCreate = new Persistence\Content\Type\CreateStruct(
            array(
                'name' => array(
                    $language->languageCode => 'User group',
                ),
                'status' => 0,
                'description' => array(),
                'identifier' => 'user_group',

                'created' => time(),
                'modified' => time(),
                'creatorId' => $user->id,
                'modifierId' => $user->id,

                'remoteId' => '25b4268cdcd01921b808a0d854b877ef',

                'urlAliasSchema' => '',
                'nameSchema' => '<name>',
                'isContainer' => true,
                'initialLanguageId' => $language->id,

                'sortField' => 1,
                'sortOrder' => 1,

                'groupIds' => array( $usersContentTypeGroup->id ),

                'fieldDefinitions' => array(
                    new Persistence\Content\Type\FieldDefinition(
                        array(
                            'name' => array(
                                $language->languageCode => 'Name',
                            ),
                            'description' => array(),
                            'identifier' => 'name',
                            'fieldGroup' => '',
                            'position' => 1,
                            'fieldType' => 'ezstring',
                            'isTranslatable' => true,
                            'isRequired' => true,
                            'isInfoCollector' => false,
                            'fieldTypeConstraints' => new Persistence\Content\FieldTypeConstraints(
                                array(
                                    'validators' => array(
                                        'StringLengthValidator' => array(
                                            'maxStringLength' => 255,
                                            'minStringLength' => 0,
                                        ),
                                    ),
                                    'fieldSettings' => NULL,
                                )
                            ),
                            'defaultValue' => new Persistence\Content\FieldValue(
                                array(
                                    'data' => NULL,
                                    'externalData' => NULL,
                                    'sortKey' => NULL,
                                )
                            ),
                            'isSearchable' => true,
                        )
                    ),
                    new Persistence\Content\Type\FieldDefinition(
                        array(
                            'name' => array(
                                $language->languageCode => 'Description',
                            ),
                            'description' => array(),
                            'identifier' => 'description',
                            'fieldGroup' => '',
                            'position' => 2,
                            'fieldType' => 'ezstring',
                            'isTranslatable' => true,
                            'isRequired' => false,
                            'isInfoCollector' => false,
                            'fieldTypeConstraints' => new Persistence\Content\FieldTypeConstraints(
                                array(
                                    'validators' => array(
                                        'StringLengthValidator' => array(
                                            'maxStringLength' => 255,
                                            'minStringLength' => 0,
                                        ),
                                    ),
                                    'fieldSettings' => NULL,
                                )
                            ),
                            'defaultValue' => new Persistence\Content\FieldValue(
                                array(
                                    'data' => NULL,
                                    'externalData' => NULL,
                                    'sortKey' => NULL,
                                )
                            ),
                            'isSearchable' => true,
                        )
                    ),
                ),
                'defaultAlwaysAvailable' => true,
            )
        );

        return $this->handler->contentTypeHandler()->create( $userGroupTypeCreate );
    }

    protected function createUserType( $user, $language, $usersContentTypeGroup )
    {
        $userGroupTypeCreate = new Persistence\Content\Type\CreateStruct(
            array(
                'name' => array(
                    $language->languageCode => 'User',
                ),
                'status' => 0,
                'description' => array(),
                'identifier' => 'user',

                'created' => time(),
                'modified' => time(),
                'creatorId' => $user->id,
                'modifierId' => $user->id,

                'remoteId' => 'user-8432795823475923',

                'urlAliasSchema' => '',
                'nameSchema' => '<name>',
                'isContainer' => true,
                'initialLanguageId' => $language->id,

                'sortField' => 1,
                'sortOrder' => 1,

                'groupIds' => array( $usersContentTypeGroup->id ),

                'fieldDefinitions' => array(
                    new Persistence\Content\Type\FieldDefinition(
                        array(
                            'name' => array(
                                $language->languageCode => 'Name',
                            ),
                            'description' => array(),
                            'identifier' => 'name',
                            'fieldGroup' => '',
                            'position' => 1,
                            'fieldType' => 'ezstring',
                            'isTranslatable' => true,
                            'isRequired' => true,
                            'isInfoCollector' => false,
                            'fieldTypeConstraints' => new Persistence\Content\FieldTypeConstraints(
                                array(
                                    'validators' => array(
                                        'StringLengthValidator' => array(
                                            'maxStringLength' => 255,
                                            'minStringLength' => 0,
                                        ),
                                    ),
                                    'fieldSettings' => NULL,
                                )
                            ),
                            'defaultValue' => new Persistence\Content\FieldValue(
                                array(
                                    'data' => NULL,
                                    'externalData' => NULL,
                                    'sortKey' => NULL,
                                )
                            ),
                            'isSearchable' => true,
                        )
                    ),
                    // @TODO: There are some common fields missing here
                ),
                'defaultAlwaysAvailable' => true,
            )
        );

        return $this->handler->contentTypeHandler()->create( $userGroupTypeCreate );
    }

    protected function createLandingPageType( $user, $language, $contentContentTypeGroup )
    {
        $landingPageTypeCreate = new Persistence\Content\Type\CreateStruct(
            array(
                'name' => array(
                    $language->languageCode => 'Landing Page',
                ),
                'status' => 0,
                'description' => array(),
                'identifier' => 'landing_page',

                'created' => time(),
                'modified' => time(),
                'creatorId' => $user->id,
                'modifierId' => $user->id,

                'remoteId' => 'e36c458e3e4a81298a0945f53a2c81f4',

                'urlAliasSchema' => '',
                'nameSchema' => '<name>',
                'isContainer' => true,
                'initialLanguageId' => $language->id,

                'sortField' => 1,
                'sortOrder' => 1,

                'groupIds' => array( $contentContentTypeGroup->id ),

                'fieldDefinitions' => array(
                    new Persistence\Content\Type\FieldDefinition(
                        array(
                            'name' => array(
                                $language->languageCode => 'Name',
                            ),
                            'description' => array(
                                $language->languageCode => '',
                            ),
                            'identifier' => 'name',
                            'fieldGroup' => '',
                            'position' => 1,
                            'fieldType' => 'ezstring',
                            'isTranslatable' => true,
                            'isRequired' => true,
                            'isInfoCollector' => false,
                            'fieldTypeConstraints' => new Persistence\Content\FieldTypeConstraints(
                                array(
                                    'validators' => array(
                                        'StringLengthValidator' => array(
                                            'maxStringLength' => 0,
                                            'minStringLength' => 0,
                                        ),
                                    ),
                                    'fieldSettings' => NULL,
                                )
                            ),
                            'defaultValue' => new Persistence\Content\FieldValue(
                                array(
                                    'data' => NULL,
                                    'externalData' => NULL,
                                    'sortKey' => NULL,
                                )
                            ),
                            'isSearchable' => true,
                        )
                    ),
                    new Persistence\Content\Type\FieldDefinition(
                        array(
                            'name' => array(
                                $language->languageCode => 'Layout',
                            ),
                            'description' => array(
                                $language->languageCode => '',
                            ),
                            'identifier' => 'page',
                            'fieldGroup' => '',
                            'position' => 2,
                            'fieldType' => 'ezpage',
                            'isTranslatable' => true,
                            'isRequired' => false,
                            'isInfoCollector' => false,
                            'fieldTypeConstraints' => new Persistence\Content\FieldTypeConstraints(
                                array(
                                    'validators' => NULL,
                                    'fieldSettings' => new \eZ\Publish\Core\FieldType\FieldSettings(
                                        array(
                                            'defaultLayout' => '',
                                        )
                                    )
                                )
                            ),
                            'defaultValue' => new Persistence\Content\FieldValue(
                                array(
                                    'data' => NULL,
                                    'externalData' => NULL,
                                    'sortKey' => NULL,
                                )
                            ),
                            'isSearchable' => false,
                        )
                    )
                ),
                'defaultAlwaysAvailable' => false,
            )
        );

        return $this->handler->contentTypeHandler()->create( $landingPageTypeCreate );
    }

    protected function createRootUserGroup( $user, $userGroupType, $usersSection, $rootLocation, $language )
    {
        $usersContentCreate = new Persistence\Content\CreateStruct(
            array(
                'name' => 'Users',
                'typeId' => $userGroupType->id,
                'sectionId' => $usersSection->id,
                'ownerId' => $user->id,
                'modified' => time(),

                'locations' => array(
                    $userRootLocation = new Persistence\Content\Location\CreateStruct(
                        array(
                            'priority' => 0,
                            'remoteId' => '3f6d92f8044aed134f32153517850f5a',
                            'parentId' => $rootLocation->id,
                            'pathIdentificationString' => 'users',
                            'sortField' => 1,
                            'sortOrder' => 1,
                        )
                    )
                ),

                'fields' => array(
                    new Persistence\Content\Field(
                        array(
                            'fieldDefinitionId' => (int) $this->getFieldDefinition( $userGroupType, 1 )->id,
                            'type' => 'ezstring',
                            'value' => new Persistence\Content\FieldValue(
                                array(
                                    'data' => 'Main group',
                                    'externalData' => NULL,
                                    'sortKey' => '',
                                )
                            ),
                            'languageCode' => $language->languageCode,
                        )
                    ),
                    new Persistence\Content\Field(
                        array(
                            'fieldDefinitionId' => (int) $this->getFieldDefinition( $userGroupType, 2 )->id,
                            'type' => 'ezstring',
                            'value' => new Persistence\Content\FieldValue(
                                array(
                                    'data' => 'Users',
                                    'externalData' => NULL,
                                    'sortKey' => '',
                                )
                            ),
                            'languageCode' => $language->languageCode,
                        )
                    ),
                ),

                'alwaysAvailable' => true,
                'remoteId' => 'f5c88a2209584891056f987fd965b0ba',

                'initialLanguageId' => $language->id,

                'name' => array(
                    $language->languageCode => 'Users',
                ),
            )
        );

        $userContent = $this->handler->contentHandler()->create( $usersContentCreate );
        return $this->handler->contentHandler()->publish(
            $userContent->versionInfo->id,
            $userContent->versionInfo->versionNo,
            new Persistence\Content\MetadataUpdateStruct()
        );
    }

    protected function createHome( $user, $landingPageType, $standardSection, $rootLocation, $language )
    {
        $homeContentCreate = new Persistence\Content\CreateStruct(
            array(
                'name' => 'Home',
                'typeId' => $landingPageType->id,
                'sectionId' => $standardSection->id,
                'ownerId' => $user->id,
                'modified' => time(),

                'locations' => array(
                    $homeLocation = new Persistence\Content\Location\CreateStruct(
                        array(
                            'priority' => '0',
                            'remoteId' => 'f3e90596361e31d496d4026eb624c983',
                            'parentId' => $rootLocation->id,
                            'pathIdentificationString' => '',
                            'sortField' => 8,
                            'sortOrder' => 1,
                        )
                    )
                ),

                'alwaysAvailable' => 1,
                'remoteId' => '8a9c9c761004866fb458d89910f52bee',

                'initialLanguageId' => $language->id,
                'name' => array(
                    $language->languageCode => 'Home',
                ),
                'fields' => array(
                    new Persistence\Content\Field(
                        array(
                            'fieldDefinitionId' => (int) $this->getFieldDefinition( $landingPageType, 1 )->id,
                            'type' => 'ezstring',
                            'value' => new Persistence\Content\FieldValue(
                                array(
                                    'data' => 'Home',
                                    'externalData' => NULL,
                                    'sortKey' => 'home',
                                )
                            ),
                            'languageCode' => $language->languageCode,
                        )
                    ),
                    new Persistence\Content\Field(
                        array(
                            'fieldDefinitionId' => (int) $this->getFieldDefinition( $landingPageType, 2 )->id,
                            'type' => 'ezpage',
                            'value' => new Persistence\Content\FieldValue(
                                array(
                                    'data' => new \eZ\Publish\Core\FieldType\Page\Parts\Page(
                                        new \eZ\Publish\Core\FieldType\Page\Service()
                                    ),
                                    'externalData' => NULL,
                                    'sortKey' => NULL,
                                )
                            ),
                            'languageCode' => $language->languageCode,
                        )
                    ),
                ),
            )
        );

        $homeContent = $this->handler->contentHandler()->create( $homeContentCreate );
        return $this->handler->contentHandler()->publish(
            $homeContent->versionInfo->id,
            $homeContent->versionInfo->versionNo,
            new Persistence\Content\MetadataUpdateStruct()
        );
    }

    protected function createUser( $user, $userGroupType, $usersSection, $rootLocation, $language, $remoteId, $name, $passwordHash )
    {
        $userContentCreate = new Persistence\Content\CreateStruct(
            array(
                'name' => 'Users',
                'typeId' => $userGroupType->id,
                'sectionId' => $usersSection->id,
                'ownerId' => $user->id,
                'modified' => time(),

                'locations' => array(
                    $userRootLocation = new Persistence\Content\Location\CreateStruct(
                        array(
                            'priority' => 0,
                            'remoteId' => $remoteId,
                            'parentId' => $rootLocation,
                            'sortField' => 1,
                            'sortOrder' => 1,
                        )
                    )
                ),

                'fields' => array(
                    new Persistence\Content\Field(
                        array(
                            'fieldDefinitionId' => (int) $this->getFieldDefinition( $userGroupType, 1 )->id,
                            'type' => 'ezstring',
                            'value' => new Persistence\Content\FieldValue(
                                array(
                                    'data' => $name,
                                    'externalData' => NULL,
                                    'sortKey' => '',
                                )
                            ),
                            'languageCode' => $language->languageCode,
                        )
                    ),
                ),

                'alwaysAvailable' => true,
                'remoteId' => $remoteId,

                'initialLanguageId' => $language->id,

                'name' => array(
                    $language->languageCode => $name,
                ),
            )
        );

        $userContent = $this->handler->contentHandler()->create( $userContentCreate );
        $userContent = $this->handler->contentHandler()->publish(
            $userContent->versionInfo->id,
            $userContent->versionInfo->versionNo,
            new Persistence\Content\MetadataUpdateStruct()
        );

        $user = $this->handler->userHandler()->create(
            new Persistence\User(
                array(
                    'id' => $userContent->versionInfo->contentInfo->id,
                    'login' => $name,
                    'email' => 'nospam@ez.no',
                    'isEnabled' => true,
                    'passwordHash' => $passwordHash,
                    'hashAlgorithm' => '2',
                )
            )
        );

        return $user;
    }

    /**
     * Get field definition at position
     *
     * @param mixed $type
     * @param mixed $position
     * @return void
     */
    protected function getFieldDefinition( $type, $position )
    {
        foreach( $type->fieldDefinitions as $fieldDefinition )
        {
            if ( $fieldDefinition->position == $position )
            {
                return $fieldDefinition;
            }
        }
        throw new \RuntimeException( "Field definition with position $position not found." );
    }

    /**
     * Initilialize database schema
     *
     * @return void
     */
    public function initializeSchema()
    {
        foreach ( $this->getSchemaStatements() as $statement )
        {
            $this->database->exec( $statement );
        }
    }

    /**
     * Returns the database schema as an array of SQL statements
     *
     * @return string[]
     */
    protected function getSchemaStatements()
    {

        return array_filter(
            preg_split(
                '(;\\s*$)m',
                file_get_contents(
                    __DIR__ . '/schema/schema.mysql.sql'
                )
            )
        );
    }
}
