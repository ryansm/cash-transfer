<?php

namespace App\Contracts;

use App\Models\Transaction;
use App\Models\User;

interface ITransaction
{
    /**
     * Store a new transaction.
     *
     * @param array $data
     * @return Transaction
     */
    public function create(array $data): Transaction;

    /**
     * Transfer cash from payer to payee changing their respective balance.
     *
     * @param User $payer
     * @param User $payee
     * @param float $value
     * @return void
     */
    public function transferCash(User $payer, User $payee, float $value): void;
}
