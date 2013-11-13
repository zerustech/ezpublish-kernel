<?php

/**
 * File containing the eZ\Publish\API\Repository\Values\User\Role class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 *
 * @version //autogentag//
 */
namespace eZ\Publish\API\Repository\Values\User;

use eZ\Publish\API\Repository\Values\MultiLanguageValueBase;

/**
 * This class represents a role.
 *
 * @property-read mixed $id the internal id of the role
 * @property-read array $policies an array of the policies {@link \eZ\Publish\API\Repository\Values\User\Policy} of the role.
 */
abstract class Role extends MultiLanguageValueBase
{
    /**
     * ID of the user role.
     *
     * @var mixed
     */
    protected $id;

    /**
     * Creation date of the content type.
     *
     * @var \DateTime
     */
    protected $creationDate;

    /**
     * Modification date of the content type.
     *
     * @var \DateTime
     */
    protected $modificationDate;

    /**
     * Creator user id of the content type.
     *
     * @var mixed
     */
    protected $creatorId;

    /**
     * Modifier user id of the content type.
     *
     * @var mixed
     */
    protected $modifierId;

    /**
     * Returns the list of policies of this role.
     *
     * @return \eZ\Publish\API\Repository\Values\User\Policy[]
     */
    abstract public function getPolicies();
}
