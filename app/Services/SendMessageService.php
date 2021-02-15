<?php

namespace App\Services;

use App\Contracts\ISendMessageService;
use App\Models\Message;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class SendMessageService extends ServiceProvider implements ISendMessageService
{
    public function __construct()
    {
        //
    }

    public function execute(Message $message): bool
    {
        $id = $message->id; // mock - b19f7b9f-9cbf-4fc6-ad22-dc30601aec04

        try {
            $res = Http::get('https://run.mocky.io/v3/b19f7b9f-9cbf-4fc6-ad22-dc30601aec04');
            $isSent = $res->successful() && $res['message'] == 'Enviado';
        } catch (Exception $e) {
            Log::error($e->getMessage());
            throw new Exception($e->getMessage());
        }

        return $isSent;
    }
}
