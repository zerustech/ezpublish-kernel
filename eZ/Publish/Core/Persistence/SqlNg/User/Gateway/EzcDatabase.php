<?php
/**
 * File containing the EzcDatabase location gateway class
 *
 * @copyright Copyright (C) 1999-2012 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 */

namespace eZ\Publish\Core\Persistence\SqlNg\User\Gateway;

use eZ\Publish\Core\Persistence\SqlNg\User\Gateway;
use eZ\Publish\Core\Persistence\Legacy\EzcDbHandler;
use eZ\Publish\SPI\Persistence\User;
use eZ\Publish\Core\Base\Exceptions\NotFoundException as NotFound;

/**
 * User gateway implementation using the zeta database component.
 */
class EzcDatabase extends Gateway
{
    /**
     * Database dbHandler
     *
     * @var EzcDbHandler
     */
    protected $dbHandler;

    /**
     * Construct from database dbHandler
     *
     * @param \EzcDbHandler $dbHandler
     */
    public function __construct( EzcDbHandler $dbHandler )
    {
        $this->dbHandler = $dbHandler;
    }

    /**
     * Create user
     *
     * @param user $user
     *
     * @return mixed
     */
    public function createUser( User $user )
    {
        $query = $this->dbHandler->createInsertQuery();
        $query
            ->insertInto( $this->dbHandler->quoteTable( 'ezuser' ) )
            ->set(
                $this->dbHandler->quoteColumn( 'user_id' ),
                $query->bindValue( $user->id, null, \PDO::PARAM_INT )
            )->set(
                $this->dbHandler->quoteColumn( 'content_id' ),
                $query->bindValue( $user->id, null, \PDO::PARAM_INT )
            )->set(
                $this->dbHandler->quoteColumn( 'login' ),
                $query->bindValue( $user->login )
            )->set(
                $this->dbHandler->quoteColumn( 'email' ),
                $query->bindValue( $user->email )
            )->set(
                $this->dbHandler->quoteColumn( 'password_hash' ),
                $query->bindValue( $user->passwordHash )
            )->set(
                $this->dbHandler->quoteColumn( 'password_hash_type' ),
                $query->bindValue( $user->hashAlgorithm, null, \PDO::PARAM_INT )
            )->set(
                $this->dbHandler->quoteColumn( 'is_enabled' ),
                $query->bindValue( $user->isEnabled, null, \PDO::PARAM_INT )
            )->set(
                $this->dbHandler->quoteColumn( 'max_login' ),
                $query->bindValue( $user->maxLogin, null, \PDO::PARAM_INT )
            );
        $query->prepare()->execute();
    }

    /**
     * Delete user with the given ID.
     *
     * @param mixed $userId
     */
    public function deleteUser( $userId )
    {
        $query = $this->dbHandler->createDeleteQuery();
        $query
            ->deleteFrom( $this->dbHandler->quoteTable( 'ezuser' ) )
            ->where(
                $query->expr->eq(
                    $this->dbHandler->quoteColumn( 'user_id' ),
                    $query->bindValue( $userId, null, \PDO::PARAM_INT )
                )
            );
        $statement = $query->prepare();
        $statement->execute();

        if ( $statement->rowCount() < 1 )
        {
            throw new NotFound( 'user', $userId );
        }
    }

    /**
     * Loads user with user ID.
     *
     * @param mixed $userId
     *
     * @return array
     */
    public function load( $userId )
    {
        $query = $this->dbHandler->createSelectQuery();
        $query->select(
            $this->dbHandler->quoteColumn( 'user_id', 'ezuser' ),
            $this->dbHandler->quoteColumn( 'content_id', 'ezuser' ),
            $this->dbHandler->quoteColumn( 'login', 'ezuser' ),
            $this->dbHandler->quoteColumn( 'email', 'ezuser' ),
            $this->dbHandler->quoteColumn( 'password_hash', 'ezuser' ),
            $this->dbHandler->quoteColumn( 'password_hash_type', 'ezuser' ),
            $this->dbHandler->quoteColumn( 'is_enabled', 'ezuser' ),
            $this->dbHandler->quoteColumn( 'max_login', 'ezuser' )
        )->from(
            $this->dbHandler->quoteTable( 'ezuser' )
        )->where(
            $query->expr->eq(
                $this->dbHandler->quoteColumn( 'user_id', 'ezuser' ),
                $query->bindValue( $userId, null, \PDO::PARAM_INT )
            )
        );

        $statement = $query->prepare();
        $statement->execute();

        return $statement->fetchAll( \PDO::FETCH_ASSOC );
    }

    /**
     * Loads user with user ID.
     *
     * @param string $login
     * @param string|null $email
     *
     * @return array
     */
    public function loadByLoginOrMail( $login, $email = null )
    {
        $query = $this->dbHandler->createSelectQuery();
        $query->select(
            $this->dbHandler->quoteColumn( 'user_id', 'ezuser' ),
            $this->dbHandler->quoteColumn( 'content_id', 'ezuser' ),
            $this->dbHandler->quoteColumn( 'login', 'ezuser' ),
            $this->dbHandler->quoteColumn( 'email', 'ezuser' ),
            $this->dbHandler->quoteColumn( 'password_hash', 'ezuser' ),
            $this->dbHandler->quoteColumn( 'password_hash_type', 'ezuser' ),
            $this->dbHandler->quoteColumn( 'is_enabled', 'ezuser' ),
            $this->dbHandler->quoteColumn( 'max_login', 'ezuser' )
        )->from(
            $this->dbHandler->quoteTable( 'ezuser' )
        )->where(
            empty( $email ) ?
                $query->expr->eq(
                    $this->dbHandler->quoteColumn( 'login', 'ezuser' ),
                    $query->bindValue( $login )
                ) :
                $query->expr->lOr(
                    $query->expr->eq(
                        $this->dbHandler->quoteColumn( 'login', 'ezuser' ),
                        $query->bindValue( $login )
                    ),
                    $query->expr->eq(
                        $this->dbHandler->quoteColumn( 'email', 'ezuser' ),
                        $query->bindValue( $email )
                    )
                )
        );

        $statement = $query->prepare();
        $statement->execute();

        return $statement->fetchAll( \PDO::FETCH_ASSOC );
    }

    /**
     * Update the user information specified by the user struct
     *
     * @param User $user
     */
    public function updateUser( User $user )
    {
        $query = $this->dbHandler->createUpdateQuery();
        $query
            ->update( $this->dbHandler->quoteTable( 'ezuser' ) )
            ->set(
                $this->dbHandler->quoteColumn( 'login' ),
                $query->bindValue( $user->login )
            )->set(
                $this->dbHandler->quoteColumn( 'email' ),
                $query->bindValue( $user->email )
            )->set(
                $this->dbHandler->quoteColumn( 'password_hash' ),
                $query->bindValue( $user->passwordHash )
            )->set(
                $this->dbHandler->quoteColumn( 'password_hash_type' ),
                $query->bindValue( $user->hashAlgorithm )
            )->set(
                $this->dbHandler->quoteColumn( 'is_enabled' ),
                $query->bindValue( $user->isEnabled, null, \PDO::PARAM_INT )
            )->set(
                $this->dbHandler->quoteColumn( 'max_login' ),
                $query->bindValue( $user->maxLogin, null, \PDO::PARAM_INT )
            )->where(
                $query->expr->eq(
                    $this->dbHandler->quoteColumn( 'user_id' ),
                    $query->bindValue( $user->id, null, \PDO::PARAM_INT )
                )
            );
        $statement = $query->prepare();
        $statement->execute();
    }

    /**
     * Assigns role to user with given limitation
     *
     * @param mixed $contentId
     * @param mixed $roleId
     * @param array $limitation
     */
    public function assignRole( $contentId, $roleId, array $limitation )
    {
        foreach ( $limitation as $identifier => $values )
        {
            foreach ( $values as $value )
            {
                $query = $this->dbHandler->createInsertQuery();
                $query
                    ->insertInto( $this->dbHandler->quoteTable( 'ezrole_content_rel' ) )
                    ->set(
                        $this->dbHandler->quoteColumn( 'content_id' ),
                        $query->bindValue( $contentId, null, \PDO::PARAM_INT )
                    )->set(
                        $this->dbHandler->quoteColumn( 'role_id' ),
                        $query->bindValue( $roleId, null, \PDO::PARAM_INT )
                    )->set(
                        $this->dbHandler->quoteColumn( 'limit_identifier' ),
                        $query->bindValue( $identifier )
                    )->set(
                        $this->dbHandler->quoteColumn( 'limit_value' ),
                        $query->bindValue( $value )
                    );
                $query->prepare()->execute();
            }
        }
    }

    /**
     * Remove role from user
     *
     * @param mixed $contentId
     * @param mixed $roleId
     */
    public function removeRole( $contentId, $roleId )
    {
        $query = $this->dbHandler->createDeleteQuery();
        $query
            ->deleteFrom( $this->dbHandler->quoteTable( 'ezrole_content_rel' ) )
            ->where(
                $query->expr->lAnd(
                    $query->expr->eq(
                        $this->dbHandler->quoteColumn( 'content_id' ),
                        $query->bindValue( $contentId, null, \PDO::PARAM_INT )
                    ),
                    $query->expr->eq(
                        $this->dbHandler->quoteColumn( 'role_id' ),
                        $query->bindValue( $roleId, null, \PDO::PARAM_INT )
                    )
                )
            );
        $query->prepare()->execute();
    }
}
