<?php

namespace App\Contracts;

interface IReadOnlyRepository
{
    public function find(string $id): ?object;
}
