<?php

namespace App\Contracts;

use App\Models\Transaction;

interface ITransactionService
{
    /**
     * Get the transaction data.
     *
     * @param array $data
     * @return Transaction
     */
    public function find(string $id): Transaction;

    /**
     * Store a new transaction.
     *
     * @param array $data
     * @return Transaction
     */
    public function create(array $data): Transaction;

    /**
     * Define if the transaction was authorized.
     * If yes, transfer the credit to the payee.
     * If no, transfer the credit back to the payer.
     */
    public function authorizeTransaction(Transaction $transaction, bool $canAuthorize): void;
}
