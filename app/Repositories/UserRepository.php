<?php

namespace App\Repositories;

use App\Contracts\IReadOnlyRepository;
use App\Contracts\IWritableRepository;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class UserRepository implements IReadOnlyRepository, IWritableRepository
{
    public function all(): LengthAwarePaginator
    {
        $res = DB::table('users')->paginate(20);

        return $res;
    }

    public function find(string $id): ?User
    {
        $res = DB::table('users')->find($id);
        $user = $res ? User::factory()->make((array) $res) : null;

        return $user;
    }

    public function create(array $data): string
    {
        $res = DB::table('users')->insertGetId($data);

        return $res;
    }

    public function update(array $data, string $id): string
    {
        $res = DB::table('users')
                    ->where('id', $id)
                    ->update($data);

        return $res;
    }

    public function delete(string $id): bool
    {
        $res = DB::table('users')
                    ->where('id', $id)
                    ->delete();

        return $res;
    }

    public function isUnique(string $field, string $value, string $id): bool
    {
        $res = DB::table('users')
                    ->where([
                        [$field, '=', $value],
                        ['id', '<>', $id],
                    ])
                    ->get();

        return $res->isEmpty();
    }
}
