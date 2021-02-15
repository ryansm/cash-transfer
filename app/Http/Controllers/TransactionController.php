<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransactionRequest;
use App\Services\TransactionService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Redis;

class TransactionController extends Controller
{
    /**
     * @var TransactionService
     */
    protected $transactionService;

    /**
     * TransactionController constructor.
     *
     * @param TransactionService $transactionService
     */
    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    /**
     * Store a new transaction.
     *
     * @param TransactionRequest $request
     * @return JsonResponse
     */
    public function store(TransactionRequest $request): JsonResponse
    {
        try {
            $transaction = $this->transactionService->create([
                'value' => $request->value,
                'payer_id' => $request->payer,
                'payee_id' => $request->payee,
            ]);

            return new JsonResponse(['message' => 'Transação em andamento!'], Response::HTTP_ACCEPTED);
        } catch (Exception $e) {
            return new JsonResponse(['message' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
}
