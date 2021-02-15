<?php

namespace App\Contracts;

use App\Models\Transaction;

interface ITransactionAuthorizationValidateService
{
    /**
     * Check if the transaction was authorized.
     *
     * @param Transaction $transaction
     * @return bool
     */
    public function execute(Transaction $transaction): bool;
}
