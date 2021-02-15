<?php

namespace App\Jobs;

use App\Models\Transaction;
use App\Services\TransactionAuthorizationValidateService;
use App\Services\TransactionService;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class TransactionAuthorizationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 5;

    /**
     * The maximum number of unhandled exceptions to allow before failing.
     *
     * @var int
     */
    public $maxExceptions = 5;

    /**
     * @var Transaction
     */
    private $transaction;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(TransactionAuthorizationValidateService $transactionAuthorizationValidateService, TransactionService $transactionService): void
    {
        try {
            $isAuthorized = $transactionAuthorizationValidateService->execute($this->transaction);
            $transactionService->authorizeTransaction($this->transaction, $isAuthorized);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}
