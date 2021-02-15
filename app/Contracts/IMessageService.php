<?php

namespace App\Contracts;

use App\Models\Message;

interface IMessageService
{
    /**
     * Get the message data.
     *
     * @param array $data
     * @return Message
     */
    public function find(string $id): Message;

    /**
     * Store a new message.
     *
     * @param array $data
     * @return Message
     */
    public function create(array $data): Message;
}
