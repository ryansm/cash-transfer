<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TransactionTest extends TestCase
{
    use RefreshDatabase;

    private function getUserId(int $type) {
        $user = User::factory()->create([
            'type' => $type,
            'balance' => 1000,
        ]);

        return $user->id;
    }

    /**
     * Test the creation of a new transaction.
     *
     * @return void
     */
    public function test_creating_a_new_transaction()
    {
        $payer = $this->getUserId(0);
        $payee = $this->getUserId(0);

        $response = $this->postJson('/api/transaction', [
            'value' => 100,
            'payer' => $payer,
            'payee' => $payee,
        ]);

        $response
            ->assertStatus(201)
            ->assertExactJson(['message' => 'Transação criada com sucesso!']);
    }

    /**
     * Test the creation of a new transaction from a storekeeper.
     *
     * @return void
     */
    public function test_creating_a_new_transaction_from_a_storekeeper()
    {
        $payer = $this->getUserId(1);
        $payee = $this->getUserId(0);

        $response = $this->postJson('/api/transaction', [
            'value' => 100,
            'payer' => $payer,
            'payee' => $payee,
        ]);

        $response
            ->assertStatus(400)
            ->assertExactJson(['message' => 'Lojistas não podem realizar transações.']);
    }

    /**
     * Test the creation of a new transaction that negative the payer balance.
     * It happens if the value of the transaction is greater than payer's balance (amount of cash).
     *
     * @return void
     */
    public function test_creating_a_new_transaction_that_negativate_payer_balance()
    {
        $payer = $this->getUserId(0);
        $payee = $this->getUserId(0);

        $response = $this->postJson('/api/transaction', [
            'value' => 2000,
            'payer' => $payer,
            'payee' => $payee,
        ]);

        $response
            ->assertStatus(400)
            ->assertExactJson(['message' => 'O valor da transação é maior que o saldo do pagador.']);
    }
}
