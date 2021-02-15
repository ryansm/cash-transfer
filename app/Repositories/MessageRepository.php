<?php

namespace App\Repositories;

use App\Contracts\IReadOnlyRepository;
use App\Models\Message;
use Illuminate\Support\Facades\DB;

class MessageRepository implements IReadOnlyRepository
{
    public function find(string $id): ?Message
    {
        $res = DB::table('messages')->find($id);
        $user = Message::factory()->make((array) $res);

        return $user;
    }

    public function create(array $data): string
    {
        $res = DB::table('messages')->insertGetId($data);

        return $res;
    }

    public function setSentStatus(string $id, bool $sent): string
    {
        $res = DB::table('messages')
                    ->where('id', $id)
                    ->update(['sent' => $sent]);

        return $res;
    }
}
