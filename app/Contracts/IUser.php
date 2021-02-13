<?php

namespace App\Contracts;

use App\Models\User;

interface IUser
{
    /**
     * Show the profile for a given user.
     *
     * @param string $id
     * @return User
     */
    public function find(string $id): User;

    /**
     * Store a new user.
     *
     * @param array $data
     * @return User
     */
    public function create(array $data): User;

    /**
     * Update the given user.
     *
     * @param array $data
     * @param string $id
     * @return User
     */
    public function update(array $data, string $id): User;

    /**
     * Delete the given user.
     *
     * @param string $id
     * @return bool
     */
    public function delete($id): bool;
}
