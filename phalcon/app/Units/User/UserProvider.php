<?php namespace Phasty\Units\User;

use Phalcon\Mvc\User\Plugin;
use Phasty\Models\Users;

class UserProvider extends Plugin implements IUserProvider
{

    /**
     * Retrieve a user by their unique identifier.
     *
     * @param  mixed $id
     * @return Users
     */
    public function retrieveById($id)
    {
        //return $this->edb->row('SELECT id, name, email, password, profile FROM users Where id = ?', (int)$id);
        $user = Users::query()->columns(['id', 'name', 'email', 'role', 'profile'])->where('id = :id:')
            ->bind(['id' => $id])->cache(['key' => 'user_id_' . (int)$id])
            ->execute()->getFirst();

        if ( ! $id || ! $user) {
            throw new HTTPException(
                'User not found',
                500,
                array(
                    'dev'  => 'Not provided id for user, or user not found',
                    'more' => '',
                )
            );
        }
        return $user;
    }

    /**
     * Retrieve a user by the given credentials.
     *
     * @param  array $credentials
     * @return Users
     */
    public function retrieveByCredentials(array $credentials)
    {
        if (empty($credentials) ||
            (count($credentials) === 1 &&
                array_key_exists('password', $credentials))
        ) {
            return;
        }

        $user = Users::query()->where('email = :email:')
            ->bind(['email' => $credentials['email']])
            ->execute()->getFirst();

        if ( ! $user) {
            return false;
        }

        return $user;
    }

    /**
     * Validate a user against the given credentials.
     *
     * @param  Users $user
     * @param  array $credentials
     * @return bool
     */
    public function validateCredentials($user, array $credentials)
    {
        return $this->security->checkHash(trim($credentials['password']), $user->password);
    }
}