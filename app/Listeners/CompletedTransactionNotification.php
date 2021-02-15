<?php

namespace App\Listeners;

use App\Events\TransactionCompleted;
use App\Jobs\SendMessageJob;
use App\Models\Transaction;
use App\Services\MessageService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CompletedTransactionNotification
{
    /**
     * @var MessageService
     */
    private $messageService;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(MessageService $messageService)
    {
        $this->messageService = $messageService;
    }

    /**
     * Handle the event.
     *
     * @param  TransactionCompleted  $event
     * @return void
     */
    public function handle(TransactionCompleted $event)
    {
        $value = $event->transaction->value;
        $payer_name = $event->transaction->payer->name;
        $payee_name = $event->transaction->payer->name;
        $payer_id = $event->transaction->payer_id;
        $payee_id = $event->transaction->payee_id;

        if ($event->transaction->status == Transaction::OK) {
            $content = 'Você enviou R$' . $value . ' para ' . $payee_name . '.';
            $message = $this->messageService->create([
                'user_id' => $payer_id,
                'content' => $content,
            ]);

            $content = $payer_name . ' te enviou R$' . $value . '.';
            $message = $this->messageService->create([
                'user_id' => $payee_id,
                'content' => $content,
            ]);
        } else {
            $content = 'A transferência no valor de R$' . $value . ' para ' . $payer_name . ' falhou.';
            $message = $this->messageService->create([
                'user_id' => $payer_id,
                'content' => $content,
            ]);
        }

        SendMessageJob::dispatch($message);
    }
}
