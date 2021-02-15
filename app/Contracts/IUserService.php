<?php

namespace App\Contracts;

use App\Models\User;

interface IUserService
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

    /**
     * Withdraw the user's credit.
     *
     * @param string $user_id
     * @param float $value
     * @return void
     */
    public function withdrawCredit(string $user_id, float $value): void;

    /**
     * Deposit credit to user.
     *
     * @param string $user_id
     * @param float $value
     * @return void
     */
    public function depositCredit(string $user_id, float $value): void;
}
