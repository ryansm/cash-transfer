<?php

namespace App\Services;

use App\Contracts\ITransactionAuthorizationValidateService;
use App\Models\Transaction;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class TransactionAuthorizationValidateService extends ServiceProvider implements ITransactionAuthorizationValidateService
{
    public function __construct()
    {
        //
    }

    public function execute(Transaction $transaction): bool
    {
        $id = $transaction->id; // mock - 8fafdd68-a090-496f-8c9a-3442cf30dae6

        try {
            $res = Http::get('https://run.mocky.io/v3/8fafdd68-a090-496f-8c9a-3442cf30dae6');
            $isAuthorized = $res->successful() && $res['message'] == 'Autorizado';
        } catch (Exception $e) {
            Log::error($e->getMessage());
            throw new Exception($e->getMessage());
        }

        return $isAuthorized;
    }
}
