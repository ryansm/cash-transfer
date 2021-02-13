<?php

namespace App\Services;

use App\Contracts\ITransaction;
use App\Models\Transaction;
use App\Models\User;
use App\Repositories\TransactionRepository;
use Exception;
use Illuminate\Support\Facades\DB;

class TransactionService implements ITransaction
{
    /**
     * @var UserRepository
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

            if (empty($transaction->getAttributes())) {
                throw new Exception('Transação não encontrada.');
            }

            return $transaction;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function create(array $data): Transaction
    {
        $transaction = Transaction::factory()->make($data);

        DB::beginTransaction();

        try {
            $transaction_value = $transaction->getAttributes()['value'];
            $payer_id = $transaction->getAttributes()['payer_id'];
            $payee_id = $transaction->getAttributes()['payee_id'];

            $payer = $this->userService->find($payer_id);
            $payee = $this->userService->find($payee_id);


            if ($payer->type == User::LOJISTA) {
                throw new Exception('Lojistas não podem realizar transações.');
            }

            if ($payer->balance < $transaction_value) {
                throw new Exception('O valor da transação é maior que o saldo do pagador.');
            }

            $this->transferCash($payer, $payee, $transaction_value);

            $new_transaction_id = $this->transactionRepository->create($transaction->getAttributes());

            if ($new_transaction_id) {
                $res = $this->find($new_transaction_id);
            }

            DB::commit();

            return $res;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }

    public function transferCash(User $payer, User $payee, float $value): void
    {
        $this->userService->update(['balance' => $payer->balance - $value], $payer->id);
        $this->userService->update(['balance' => $payee->balance + $value], $payee->id);
    }
}
