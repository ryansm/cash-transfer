<?php

namespace App\Services;

use App\Contracts\ITransactionService;
use App\Events\TransactionCompleted;
use App\Jobs\TransactionAuthorizationJob;
use App\Models\Transaction;
use App\Models\User;
use App\Repositories\TransactionRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class TransactionService extends ServiceProvider implements ITransactionService
{
    /**
     * @var TransactionRepository
     */
    protected $transactionRepository;

    /**
     * @var UserService
     */
    protected $userService;

    public function __construct(TransactionRepository $transactionRepository, UserService $userService)
    {
        $this->transactionRepository = $transactionRepository;
        $this->userService = $userService;
    }

    public function find(string $id): Transaction
    {
        try {
            $transaction = $this->transactionRepository->find($id);

            if (!$transaction) {
                throw new Exception('Transação não encontrada.');
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());
            throw new Exception($e->getMessage());
        }

        return $transaction;
    }

    public function create(array $data): Transaction
    {
        $transaction = Transaction::factory()->make($data);

        DB::beginTransaction();

        try {
            if ($transaction->payer->type == User::LOJISTA) {
                throw new Exception('Lojistas não podem realizar transações.');
            }

            $new_transaction_id = $this->transactionRepository->create($transaction->getAttributes());
            $res = $this->find($new_transaction_id);

            $this->userService->withdrawCredit($transaction->payer->id, $transaction->value);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            throw new Exception($e->getMessage());
        }

        TransactionAuthorizationJob::dispatch($res);

        return $res;
    }

    public function authorizeTransaction(Transaction $transaction, bool $isAuthorized): void
    {
        $status = $isAuthorized ? Transaction::OK : Transaction::CANCELADO;
        $user_id = $isAuthorized ? $transaction->payee_id : $transaction->payer_id;

        DB::beginTransaction();

        try {
            $this->transactionRepository->setStatus($transaction->id, $status);
            $res = $this->find($transaction->id);

            $this->userService->depositCredit($user_id, $transaction->value);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            throw new Exception($e->getMessage());
        }

        TransactionCompleted::dispatch($res);
    }
}
