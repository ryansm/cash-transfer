<?php

namespace App\Contracts;

use App\Models\Message;

interface ISendMessageService
{
    /**
     * Send message to the payee after transaction was authorized.
     *
     * @param Message $message
     * @return bool
     */
    public function execute(Message $message): bool;
}
