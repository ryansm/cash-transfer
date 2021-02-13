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
        $user = new Transaction((array) $res);

        return $user;
    }

    public function create(array $data): string
    {
        $res = DB::table('transactions')->insertGetId($data);

        return $res;
    }
}
