<?php

namespace App\Repositories;

use App\Contracts\IReadOnlyRepository;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class TransactionRepository implements IReadOnlyRepository
{
    public function find(string $id): ?Transaction
    {
        $res = DB::table('transactions')->find($id);
        $user = Transaction::factory()->make((array) $res);

        return $user;
    }

    public function create(array $data): string
    {
        $res = DB::table('transactions')->insertGetId($data);

        return $res;
    }

    public function setStatus(string $id, int $status): string
    {
        $res = DB::table('transactions')
                    ->where('id', $id)
                    ->update(['status' => $status]);

        return $res;
    }
}
