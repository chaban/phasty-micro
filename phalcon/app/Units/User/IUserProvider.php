<?php namespace Phasty\Units\User;

interface IUserProvider
{
    /**
     * Retrieve a user by their unique identifier.
     *
     * @param  mixed  $identifier
     * @return \stdClass
     */
    public function retrieveById($identifier);

    /**
     * Retrieve a user by the given credentials.
     *
     * @param  array  $credentials
     * @return \stdClass
     */
    public function retrieveByCredentials(array $credentials);

    /**
     * Validate a user against the given credentials.
     *
     * @param  \stdClass $user
     * @param  array  $credentials
     * @return bool
     */
    public function validateCredentials($user, array $credentials);
}