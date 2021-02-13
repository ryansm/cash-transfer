<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory(1)->create(
            [
                'type' => 0,
                'name' => 'John Doe',
                'cpf_cnpj' => '12345678901234',
                'balance' => 1000,
                'email' => 'john.doe@email.com',
                'password' => Hash::make('password'),
            ]
        );

        User::factory(1)->create(
            [
                'type' => 1,
                'name' => 'Jane Doe',
                'cpf_cnpj' => '12345678909876',
                'balance' => 1000,
                'email' => 'jane.doe@email.com',
                'password' => Hash::make('password'),
            ]
        );
    }
}
