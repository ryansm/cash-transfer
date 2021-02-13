<?php

namespace App\Contracts;

interface IWritableRepository
{
    public function create(array $data): string;

    public function update(array $data, string $id): string;

    public function delete(string $id): bool;
}
